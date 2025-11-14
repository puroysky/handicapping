<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\ParticipantCourse;
use App\Models\ParticipantCourseHandicap;
use App\Models\PlayerProfile;
use App\Models\Tournament;
use App\Models\Participat;
use App\Models\WhsHandicapIndex;
use App\Services\ParticipantImportService;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NXP\MathExecutor;

class ParticipantController extends Controller
{



    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $players = PlayerProfile::with('userProfile')->get();
        $tournament = Tournament::with('tournamentCourses.course.tees')->find($request->id); // Example to get a tournament


        // echo '<pre>';
        // print_r($tournament->toArray());
        // echo '</pre>';
        // return;

        return view('admin.tournaments.create-participant-form', compact('tournament', 'players'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tournament = Tournament::with('tournamentCourses')->find($id);
        $players = Participant::with('user.profile', 'user.player', 'tournament', 'participantCourseHandicaps.course', 'participantCourseHandicaps.tee')
            ->leftJoin('users', 'participants.user_id', '=', 'users.id')
            ->leftJoin('tournaments', 'participants.tournament_id', '=', 'tournaments.tournament_id')
            ->leftJoin('player_profiles', 'participants.player_profile_id', '=', 'player_profiles.player_profile_id')
            ->leftJoin('whs_handicap_indexes', function ($join) {
                $join->on('participants.tournament_id', '=', 'whs_handicap_indexes.tournament_id')
                    ->on('tournaments.whs_handicap_import_id', '=', 'whs_handicap_indexes.whs_handicap_import_id')
                    ->on('player_profiles.whs_no', '=', 'whs_handicap_indexes.whs_no');
            })
            ->select('participants.*', 'users.*', 'tournaments.*', 'player_profiles.*', 'whs_handicap_indexes.whs_handicap_index', 'whs_handicap_indexes.final_whs_handicap_index')
            ->where('participants.tournament_id', $id)
            // ->where('participants.participant_id', 10)
            ->get();
        // echo '<pre>';
        // print_r($players->toArray());
        // return;
        return view('admin.tournaments.participants', ['players' => $players, 'tournamentId' => $id, 'tournament' => $tournament]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function import(Request $request)
    {

        $importService = new ParticipantImportService();
        return $importService->import($request);
    }

    /**
     * Get available players (not yet in tournament)
     */
    public function available(Request $request)
    {
        $tournamentId = $request->query('tournament_id');

        // Get all players not already in tournament
        $query = PlayerProfile::with('user.profile')
            ->where('active', true);

        if ($tournamentId) {
            $query->whereNotIn('player_profile_id', function ($q) use ($tournamentId) {
                $q->select('player_profile_id')
                    ->from('participants')
                    ->where('tournament_id', $tournamentId);
            });
        }

        $players = $query->get()->map(function ($player) {
            return [
                'participant_id' => $player->player_profile_id,
                'first_name' => $player->user->profile->first_name ?? '',
                'last_name' => $player->user->profile->last_name ?? '',
                'account_no' => $player->account_no ?? 'N/A'
            ];
        });

        return response()->json([
            'success' => true,
            'players' => $players
        ]);
    }

    /**
     * Bulk add participants to tournament
     */
    public function addBulk(Request $request)
    {


        try {
            $validated = $request->validate([
                'tournament_id' => 'required|exists:tournaments,tournament_id',
                'participant_ids' => 'required|array|min:1',
                'participant_ids.*' => 'exists:player_profiles,player_profile_id'
            ]);

            $tournamentId = $validated['tournament_id'];
            $participantIds = $validated['participant_ids'];
            $tournament = Tournament::find($tournamentId);
            $errors = [];
            $added = 0;

            DB::beginTransaction();

            $existingParticipants = Participant::with('user.profile')->where('tournament_id', $tournamentId)
                ->whereIn('player_profile_id', $participantIds)
                ->get()
                ->keyBy('player_profile_id');



            $participantData = [];

            foreach ($participantIds as $playerProfileId) {
                try {


                    if (isset($existingParticipants[$playerProfileId])) {
                        $errors[] = "Player " . ($existingParticipants[$playerProfileId]->user->profile->first_name ?? '') . " " . ($existingParticipants[$playerProfileId]->user->profile->last_name ?? '') . " already added to this tournament";
                        continue;
                    }

                    $playerProfile = PlayerProfile::find($playerProfileId);


                    $now = now();
                    $participantData[] = [
                        'tournament_id' => $tournamentId,
                        'player_profile_id' => $playerProfileId,
                        'user_id' => $playerProfile->user_id,
                        'created_by' => Auth::id(),
                        'created_at' => $now,
                        'updated_at' => $now
                    ];





                    // Add default course handicaps for tournament courses
                    $tournamentCourses = $tournament->tournamentCourses;
                    // foreach ($tournamentCourses as $tc) {
                    //     if ($tc->tee) {
                    //         $participant->participantCourseHandicaps()->create([
                    //             'tournament_id' => $tournamentId,
                    //             'course_id' => $tc->course_id,
                    //             'tee_id' => $tc->tee_id,
                    //             'created_by' => Auth::id() ?? 1
                    //         ]);
                    //     }
                    // }

                    $added++;
                } catch (Exception $e) {
                    Log::error('Error adding participant', ['error' => $e->getMessage()]);
                    $errors[] = "Error adding player: " . $e->getMessage();
                }
            }


            Participant::insert($participantData);

            DB::commit();

            return response()->json([
                'success' => $added > 0,
                'message' => $added > 0 ? "$added player(s) added successfully" : "Failed to add players",
                'added' => $added,
                'errors' => $errors
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Bulk add participants error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'errors' => []
            ], 422);
        }
    }

    public function calculateHandicap(Request $request)
    {

        $validatedData = $request->validate([
            'tournament_id' => 'required|integer',
            'type' => 'required|in:tournament,local',
        ]);




        $tournamentId = $validatedData['tournament_id'];
        $type = $validatedData['type'];


        $tournament = Tournament::find($tournamentId);


        switch ($type) {
            case 'tournament':
                $handicap = $this->calculateTournamentHandicap($tournament);
                break;
            case 'local':
                // Example adjustment for local handicap

                break;
            default:
                throw new Exception('Invalid handicap type specified.');
        }


        return $handicap;
    }

    private function calculateTournamentHandicap($tournament)
    {
        try {
            DB::beginTransaction();

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
                ->where('participants.tournament_id', $tournament->tournament_id)
                ->get();

            if ($participants->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No participants found for this tournament',
                    'updated_count' => 0
                ]);
            }



            $courseHandicapFormula = [];


            foreach ($tournament->tournamentCourses as $course) {
                $courseHandicapFormula[$course->course_id] = $course->scorecard->courseHandicapFormula->formula_expression;
            }


            Log::debug('Course Handicap Formula', ['formula' => $courseHandicapFormula]);



            $executor = new MathExecutor();

            // Register custom math helpers
            $executor->addFunction('ROUND', fn($value, $precision = 0) => round($value, $precision));
            $executor->addFunction('MIN', fn(...$args) => min($args));
            $executor->addFunction('MAX', fn(...$args) => max($args));
            $executor->addFunction('AVG', fn(...$args) => array_sum($args) / count($args));

            $updatedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($participants as $participant) {

                $whsHandicapIndex = $participant->final_whs_handicap_index;
                $localHandicapIndex = $participant->final_local_handicap_index;
                $expression = null;

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
                    $skippedCount++;
                    Log::warning('No formula available for participant', [
                        'participant_id' => $participant->participant_id,
                        'whs_handicap_index' => $whsHandicapIndex,
                        'local_handicap_index' => $localHandicapIndex
                    ]);
                    continue;
                }

                $executor->setVar('WHS_HANDICAP_INDEX', (float) ($whsHandicapIndex ?? 0));
                $executor->setVar('LOCAL_HANDICAP_INDEX', (float) ($localHandicapIndex ?? 0));

                // Execute the formula
                $result = $executor->execute($expression);

                // Update participant with calculated handicap
                // Log the SQL query before executing
                DB::enableQueryLog();

                Participant::where($participant->particpant_id)->update([
                    'final_tournament_handicap_index' => $result,
                    'updated_by' => Auth::id()
                ]);

                // Log the executed query
                $queries = DB::getQueryLog();
                Log::info('Participant update query', [
                    'participant_id' => $participant->participant_id,
                    'query' => end($queries)
                ]);

                $updatedCount++;

                Log::info('Calculated tournament handicap', [
                    'participant_id' => $participant->participant_id,
                    'whs_handicap_index' => (float) ($whsHandicapIndex ?? 0),
                    'local_handicap_index' => (float) ($localHandicapIndex ?? 0),
                    'formula' => $expression,
                    'result' => $result
                ]);





                ParticipantCourse::where('participant_id', $participant->participant_id)
                    ->where('tournament_id', $tournament->tournament_id)
                    ->get()
                    ->each(function ($participantCourse) use ($executor, $courseHandicapFormula, $result, $whsHandicapIndex, $localHandicapIndex) {
                        $courseId = $participantCourse->course_id;
                        if (isset($courseHandicapFormula[$courseId])) {
                            $formula = $courseHandicapFormula[$courseId];

                            $executor->setVar('HANDICAP_INDEX', (float) ($result ?? 0));
                            $executor->setVar('SLOPE_RATING', (float) ($localHandicapIndex ?? 0));
                            $executor->setVar('COURSE_RATING', (float) ($localHandicapIndex ?? 0));
                            $executor->setVar('PAR', (float) ($localHandicapIndex ?? 0));

                            $courseHandicap = $executor->execute($formula);

                            $participantCourse->update([
                                'course_handicap' => $courseHandicap,
                                'updated_by' => Auth::id()
                            ]);

                            Log::info('Calculated course handicap', [
                                'participant_course_id' => $participantCourse->participant_course_id,
                                'course_id' => $courseId,
                                'formula' => $formula,
                                'course_handicap' => $courseHandicap
                            ]);
                        }
                    });
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
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateLocalHandicap($whsHandicapIndex, $slopeRating)
    {
        // WHS to Local Handicap conversion formula
        return ($whsHandicapIndex * $slopeRating) / 113;
    }

    /**
     * Handle course selection for a participant
     */
    public function setCourseSelection(Request $request)
    {
        try {
            $validated = $request->validate([
                'participant_id' => 'required|integer',
                'course_id' => 'required|integer',
                'action' => 'required|in:check,uncheck'
            ]);



            $participantId = $validated['participant_id'];
            $courseId = $validated['course_id'];
            $action = $validated['action'];

            $participant = Participant::with('tournament')->findOrFail($participantId);

            $courseTees = $participant->tournament->tournamentCourses->where('course_id', $courseId)->first()->course->tees;







            $exists = ParticipantCourse::where('participant_id', $participantId)
                ->where('course_id', $courseId)
                ->where('tournament_id', $participant->tournament_id)
                ->first();


            if ($action === 'check') {
                if (!$exists) {
                    ParticipantCourse::create([
                        'participant_id' => $participantId,
                        'course_id' => $courseId,
                        'tournament_id' => $participant->tournament_id,
                        'created_by' => Auth::id()
                    ]);


                    // $table->unsignedBigInteger('tournament_id');
                    // $table->unsignedBigInteger('participant_id');
                    // $table->unsignedBigInteger('course_id');
                    // $table->unsignedBigInteger('tee_id');

                    // $table->decimal('course_handicap', 4, 2)->nullable()->default(null);
                    // $table->decimal('final_course_handicap', 4, 2)->nullable()->default(null);





                    foreach ($courseTees as $tee) {

                        Log::info('Adding ParticipantCourseHandicap', [
                            'participant_id' => $participantId,
                            'course_id' => $courseId,
                            'tournament_id' => $participant->tournament_id,
                            'tee_id' => $tee->tee_id,
                        ]);









                        ParticipantCourseHandicap::create([
                            'participant_id' => $participantId,
                            'course_id' => $courseId,
                            'tournament_id' => $participant->tournament_id,
                            'tee_id' => $tee->tee_id,
                            'created_by' => Auth::id()
                        ]);
                    }
                }
            } else {
                if ($exists) {
                    $exists = ParticipantCourse::where('participant_id', $participantId)
                        ->where('course_id', $courseId)
                        ->where('tournament_id', $participant->tournament_id)
                        ->delete();
                }
            }





            // Log the course selection event
            Log::info('Course selection updated', [
                'participant_id' => $participantId,
                'course_id' => $courseId,
                'action' => $action,
                'user_id' => Auth::id() ?? 'unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => "Course $action event recorded successfully",
                'data' => [
                    'participant_id' => $participantId,
                    'course_id' => $courseId,
                    'action' => $action
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error updating course selection', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Update handicap index for a participant
     */
    public function updateHandicap(Request $request)
    {
        try {
            $validated = $request->validate([
                'participant_id' => 'required|integer|exists:participants,participant_id',
                'type' => 'required|in:local,tournament,whs',
                'value' => 'required|numeric|min:0|max:54'
            ]);

            $participantId = $validated['participant_id'];
            $type = $validated['type'];
            $value = $validated['value'];

            // Find the participant
            $participant = Participant::with('playerProfile')->findOrFail($participantId);
            $tournament = Tournament::find($participant->tournament_id);



            // $table->unique(['tournament_id', 'whs_handicap_import_id', 'whs_no'], 'tournament_whs_handicap_unique');



            $whsHandicapIndex = WhsHandicapIndex::where('tournament_id', $tournament->tournament_id)
                ->where('whs_handicap_import_id', $tournament->whs_handicap_import_id)
                ->where('whs_no', $participant->playerProfile->whs_no)
                ->first();

            // Update based on type
            switch ($type) {
                case 'local':
                    $participant->final_local_handicap_index = $value;
                    break;
                case 'tournament':
                    $participant->final_tournament_handicap_index = $value;
                    break;
                case 'whs':
                    $whsHandicapIndex->final_whs_handicap_index = $value;
                    break;
            }

            $participant->updated_by = Auth::id();
            $whsHandicapIndex->updated_by = Auth::id();

            $participant->save();
            $whsHandicapIndex->save();

            // Log the update
            Log::info('Handicap index updated', [
                'participant_id' => $participantId,
                'type' => $type,
                'value' => $value,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . " handicap index updated successfully",
                'data' => [
                    'participant_id' => $participantId,
                    'type' => $type,
                    'value' => $value
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error updating handicap index', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
