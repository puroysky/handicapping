<?php

namespace App\Services;

use App\Models\PlayerProfile;
use App\Models\Score;
use App\Models\ScoreHole;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        return array_sum(array_column($scores, 'stroke'));
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
                'strokes' => $holeData['strokes'],
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


    private function calculateNetScore(int $grossScore, int $handicapStroke): int {}

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
}
