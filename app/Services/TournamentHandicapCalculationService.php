<?php

namespace App\Services;

use App\Exceptions\HandicapCalculationException;
use App\Models\Participant;
use App\Models\ParticipantCourse;
use App\Models\ParticipantCourseHandicap;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use NXP\MathExecutor;

class TournamentHandicapCalculationService
{

    protected $updatedCount = 0;
    public function calculate($tournament)
    {
        try {

            DB::beginTransaction();

            $participants = $this->getParticipantData($tournament->tournament_id);

            if ($participants->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No participants found for this tournament',
                    'updated_count' => 0
                ]);
            }


            $ratingsAndFormulas = $this->getRatingsAndCourseHandicapFormula($tournament);

            $ratingsArr = $ratingsAndFormulas['ratingsArr'];
            $courseHandicapFormula = $ratingsAndFormulas['courseHandicapFormula'];




            $executor = new MathExecutor();
            $this->registerMathFunctions($executor);



            $updatedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($participants as $participant) {

                $whsHandicapIndex = $participant->final_whs_handicap_index;
                $localHandicapIndex = $participant->final_local_handicap_index;

                $tournamentHandicap = $this->executeForula($executor, $tournament, $whsHandicapIndex, $localHandicapIndex);

                // Update participant with calculated handicap
                // Log the SQL query before executing
                DB::enableQueryLog();

                $this->updateParticipantTournamentHandicap($participant, $tournamentHandicap);

                $this->updateParticipantTournamentCourseHandicaps($participant, $tournament, $tournamentHandicap, $ratingsArr, $courseHandicapFormula);
            }














            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Tournament handicaps calculated successfully. Updated: $updatedCount, Skipped: $skippedCount",
                'updated_count' => $updatedCount,
                'skipped_count' => $skippedCount,
                'errors' => !empty($errors) ? $errors : null
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error calculating tournament handicaps', [
                'tournament_id' => $tournament->tournament_id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function updateParticipantTournamentCourseHandicaps($participant, $tournament, $tournamentHandicap, $ratingsArr, $courseHandicapFormula)
    {

        $participantCourses = ParticipantCourse::with('course')->where('participant_id', $participant->participant_id)
            ->where('tournament_id', $tournament->tournament_id)
            ->get();



        foreach ($participantCourses as $participantCourse) {

            $courseRatingFormula = $courseHandicapFormula[$participantCourse->course_id];

            foreach ($participant->participantCourseHandicaps as $teeHandicap) {

                $slopeRating = $ratingsArr[$teeHandicap->course_id][$teeHandicap->tee_id]['slope_rating'];
                $courseRating = $ratingsArr[$teeHandicap->course_id][$teeHandicap->tee_id]['course_rating'];
                $par = $ratingsArr[$teeHandicap->course_id][$teeHandicap->tee_id]['par'];
                $courseHandicap = $this->calculateCourseHandicap($courseRatingFormula, $tournamentHandicap, $slopeRating, $courseRating, $par);
                ParticipantCourseHandicap::where([
                    'tournament_id' => $tournament->tournament_id,
                    'participant_id' => $participant->participant_id,
                    'course_id' => $participantCourse->course_id,
                    'tee_id' => $teeHandicap->tee_id,
                ])
                    ->update([
                        'course_handicap' => $courseHandicap,
                        'final_course_handicap' => $courseHandicap,
                        'updated_by' => Auth::id()
                    ]);
            }
        }
    }

    private function updateParticipantTournamentHandicap($participant, $tournamentHandicap)
    {

        $this->updatedCount++;

        Participant::where($participant->particpant_id)->update([
            'tournament_handicap_index' => $tournamentHandicap,
            'final_tournament_handicap_index' => $tournamentHandicap,
            'updated_by' => Auth::id()
        ]);
    }


    private function executeForula($executor, $tournament, $whsHandicapIndex, $localHandicapIndex)
    {

        $executor->setVar('WHS_HANDICAP_INDEX', (float) ($whsHandicapIndex ?? 0));
        $executor->setVar('LOCAL_HANDICAP_INDEX', (float) ($localHandicapIndex ?? 0));

        // Execute the formula

        $expression  = $this->determineExpression($tournament, $whsHandicapIndex, $localHandicapIndex);
        $tournamentHandicap = $executor->execute($expression);

        return $tournamentHandicap;
    }


    private function determineExpression($tournament, $whsHandicapIndex, $localHandicapIndex): string
    {
        // Determine which formula to use based on available data
        if ($whsHandicapIndex === null && $localHandicapIndex === null) {
            $expression = $tournament->tournament_handicap_formula_4;
        } elseif ($whsHandicapIndex !== null && $localHandicapIndex !== null) {
            $expression = $tournament->tournament_handicap_formula_1;
        } elseif ($whsHandicapIndex !== null && $localHandicapIndex === null) {
            $expression = $tournament->tournament_handicap_formula_2;
        } elseif ($localHandicapIndex !== null && $whsHandicapIndex === null) {
            $expression = $tournament->tournament_handicap_formula_3;
        }

        if (empty($expression)) {
            throw new HandicapCalculationException(
                'No valid tournament handicap formula found. Please check the tournament configuration.',
                'Invalid tournament handicap formula',
                ['tournament_id' => $tournament->tournament_id]
            );
        }
    }


    public function calculateCourseHandicap($formula, $handicapIndex, $slopeRating, $courseRating, $par)
    {

        $validator = Validator::make([
            'formula' => $formula,
            'handicap_index' => $handicapIndex,
            'slope_rating' => $slopeRating,
            'course_rating' => $courseRating,
            'par' => $par,
        ], [
            'formula' => 'required|string',
            'handicap_index' => 'required|numeric',
            'slope_rating' => 'required|numeric:min:1',
            'course_rating' => 'required|numeric:min:1',
            'par' => 'required|integer',
        ]);


        if ($validator->fails()) {
            throw new \InvalidArgumentException("Invalid input data: " . implode(", ", $validator->errors()->all()));
        }


        $executor = new MathExecutor();


        $this->registerMathFunctions($executor);




        // Add variables to the executor
        $executor->setVar('HANDICAP_INDEX', (float) ($handicapIndex));
        $executor->setVar('SLOPE_RATING', (float) ($slopeRating));
        $executor->setVar('COURSE_RATING', (float) ($courseRating));
        $executor->setVar('PAR', (int) ($par));

        // Execute the formula
        return $executor->execute($formula);
    }


    private function getParticipantData($tournamentId)
    {

        $participants = Participant::with('user.profile', 'user.player', 'tournament', 'participantCourseHandicaps.course', 'participantCourseHandicaps.tee')
            ->leftJoin('users', 'participants.user_id', '=', 'users.id')
            ->leftJoin('tournaments', 'participants.tournament_id', '=', 'tournaments.tournament_id')
            ->leftJoin('player_profiles', 'participants.player_profile_id', '=', 'player_profiles.player_profile_id')
            ->leftJoin('whs_handicap_indexes', function ($join) {
                $join->on('participants.tournament_id', '=', 'whs_handicap_indexes.tournament_id')
                    ->on('tournaments.whs_handicap_import_id', '=', 'whs_handicap_indexes.whs_handicap_import_id')
                    ->on('player_profiles.whs_no', '=', 'whs_handicap_indexes.whs_no');
            })
            ->select('participants.*', 'users.*', 'tournaments.*', 'player_profiles.*', 'whs_handicap_indexes.whs_handicap_index', 'whs_handicap_indexes.final_whs_handicap_index')
            ->where('participants.tournament_id', $tournamentId)
            ->get();

        return $participants;
    }

    private function getRatingsAndCourseHandicapFormula($tournament)
    {


        $ratingsArr = [];
        $courseHandicapFormula = [];

        foreach ($tournament->tournamentCourses as $course) {
            foreach ($course->scorecard->ratings as $rating) {
                $ratingsArr[$course->course_id][$rating->tee_id] = [
                    'slope_rating' => $rating->slope_rating,
                    'course_rating' => $rating->course_rating,
                    'par' => $course->scorecard->scorecardHoles->sum('par')
                ];
            }

            $courseHandicapFormula[$course->course_id] = $course->scorecard->courseHandicapFormula->formula_expression;
        }


        return [
            'ratingsArr' => $ratingsArr,
            'courseHandicapFormula' => $courseHandicapFormula
        ];
    }


    private function registerMathFunctions(MathExecutor $executor)
    {

        $executor->addFunction('ROUND', fn($value, $precision = 0) => round($value, $precision));
        $executor->addFunction('MIN', fn(...$args) => min($args));
        $executor->addFunction('MAX', fn(...$args) => max($args));
        $executor->addFunction('CEIL', fn($value) => ceil($value));
        $executor->addFunction('FLOOR', fn($value) => floor($value));

        $executor->addFunction('AVG', function (...$args) {
            if (count($args) === 0) {
                throw new HandicapCalculationException("AVG requires at least one argument.");
            }
            return array_sum($args) / count($args);
        });
    }
}
