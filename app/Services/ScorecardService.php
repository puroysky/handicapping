<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use App\Models\Rating;
use App\Models\Scorecard;
use App\Models\ScorecardHole;
use App\Models\ScorecardYardage;
use App\Models\Tee;
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

class ScorecardService
{
    public function index()
    {

        $scorecards = Scorecard::get();

        return view('admin.scorecards.scorecards', compact('scorecards'));
    }

    public function store($validatedData)
    {
        DB::beginTransaction();
        try {
            // Create Scorecard
            $scorecard = $this->createScorecard($validatedData);

            $lastScorecardHoleId = $this->getLastScorecardHoleId();

            // Prepare Scorecard Holes Data
            $scorecardHolesData = $this->prepareScorecardHoles($scorecard->scorecard_id, $validatedData, $lastScorecardHoleId);

            // Insert Scorecard Holes
            ScorecardHole::insert($scorecardHolesData);


            //prepare and insert yardages
            $scorecardYardagesData = $this->prepareScorecardYardages($scorecard->scorecard_id, $validatedData, $lastScorecardHoleId);

            ScorecardYardage::insert($scorecardYardagesData);


            $scorecardRatingsData = $this->prepareScorecardRatings($scorecard->scorecard_id, $validatedData);

            Rating::insert($scorecardRatingsData);

            // echo '<pre>';
            // print_r($scorecardYardagesData);
            // echo '</pre>';
            DB::commit();

            return response()->json(['success' => false, 'message' => 'Scorecard created successfully.'], 500);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating scorecard: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create scorecard. ' . $e->getMessage()], 500);
        }
    }


    private function getLastScorecardHoleId(): int
    {
        $maxHoleId = ScorecardHole::max('scorecard_hole_id') ?? 0;
        return $maxHoleId;
    }
    private function createScorecard($validatedData): Scorecard
    {
        $scorecard = Scorecard::create([
            'scorecard_code' => $validatedData['scorecard_code'],
            'scorecard_name' => $validatedData['scorecard_name'],
            'scorecard_desc' => $validatedData['scorecard_desc'],
            'adjusted_gross_score_formula_id' => $validatedData['adjusted_gross_score_formula_id'],
            'score_differential_formula_id' => $validatedData['score_differential_formula_id'],
            'course_handicap_formula_id' => $validatedData['course_handicap_formula_id'],
            'course_id' => $validatedData['course_id'],
            'x_value' => $validatedData['x_value'],
            'active' => $validatedData['active'] ?? true,
            'created_by' => Auth::id(),
        ]);

        return $scorecard;
    }

    private function prepareScorecardYardages(?int $scorecardId, $validatedData, int $lastScorecardHoleId = 0): array
    {
        $scorecardYardagesData = [];

        $userId = Auth::id();

        foreach ($validatedData['yardages'] as $teeId => $holes) {

            $scorecardHoleIdCounter = $lastScorecardHoleId;

            foreach ($holes as $hole => $yardage) {
                $scorecardHoleIdCounter++;

                $scorecardYardagesData[] = [

                    'scorecard_id' => $scorecardId,
                    'scorecard_hole_id' => $scorecardHoleIdCounter,
                    'tee_id' => $teeId,
                    'yardage' => $yardage,
                    'created_by' => $userId,
                ];
            }
        }

        return $scorecardYardagesData;
    }

    /**
     * Prepare scorecard holes data for bulk insertion.
     *
     * @param int|null $scorecardId The scorecard ID
     * @param array $validatedData Form data containing par, male_handicap, and ladies_handicap
     * @param int $lastScorecardHoleId The last scorecard hole ID for sequential generation
     * @return array Array of prepared scorecard hole records
     */
    private function prepareScorecardHoles(?int $scorecardId, $validatedData, int $lastScorecardHoleId = 0): array
    {
        $scorecardHolesData = [];
        $holeIdCounter = $lastScorecardHoleId;
        $userId = Auth::id();

        foreach ($validatedData['par'] as $hole => $par) {
            $holeIdCounter++;

            $scorecardHolesData[] = [
                'scorecard_hole_id' => $holeIdCounter,
                'scorecard_id' => $scorecardId,
                'hole' => $hole,
                'par' => $par,
                'men_stroke_index' => $validatedData['male_handicap'][$hole],
                'ladies_stroke_index' => $validatedData['ladies_handicap'][$hole],
                'created_by' => $userId,
            ];
        }

        return $scorecardHolesData;
    }

    private function prepareScorecardRatings($scorecardId, $validatedData)
    {

        $tees = Tee::where('course_id', $validatedData['course_id'])->pluck('tee_id')->toArray();


        $scorecardRatingsData = [];
        $userId = Auth::id();

        foreach ($tees as $teeId) {
            $scorecardRatingsData[] = [
                'scorecard_id' => $scorecardId,
                'tee_id' => $teeId,
                'slope_rating' => $validatedData['slope_rating'][$teeId],
                'f9_slope_rating' => $validatedData['front_nine_slope_rating'][$teeId],
                'b9_slope_rating' => $validatedData['back_nine_slope_rating'][$teeId],
                'course_rating' => $validatedData['course_rating'][$teeId],
                'f9_course_rating' => $validatedData['front_nine_course_rating'][$teeId],
                'b9_course_rating' => $validatedData['back_nine_course_rating'][$teeId],
                'created_by' => $userId,
            ];
        }

        return $scorecardRatingsData;
    }
}
