<?php

namespace App\Services;

use App\Models\Participant;
use App\Models\PlayerProfile;
use App\Models\Score;
use App\Models\Scorecard;
use App\Models\ScoreHole;
use App\Models\Tournament;
use App\Models\TournamentCourse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use NXP\MathExecutor;

class ScoreService
{
    // How many strokes to add when input is 'x'
    private int $xStrokePenalty;

    private Tournament $tournament;
    private TournamentCourse $tournamentCourse;
    private PlayerProfile $playerProfile;
    private Participant $participant;

    public function __construct(int $xStrokePenalty = 2)
    {
        $this->xStrokePenalty = $xStrokePenalty;
    }


    public function index()
    {

        $scores = Score::with(['playerProfile.userProfile', 'tournament', 'tournamentCourse.course', 'tee'])
            ->orderBy('created_at', 'desc')
            ->get();
        $title = 'Scores';


        // echo '<pre>';
        // print_r($scores->toArray());
        // echo '</pre>';
        // return;
        return view('admin.scores.scores', compact('scores', 'title'));
    }

    public function filter($request)
    {
        $filters = $request->input('filters', []);

        $query = Score::with(['playerProfile.userProfile', 'tournament', 'tournamentCourse.course', 'tee']);

        foreach ($filters as $filter) {
            $field = $filter['field'];
            $value = $filter['value'];
            $type = $filter['type'];

            switch ($field) {
                case 'player_name':
                    $query->whereHas('playerProfile.userProfile', function ($q) use ($value) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$value}%");
                    });
                    break;

                case 'whs_no':
                    $query->whereHas('playerProfile', function ($q) use ($value) {
                        $q->where('whs_handicap_no', 'LIKE', "%{$value}%");
                    });
                    break;

                case 'account_no':
                    $query->whereHas('playerProfile', function ($q) use ($value) {
                        $q->where('account_no', 'LIKE', "%{$value}%");
                    });
                    break;

                case 'tournament':
                    $query->whereHas('tournament', function ($q) use ($value) {
                        $q->where('tournament_name', 'LIKE', "%{$value}%");
                    });
                    break;

                case 'course':
                    $query->whereHas('tournamentCourse.course', function ($q) use ($value) {
                        $q->where('course_code', 'LIKE', "%{$value}%");
                    });
                    break;

                case 'tee':
                    $query->whereHas('tee', function ($q) use ($value) {
                        $q->where('tee_code', 'LIKE', "%{$value}%");
                    });
                    break;

                case 'adjusted_score':
                    if ($type === 'number') {
                        $query->where('adjusted_score', $value);
                    }
                    break;

                case 'date_played':
                    if ($type === 'date') {
                        $query->whereDate('played_date', $value);
                    }
                    break;
            }
        }

        $scores = $query->orderBy('created_at', 'desc')->get();

        // Generate HTML for filtered rows
        $html = view('admin.scores.partials.scores-table-rows', compact('scores'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $scores->count(),
            'total' => Score::count()
        ]);
    }

    public function show($id)
    {

        $score = Score::where('score_id', $id)
            ->with(['playerProfile', 'userProfile', 'scoreHoles', 'tournament', 'tournamentCourse.course'])
            ->firstOrFail();
    }

    public function getTees($request, $courseId) {}


    /**
     * Store a new score with hole-by-hole details
     */
    public function store($request)
    {

        try {

            $this->storeRequestToFile($request);
            $this->loadScoreData($request);


            $scores = $request['scores'] ?? [];

            $side = $this->determineSide($scores);





            $scorecardId = $this->tournamentCourse->scorecard_id;
            $teeId = $request['tee_id'];
            $formattedScore = $this->formatScoreInput($teeId, $scores);
            $scorecard = $this->getScorecard($scorecardId, $teeId);

            $courseHandicap = $this->getCourseHandicap($request, $scorecard);





            $scoreBreakdown = $this->getScoreBreakdown($formattedScore, $courseHandicap);

            DB::beginTransaction();
            $score = $this->createScore($request, $side, $scoreBreakdown);


            return response()->json([
                'success' => false,
                'message' => 'Failed to create score',
                'error' => 'Error test.'

            ], 422);

            Log::debug('Score created', ['score_id' => $score->score_id]);
            $this->createScoreHoles($score->score_id, $scores);


            // // return '<pre>' . print_r($formattedScore) . '</pre>';
            // return '<pre>' . print_r($scoreBreakdown) . '</pre>';

            // return '<pre>' . print_r(array_sum(array_column($scoreBreakdown["hole_details"], 'gross_strokes'))) . '</pre>';








            // return '<pre>' . print_r($scoreBreakdown, true) . '</pre>';

            // // return '<pre>' . print_r($this->formatScoreInput(1, 'M', 1, $scores), true) . '</pre>';





            DB::commit();



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



    private function loadScoreData($request)
    {


        $this->playerProfile = PlayerProfile::with('user', 'userProfile')
            ->where('player_profile_id', $request['player_profile_id'])
            ->firstOrFail();

        $this->tournamentCourse = TournamentCourse::with('scorecard.scoreDifferentialFormula')
            ->where('tournament_course_id', $request['tournament_course_id'])
            ->firstOrFail();

        $this->tournament = $this->tournamentCourse->tournament;


        $this->participant = Participant::where('tournament_id', $request['tournament_id'])
            ->where('player_profile_id', $request['player_profile_id'])
            ->firstOrFail();
    }


    private function getScorecard($scorecardId, $teeId): ?Scorecard
    {
        return Scorecard::with([
            'ratings' => function ($query) use ($teeId) {
                $query->where('tee_id', $teeId);
            },

            'courseRatingFormula'
        ])->where('scorecard_id', $scorecardId)->firstOrFail();
    }


    private function getCourseHandicap($request, Scorecard $scorecard)
    {


        if (empty(trim($request['handicap_index'] ?? ''))) {
            return 0;
        }

        $handicapIndex = (float) $request['handicap_index'];

        // Start with user-defined variables from the formula (if any)
        $variables = json_decode($scorecard->courseRatingFormula?->formula_variables, true) ?? [];


        $formula = $scorecard->courseRatingFormula?->formula_expression ?? '';

        // Convert formula variable objects/arrays into key-value pairs
        $param = [];
        foreach ($variables as $parameter) {
            $param[$parameter['name']] = $parameter['value'];
        }

        // Merge predefined system values
        $systemParams = [
            'HANDICAP_INDEX' => $handicapIndex,
            'SLOPE_RATING'   => $scorecard->slopeRating->slope_rating,
            'COURSE_RATING'  => $scorecard->courseRating->course_rating,
            'PAR'            => $scorecard->scorecardHoles->sum('par'),
        ];

        // Merge formula variables + system parameters
        $parameters = array_merge($systemParams, $param);


        return $this->calculateCourseHandicap($parameters, $formula);
    }


    /**
     * Calculate the Course Handicap based on user-defined or standard formulas.
     *
     * Formula Example (default USGA): 
     *     Course Handicap = Handicap Index × (Slope Rating ÷ 113)
     *
     * Notes:
     * - The Course Rating is generally used to calculate the Handicap Index, 
     *   not directly in this formula, but is included here for flexibility.
     * - Supports dynamic formulas via MathExecutor with registered custom functions.
     *
     * @param  array  $params  Key-value pairs of variables (e.g., HANDICAP_INDEX, SLOPE_RATING)
     * @return float  Calculated course handicap, rounded to the nearest whole number
     */
    public function calculateCourseHandicap(array $params, $formula): float
    {
        $executor = new MathExecutor();

        // Register custom math helpers
        $executor->addFunction('round', fn($value, $precision = 0) => round($value, $precision));

        // Bind parameters as variables
        foreach ($params as $key => $value) {
            if (is_numeric($value)) {
                $executor->setVar($key, (float) $value);
                continue;
            }

            throw new InvalidArgumentException("Non-numeric parameter value for {$key} provided. Key: {$key}, Value: " . print_r($value, true));
        }


        $courseHandicap = $executor->execute($formula);

        return $courseHandicap;
    }


    /**
     * Format scorecard input data for a given scorecard, gender, and tee.
     *
     * @param int $scorecardId
     * @param string $sex
     * @param int $teeId
     * @param array $score Array of scores [hole => ['gross_strokes' => int, ...]]
     * @return array|null Returns array keyed by hole number with par, stroke index, yardage, and gross strokes, or null if not found.
     */
    private function formatScoreInput(int $teeId, array $score): ?array
    {

        $scorecardId = $this->tournamentCourse->scorecard_id;
        $sex = $this->playerProfile->userProfile->sex;
        // Validate input parameters
        if (empty($scorecardId) || empty($sex) || empty($teeId)) {
            Log::warning('formatScoreInput: Missing required parameters', compact('scorecardId', 'gender', 'teeId'));
            return null;
        }

        $scoreCard = Scorecard::where('scorecard_id', $scorecardId)
            ->with([
                'scorecardHoles:scorecard_id,scorecard_hole_id,hole,par',

                'scorecardHoles.yardage' => function ($query) use ($teeId) {
                    $query->select('scorecard_yardage_id', 'scorecard_hole_id', 'yardage')
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
            ->mapWithKeys(function ($hole) use ($score, $sex) {
                return [
                    $hole->hole => [
                        'hole' => $hole->hole,
                        'par' => $hole->par,
                        'stroke_index' => $sex === 'M' ? $hole->men_stroke_index : $hole->ladies_stroke_index,
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
            return '18';
        }

        $holeNumbers = array_keys($scores);
        $hasFront = !empty(array_filter($holeNumbers, fn($h) => $h >= 1 && $h <= 9));
        $hasBack = !empty(array_filter($holeNumbers, fn($h) => $h >= 10 && $h <= 18));

        if ($hasFront && !$hasBack) {
            return 'F9';
        }

        if ($hasBack && !$hasFront) {
            return 'B9';
        }

        return '18';
    }

    /**
     * Calculate total strokes from scores array
     */
    private function calculateGrossScore(array $scores): int
    {
        return array_sum(array_column($scores, 'gross_strokes'));
    }

    /**
     * Create the main Score record
     */
    private function createScore($request, string $side, array $scoreBreakdown): Score
    {
        $userId = Auth::id();
        $now = now();

        $player = $this->playerProfile;

        $tournamentCourse = $this->tournamentCourse;
        $courseId = $tournamentCourse->course_id;

        $scorecard = $tournamentCourse->scorecard->scoreDifferentialFormula;

        if (!$scorecard) {
            Log::warning('createScore: Scorecard not found', ['tournament_id' => $request['tournament_id'], 'course_id' => $courseId]);
            throw new \Exception('Scorecard not found');
        }

        $participant = Participant::with('participantCourse', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
            ->where('user_id', $player->user_id)
            ->where('tournament_id', $request['tournament_id'])
            ->first();



        if (!$participant) {
            Log::warning('createScore: Participant not found', ['user_id' => $player->user_id, 'tournament_id' => $request['tournament_id']]);
            throw new \Exception('Participant not found');
        }





        return Score::create([
            'player_profile_id' => $request['player_profile_id'],
            'user_profile_id' => $player->user_profile_id,
            'user_id' => $player->user_id,
            'participant_id' => $participant->participant_id,
            'tournament_id' => $request['tournament_id'],
            'tournament_course_id' => $request['tournament_course_id'],
            'division_id' => $request['division_id'],
            'course_id' => $tournamentCourse->course_id,
            'tee_id' => $request['tee_id'],

            'date_played' => $request['date_played'],
            'scoring_method' => $request['scoring_method'],
            'score_type' => $request['tournament_id'] ? 'tmt' : 'reg',
            'score_source' => 'form',
            'holes_played' => $side,

            'handicap_index' => $participant->final_tournament_handicap,
            'handicap_index_source' => 'tournament',
            'course_handicap' => null,



            'gross_score' =>  $request['scoring_method'] === 'hole_by_hole' ? $scoreBreakdown['gross_score'] : null,
            'adjusted_score' => $request['scoring_method'] === 'adjusted_score' ? $request['adjusted_score'] : $scoreBreakdown['adjusted_gross_score'],
            'net_score' => $request['scoring_method'] === 'adjusted_score' ? null : $scoreBreakdown['net_score'],

            'score_differential' => null,


            'is_verified' => true,
            'verified_by' => $userId,
            'verified_at' => $now,
            'remarks' => $request['remarks'] ?? null,
            'created_by' => $userId,

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
                'side' => ($holeNumber <= 9) ? 'F9' : 'B9',
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
        $netScore = 0;
        $totalGrossScore = array_sum($scores);
        $adjustedGrossScore = 0;
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

            $netScore += $netStrokes;
            $adjustedGrossScore += $adjustedGrossStrokes;

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
            'net_score' => $netScore,
            'gross_score' => $totalGrossScore,
            'adjusted_gross_score' => $adjustedGrossScore,
            'course_handicap' => $courseHandicap,
            'holes_played' => count($scores)
        ]);

        return $detailed ? [
            'net_score' => $netScore,
            'gross_score' => $totalGrossScore,
            'adjusted_gross_score' => $adjustedGrossScore,
            'hole_details' => $holeDetails,
        ] : $netScore;
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


        $courseHandicap = 19;


        Log::debug("Handicap", [
            "course_handicap" => $courseHandicap,
            "stroke_index" => $strokeIndex
        ]);
        // Basic handicap allocation: if course handicap >= stroke index, player gets 1 stroke
        // For handicaps > 18, players get additional strokes on easier holes

        if ($courseHandicap <= 0) {

            Log::debug('No handicap strokes allocated', ['course_handicap' => $courseHandicap, 'stroke_index' => $strokeIndex]);
            return 0;
        }

        $strokes = 0;

        // First stroke allocation (handicap 1-18)
        if ($courseHandicap >= $strokeIndex) {

            Log::debug("Allocating first handicap stroke (first)", ['course_handicap' => $courseHandicap, 'stroke_index' => $strokeIndex]);
            $strokes++;
        }

        // Second stroke allocation (handicap 19-36)
        if ($courseHandicap >= ($strokeIndex + 18)) {
            Log::debug("Allocating first handicap stroke (second)", ['course_handicap' => $courseHandicap, 'stroke_index' => $strokeIndex]);
            $strokes++;
        }

        // Third stroke allocation (handicap 37-54) - rare but possible
        if ($courseHandicap >= ($strokeIndex + 36)) {
            Log::debug("Allocating first handicap stroke (third)", ['course_handicap' => $courseHandicap, 'stroke_index' => $strokeIndex]);
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
        $scores = array_column($scoreArr, 'gross_strokes', 'hole');
        $strokeIndex = array_column($scoreArr, 'stroke_index', 'hole');
        $parValues = array_column($scoreArr, 'par', 'hole');

        Log::debug($scores);
        Log::debug($strokeIndex);
        Log::debug($parValues);

        return $this->calculatePlayerNetScore($scores, $courseHandicap, $strokeIndex, $parValues, true);
    }
}
