<?php

namespace App\Services;

use App\Models\PlayerProfile;
use App\Models\Score;
use App\Models\Scorecard;
use App\Models\ScoreHole;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use NXP\MathExecutor;

class ScoreService
{
    // How many strokes to add when input is 'x'
    private int $xStrokePenalty;

    public function __construct(int $xStrokePenalty = 2)
    {
        $this->xStrokePenalty = $xStrokePenalty;
    }


    public function index()
    {

        $scores = Score::with(['playerProfile', 'userProfile', 'scoreHoles', 'tournament', 'tournamentCourse.course'])->orderBy('created_at', 'desc')->get();
        $title = 'Scores';


        // echo '<pre>';
        // print_r($scores->toArray());
        // echo '</pre>';
        // return;
        return view('admin.scores.scores', compact('scores', 'title'));
    }

    public function getTees($request, $courseId) {}


    /**
     * Store a new score with hole-by-hole details
     */
    public function store($request)
    {
        // Store request to file for debugging
        $this->storeRequestToFile($request);

        try {
            DB::beginTransaction();

            $scores = $request['scores'] ?? [];
            $side = $this->determineSide($scores);
            $totalStrokes = $this->calculateTotalStrokes($scores);

            $score = $this->createScore($request, $side, $totalStrokes);


            Log::debug('Score created', ['score_id' => $score->score_id]);
            $this->createScoreHoles($score->score_id, $scores);


            $formattedScore = $this->formatScoreInput(1, 'M', 1, $scores);

            $scoreBreakdown = $this->getScoreBreakdown($formattedScore, 10);


            return '<pre>' . print_r($scoreBreakdown, true) . '</pre>';

            // return '<pre>' . print_r($this->formatScoreInput(1, 'M', 1, $scores), true) . '</pre>';





            // DB::commit();



            return response()->json([
                'success' => true,
                'message' => 'Score created successfully',
                'score_id' => $score->score_id,
                'redirect' => route('admin.scores.index')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();



            return response()->json([
                'message' => 'Failed to create score',
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /* Calculate the course handicap using handicap index, slope rating, and course rating.
     *
     * Formula: Course Handicap = Handicap Index × (Slope Rating ÷ 113)
     * (Course rating is typically used in handicap index calculation, not directly here)
     *
     * @param float $handicapIndex Player's handicap index
     * @param int $slopeRating Course slope rating
     * @param float $courseRating Course rating (for reference, not used in calculation)
     * @return float Calculated course handicap, rounded to nearest whole number
     */
    public function calculateCourseHandicap(float $handicapIndex, int $slopeRating, float $courseRating, int $par, array $variables): float
    {


        $params = [
            'HANDICAP_INDEX' => $handicapIndex,
            'SLOPE_RATING' => $slopeRating,
            'COURSE_RATING' => $courseRating,
            'PAR' => $par,
        ];

        $executor = new MathExecutor();

        foreach ($params as $key => $value) {
            $executor->setVar($key, $value);
        }

        // Standard USGA formula for course handicap
        $courseHandicap = $executor->execute('HANDICAP_INDEX * (SLOPE_RATING / 113) + (COURSE_RATING - PAR)');


        return round($courseHandicap);
    }

    /**
     * Format scorecard input data for a given scorecard, gender, and tee.
     *
     * @param int $scorecardId
     * @param string $gender
     * @param int $teeId
     * @param array $score Array of scores [hole => ['gross_strokes' => int, ...]]
     * @return array|null Returns array keyed by hole number with par, stroke index, yardage, and gross strokes, or null if not found.
     */
    private function formatScoreInput(int $scorecardId, string $gender, int $teeId, array $score): ?array
    {
        // Validate input parameters
        if (empty($scorecardId) || empty($gender) || empty($teeId)) {
            Log::warning('formatScoreInput: Missing required parameters', compact('scorecardId', 'gender', 'teeId'));
            return null;
        }

        $scoreCard = Scorecard::where('scorecard_id', $scorecardId)
            ->with([
                'scorecardHoles:scorecard_id,scorecard_hole_id,hole,par',
                'scorecardHoles.strokeIndex' => function ($query) use ($gender) {
                    $query->select('scorecard_stroke_index_id', 'scorecard_hole_id', 'stroke_index')
                        ->where('gender', $gender);
                },
                'scorecardHoles.yardage' => function ($query) use ($teeId) {
                    $query->select('scorecard_yard_id', 'scorecard_hole_id', 'yardage')
                        ->where('tee_id', $teeId);
                },
            ])->first();

        if (!$scoreCard) {
            Log::warning('formatScoreInput: Scorecard not found', ['scorecard_id' => $scorecardId]);
            return null;
        }

        if (!$scoreCard->scorecardHoles || $scoreCard->scorecardHoles->isEmpty()) {
            Log::warning('formatScoreInput: No holes found for scorecard', ['scorecard_id' => $scorecardId]);
            return null;
        }

        // Use Laravel Collection for more idiomatic processing, keyed by hole number
        return $scoreCard->scorecardHoles
            ->mapWithKeys(function ($hole) use ($score) {
                return [
                    $hole->hole => [
                        'par' => $hole->par,
                        'stroke_index' => $hole->strokeIndex?->stroke_index,
                        'yardage' => $hole->yardage?->yardage,
                        'gross_strokes' => $score[$hole->hole]['gross_strokes'] ?? null,
                    ]
                ];
            })
            ->sortKeys()
            ->toArray();
    }

    /**
     * Determine which side(s) were played based on hole numbers
     */
    private function determineSide(array $scores): string
    {
        if (empty($scores)) {
            return 'both';
        }

        $holeNumbers = array_keys($scores);
        $hasFront = !empty(array_filter($holeNumbers, fn($h) => $h >= 1 && $h <= 9));
        $hasBack = !empty(array_filter($holeNumbers, fn($h) => $h >= 10 && $h <= 18));

        if ($hasFront && !$hasBack) {
            return 'front';
        }

        if ($hasBack && !$hasFront) {
            return 'back';
        }

        return 'both';
    }

    /**
     * Calculate total strokes from scores array
     */
    private function calculateTotalStrokes(array $scores): int
    {
        return array_sum(array_column($scores, 'gross_strokes'));
    }

    /**
     * Create the main Score record
     */
    private function createScore($request, string $side, int $totalStrokes): Score
    {
        $userId = Auth::id();
        $now = now();

        $player = PlayerProfile::findOrFail($request['player_profile_id']);

        Log::debug('Player found', ['player_profile_id' => $player->player_profile_id, 'user_id' => $player->user_id, 'user_profile_id' => $player->user_profile_id]);

        return Score::create([
            'player_profile_id' => $request['player_profile_id'],
            'user_profile_id' => $player->user_profile_id,
            'user_id' => $player->user_id,
            'tournament_id' => $request['tournament_id'],
            'tournament_course_id' => $request['tournament_course_id'],
            'tee_id' => $request['tee_id'],
            'scoring_method' => $request['scoring_method'],
            'score_date' => $request['score_date'],
            'entry_type' => 'manual',
            'side' => $side,
            'gross_score' =>  $request['scoring_method'] === 'hole_by_hole' ? $totalStrokes : null,
            'adjusted_score' => $request['scoring_method'] === 'adjusted_score' ? $request['adjusted_score'] : $totalStrokes,
            'net_score' => $totalStrokes,
            'is_verified' => true,
            'verified_by' => $userId,
            'verified_at' => $now,
            'remarks' => $request['remarks'] ?? null,
            'created_by' => $userId,
            'created_at' => $now,
        ]);
    }

    /**
     * Create ScoreHole records for each hole
     */
    private function createScoreHoles(int $scoreId, array $scores): void
    {


        Log::debug('Creating score holes', ['scoring_method' => request('scoring_method'), 'scores_count' => count($scores)]);
        if (request('scoring_method') === 'adjusted_score') {

            Log::debug('No score holes to create for adjusted_score method');
            return;
        }


        if (request('scoring_method') === 'hole_by_hole' && empty($scores)) {

            throw new \InvalidArgumentException('Scores data is required for hole_by_hole scoring method.');
        }

        $userId = Auth::id();
        $now = now();
        $scoreHoles = [];

        foreach ($scores as $holeNumber => $holeData) {
            $scoreHoles[] = [
                'score_id' => $scoreId,
                'hole' => $holeNumber,
                'side' => ($holeNumber <= 9) ? 'front' : 'back',
                'strokes' => $holeData['gross_strokes'],
                'raw_input' => $holeData['raw_input'],
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Log::debug('Inserting score holes', ['count' => count($scoreHoles)]);


        $inserted = ScoreHole::insert($scoreHoles);
        Log::debug('Score holes inserted', ['inserted' => $inserted]);
    }


    /**
     * Calculate net score for a single hole (simple version)
     */
    private function calculateNetScore(int $grossScore, int $handicapStroke): int
    {
        return max(0, $grossScore - $handicapStroke);
    }

    /**
     * Store request data to a file for debugging
     */
    private function storeRequestToFile($request): void
    {
        try {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "score_request_{$timestamp}.json";

            $requestData = [
                'timestamp' => now()->toDateTimeString(),
                'user_id' => Auth::id(),
                'request_data' => is_array($request) ? $request : $request->all(),
            ];

            $jsonContent = json_encode($requestData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            // Store in storage/app/score_requests directory
            Storage::disk('local')->put("score_requests/{$fileName}", $jsonContent);

            Log::info("Request stored to file: {$fileName}");
        } catch (\Exception $e) {
            // Don't let file storage errors break the main process
            Log::warning("Failed to store request to file: " . $e->getMessage());
        }
    }

    /**
     * Calculate net score for a player based on their scores and course handicap
     * 
     * @param array $scores Array of hole scores [hole => strokes]
     * @param int $courseHandicap Player's course handicap
     * @param array $strokeIndex Stroke index for each hole [hole => index]
     * @param array $parValues Par values for each hole [hole => par]
     * @param bool $detailed Return detailed hole-by-hole breakdown
     * @return array|int Net score total or detailed breakdown
     */
    public function calculatePlayerNetScore(array $scores, int $courseHandicap, array $strokeIndex, array $parValues = [], bool $detailed = false)
    {
        $totalNetScore = 0;
        $totalGrossScore = array_sum($scores);
        $totalAdjustedGrossScore = 0;
        $holeDetails = [];

        foreach ($scores as $hole => $strokes) {
            // Ensure hole number is integer
            $holeNumber = (int) $hole;

            // Skip if hole number is invalid
            if ($holeNumber < 1 || $holeNumber > 18) {
                continue;
            }

            // Skip if stroke index not available for this hole
            if (!isset($strokeIndex[$holeNumber])) {
                Log::warning("Stroke index not found for hole {$holeNumber}");
                continue;
            }

            // Get par value for this hole (default to 4 if not provided)
            $par = $parValues[$holeNumber] ?? 4;

            // Calculate handicap strokes for this hole
            $handicapStrokes = $this->calculateHandicapStrokesForHole($courseHandicap, $strokeIndex[$holeNumber]);

            // Calculate adjusted gross strokes (ESC - Equitable Stroke Control)
            $adjustedGrossStrokes = $this->calculateAdjustedGrossStroke($strokes, $par, $handicapStrokes, $courseHandicap);

            // Calculate net strokes (adjusted gross strokes minus handicap strokes)
            $netStrokes = max(0, $adjustedGrossStrokes - $handicapStrokes); // Ensure non-negative

            $totalNetScore += $netStrokes;
            $totalAdjustedGrossScore += $adjustedGrossStrokes;

            if ($detailed) {
                $holeDetails[$holeNumber] = [
                    'gross_strokes' => $strokes,
                    'adjusted_gross_strokes' => $adjustedGrossStrokes,
                    'handicap_strokes' => $handicapStrokes,
                    'net_strokes' => $netStrokes,
                    'par' => $par,
                    'stroke_index' => $strokeIndex[$holeNumber],
                    'max_allowed_strokes' => $this->getMaxAllowedStrokes($par, $handicapStrokes, $courseHandicap)
                ];
            }
        }

        Log::debug('Net score calculated', [
            'total_net_score' => $totalNetScore,
            'total_gross_score' => $totalGrossScore,
            'total_adjusted_gross_score' => $totalAdjustedGrossScore,
            'course_handicap' => $courseHandicap,
            'holes_played' => count($scores)
        ]);

        return $detailed ? [
            'total_net_score' => $totalNetScore,
            'total_gross_score' => $totalGrossScore,
            'total_adjusted_gross_score' => $totalAdjustedGrossScore,
            'hole_details' => $holeDetails,
        ] : $totalNetScore;
    }

    /**
     * Calculate handicap strokes for a specific hole
     * 
     * @param int $courseHandicap Player's course handicap
     * @param int $strokeIndex Stroke index of the hole
     * @return int Number of handicap strokes for this hole
     */
    private function calculateHandicapStrokesForHole(int $courseHandicap, int $strokeIndex): int
    {
        // Basic handicap allocation: if course handicap >= stroke index, player gets 1 stroke
        // For handicaps > 18, players get additional strokes on easier holes

        if ($courseHandicap <= 0) {
            return 0;
        }

        $strokes = 0;

        // First stroke allocation (handicap 1-18)
        if ($courseHandicap >= $strokeIndex) {
            $strokes++;
        }

        // Second stroke allocation (handicap 19-36)
        if ($courseHandicap >= ($strokeIndex + 18)) {
            $strokes++;
        }

        // Third stroke allocation (handicap 37-54) - rare but possible
        if ($courseHandicap >= ($strokeIndex + 36)) {
            $strokes++;
        }

        return $strokes;
    }

    /**
     * Calculate adjusted gross stroke for a hole using Equitable Stroke Control (ESC)
     * 
     * @param int $actualStrokes Actual strokes taken on the hole
     * @param int $par Par value for the hole
     * @param int $handicapStrokes Number of handicap strokes for this hole
     * @param int $courseHandicap Player's total course handicap
     * @return int Adjusted gross strokes (capped at maximum allowed)
     */
    private function calculateAdjustedGrossStroke(int $actualStrokes, int $par, int $handicapStrokes, int $courseHandicap): int
    {
        $maxAllowedStrokes = $this->getMaxAllowedStrokes($par, $handicapStrokes, $courseHandicap);
        return min($actualStrokes, $maxAllowedStrokes);
    }

    /**
     * Get maximum allowed strokes for a hole based on mathematical expression
     * Formula: Par + Handicap Strokes + Double Bogey Constant (2)
     * 
     * @param int $par Par value for the hole
     * @param int $handicapStrokes Number of handicap strokes for this hole
     * @param int $courseHandicap Player's total course handicap (unused in pure mathematical approach)
     * @return int Maximum allowed strokes
     */
    private function getMaxAllowedStrokes(int $par, int $handicapStrokes, int $courseHandicap): int
    {


        $executor = new MathExecutor();
        $expresson = [
            'PAR' => $par,
            'HANDICAP_STROKES' => $handicapStrokes,
            'DOUBLE_BOGEY_LIMIT' => 2,
        ];

        foreach ($expresson as $var => $value) {
            $executor->setVar($var, $value);
        }
        //PAR+HANDICAP_STROKES+DOUBLE_BOGEY_LIMIT
        $result = $executor->execute('PAR + HANDICAP_STROKES + DOUBLE_BOGEY_LIMIT');

        // Pure mathematical expression: Par + Handicap Strokes + Double Bogey Constant
        $doubleBogeyConstant = 2;
        return $result;
    }

    /**
     * Get a detailed score breakdown for a test round (demo method)
     *
     * @param array $scoreArr Array of hole data [hole => ['par' => int, 'stroke_index' => int, 'yardage' => int, 'gross_strokes' => int]]
     * @param int $courseHandicap Player's course handicap
     * @return array Detailed score breakdown for the provided round
     */
    private function getScoreBreakdown(array $scoreArr, int $courseHandicap): array
    {
        // Extract data from scoreArr
        $scores = array_column($scoreArr, 'gross_strokes');
        $strokeIndex = array_column($scoreArr, 'stroke_index');
        $parValues = array_column($scoreArr, 'par');

        return $this->calculatePlayerNetScore($scores, $courseHandicap, $strokeIndex, $parValues, true);
    }
}
