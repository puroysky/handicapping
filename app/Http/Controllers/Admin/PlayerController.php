<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\PlayerImportService;
use App\Services\PlayerLocalHandicapService;
use Illuminate\Http\Request;
use App\Services\PlayerService;

class PlayerController extends Controller
{

    protected $playerService;


    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return $this->playerService->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->playerService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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



    /**
     * Get recent scores for a specific player
     */
    public function getRecentScores($playerId)
    {
        try {
            $player = \App\Models\User::with('profile', 'player')->find($playerId);

            if (!$player) {
                return response()->json([
                    'success' => false,
                    'message' => 'Player not found',
                    'scores' => []
                ]);
            }

            // Get recent scores for this player (last 5 scores)
            $recentScores = \App\Models\Score::where('player_id', $playerId)
                ->with(['tournament', 'course'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($score) {
                    return [
                        'id' => $score->id,
                        'entry_date' => $score->created_at->format('M d, Y'),
                        'tournament_name' => $score->tournament->tournament_name ?? 'N/A',
                        'course_name' => $score->course->course_name ?? 'N/A',
                        'gross_score' => $score->gross_score ?? '-',
                        'adjusted_score' => $score->adjusted_score ?? '-',
                        'handicap' => $score->handicap ?? '-',
                        'score_differential' => $score->score_differential ?? '-'
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Recent scores retrieved successfully',
                'scores' => $recentScores,
                'count' => $recentScores->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving recent scores: ' . $e->getMessage(),
                'scores' => []
            ], 500);
        }
    }

    public function import(Request $request)
    {




        $import = new PlayerImportService();

        $result = $import->import($request);

        if ($result['success']) {
            return response()->json($result, 200);
        } else {
            return response()->json($result, 400);
        }
    }

    public function search()
    {
        return $this->playerService->searchPlayers(request('q'));
    }


    //used from the tournament participants to get available players
    public function getAvailablePlayers(Request $request)
    {
        return $this->playerService->getAvailablePlayers($request);
    }


    public function handicap(Request $request)
    {


        $playerLocalHandicapService = new PlayerLocalHandicapService();



        return $playerLocalHandicapService->calculate($request->player_id);
    }

    /**
     * Get handicap information for a specific player
     */
    public function getHandicapInfo($playerId)
    {
        try {
            $player = \App\Models\User::find($playerId);

            if (!$player) {
                return response()->json([
                    'success' => false,
                    'message' => 'Player not found'
                ], 404);
            }

            // Get recent scores for this player (up to 40 for calculation)
            $recentScores = \App\Models\Score::where('user_id', $playerId)
                ->orderBy('date_played', 'desc')
                ->limit(40)
                ->get()
                ->map(function ($score) {
                    return [
                        'score_id' => $score->id,
                        'user_id' => $score->user_id,
                        'score_differential' => $score->score_differential,
                        'holes_played' => $score->holes_played,
                        'date_played' => $score->date_played,
                        'adjusted_gross_score' => $score->adjusted_gross_score
                    ];
                })
                ->toArray();

            // Get latest local handicap index from participant
            $participant = \App\Models\Participant::where('user_id', $playerId)
                ->orderBy('updated_at', 'desc')
                ->first();

            // Build formula label from tournament calculation table
            $formulaLabel = 'N/A';
            $methodName = 'Unknown';
            $usedScores = 0;
            $totalRecentScores = count($recentScores);
            $configBracket = null;

            if ($participant && $participant->tournament) {
                $calculationTable = json_decode($participant->tournament->tournament_handicap_calculation_table, true);
                if ($calculationTable && is_array($calculationTable)) {
                    // Try to match the bracket based on score count
                    foreach ($calculationTable as $bracket) {
                        $scoreCount = $totalRecentScores;
                        if ($scoreCount >= (int)$bracket['min'] && $scoreCount <= (int)$bracket['max']) {
                            $method = $bracket['method'] ?? 'UNKNOWN';
                            $count = $bracket['count'] ?? 1;
                            $usedScores = (int)$count;
                            $methodName = $method;
                            $configBracket = $bracket; // Store the matched bracket

                            // Format the formula label
                            if ($method === 'LOWEST') {
                                $formulaLabel = "Lowest {$count} for score {$bracket['min']} to {$bracket['max']}";
                            } elseif ($method === 'HIGHEST') {
                                $formulaLabel = "Highest {$count} for score {$bracket['min']} to {$bracket['max']}";
                            } elseif ($method === 'AVERAGE_OF_LOWEST') {
                                $formulaLabel = "Average of Lowest {$count} for score {$bracket['min']} to {$bracket['max']}";
                            }
                            break;
                        }
                    }
                }
            }

            // If no handicap information, return with null handicaps but include config and scores
            if (!$participant || $participant->local_handicap_index === null) {
                return response()->json([
                    'success' => true,
                    'handicaps' => null,
                    'message' => 'No handicap information available for this player',
                    'config' => $configBracket ?? [
                        'max' => 'N/A',
                        'min' => 'N/A',
                        'count' => 'N/A',
                        'method' => 'N/A',
                        'adjustment' => 'N/A'
                    ],
                    'recent_scores' => $recentScores
                ], 200);
            }

            return response()->json([
                'success' => true,
                'handicaps' => [
                    'local_handicap_index' => $participant->local_handicap_index,
                    'details' => [
                        'recent_scores' => $totalRecentScores,
                        'used_scores' => $usedScores,
                        'method' => $methodName,
                        'adjustment' => 0
                    ]
                ],
                'config' => $configBracket ?? [
                    'max' => 'N/A',
                    'min' => 'N/A',
                    'count' => 'N/A',
                    'method' => 'N/A',
                    'adjustment' => 'N/A'
                ],
                'recent_scores' => $recentScores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving handicap information: ' . $e->getMessage()
            ], 500);
        }
    }
}
