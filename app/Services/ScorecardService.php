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
        try {
            DB::beginTransaction();

            $scorecard = $this->createScorecard($validatedData);
            $nextHoleId = $this->getNextScorecardHoleId();

            $this->insertScorecardData($scorecard->scorecard_id, $validatedData, $nextHoleId);

            DB::commit();

            Log::info('Scorecard created successfully', [
                'scorecard_id' => $scorecard->scorecard_id,
                'scorecard_code' => $scorecard->scorecard_code,
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Scorecard created successfully.',
                'data' => $scorecard,
                'redirect' => route('admin.scorecards.show', $scorecard->scorecard_id)
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating scorecard: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create scorecard. ' . $e->getMessage()
            ], 500);
        }
    }


    private function getNextScorecardHoleId(): int
    {
        return ScorecardHole::max('scorecard_hole_id') ?? 0;
    }

    private function insertScorecardData(int $scorecardId, $validatedData, int $nextHoleId): void
    {
        $holesData = $this->prepareScorecardHoles($scorecardId, $validatedData, $nextHoleId);
        $yardagesData = $this->prepareScorecardYardages($scorecardId, $validatedData, $nextHoleId);
        $ratingsData = $this->prepareScorecardRatings($scorecardId, $validatedData);

        ScorecardHole::insert($holesData);
        ScorecardYardage::insert($yardagesData);
        Rating::insert($ratingsData);
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

    private function prepareScorecardYardages(int $scorecardId, $validatedData, int $nextHoleId): array
    {
        $yardagesData = [];
        $currentHoleId = $nextHoleId;
        $userId = Auth::id();

        foreach ($validatedData['yardages'] as $teeId => $holes) {

            $currentHoleId = $nextHoleId;

            foreach ($holes as $hole => $yardage) {
                $currentHoleId++;

                $yardagesData[] = [
                    'scorecard_id' => $scorecardId,
                    'scorecard_hole_id' => $currentHoleId,
                    'tee_id' => $teeId,
                    'yardage' => $yardage,
                    'created_by' => $userId,
                ];
            }
        }

        return $yardagesData;
    }

    /**
     * Prepare scorecard holes data for bulk insertion.
     */
    private function prepareScorecardHoles(int $scorecardId, $validatedData, int $nextHoleId): array
    {
        $holesData = [];
        $currentHoleId = $nextHoleId;
        $userId = Auth::id();

        foreach ($validatedData['par'] as $hole => $par) {
            $currentHoleId++;

            $holesData[] = [
                'scorecard_hole_id' => $currentHoleId,
                'scorecard_id' => $scorecardId,
                'hole' => $hole,
                'par' => $par,
                'men_stroke_index' => $validatedData['male_handicap'][$hole],
                'ladies_stroke_index' => $validatedData['ladies_handicap'][$hole],
                'created_by' => $userId,
            ];
        }

        return $holesData;
    }

    private function prepareScorecardRatings(int $scorecardId, $validatedData): array
    {
        $teeIds = Tee::where('course_id', $validatedData['course_id'])
            ->pluck('tee_id')
            ->toArray();

        $ratingsData = [];
        $userId = Auth::id();

        foreach ($teeIds as $teeId) {
            $ratingsData[] = [
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

        return $ratingsData;
    }
}
