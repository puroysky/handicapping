<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\PlayerImportService;
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
}
