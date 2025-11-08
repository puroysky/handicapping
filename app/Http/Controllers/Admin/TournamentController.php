<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Services\TournamentService;
use Brick\Math\Exception\MathException;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use NXP\MathExecutor;

class TournamentController extends Controller
{


    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->tournamentService->index();
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->tournamentService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {





        echo '<pre>';
        print_r($request->all());
        echo '</pre>';
        return;








        // Validate the request data
        $validatedData = $request->validate([
            'tournament_name' => 'required|string|max:100',
            'tournament_desc' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'tournament_start' => 'required|date',
            'tournament_end' => 'required|date|after_or_equal:tournament_start',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,course_id',

            'course_scorecards' => 'required|array|min:1',
            'course_scorecards.*' => 'exists:scorecards,scorecard_id',

            'score_diff_start_date' => 'nullable|date',
            'score_diff_end_date' => 'nullable|date|after_or_equal:score_diff_start_date',
            'recent_scores_count' => 'nullable|integer|min:1',


            'handicap_formula_expression' => 'required|string|max:255',
            'handicap_formula_desc' => 'required|string|max:255',

            'scores_config' => 'nullable|array|min:1',
            'scores_config.*.min' => 'required|integer|min:0',
            'scores_config.*.max' => 'required|integer|min:0|gt:scores_config.*.min',
            'scores_config.*.method' => 'required|string|in:LOWEST,HIGHEST,AVERAGE_OF_LOWEST',
            'scores_config.*.count' => 'required|integer|min:1',
            'scores_config.*.adjustment' => 'required|numeric',



            'divisions' => 'required|array|min:1',
            'divisions.*.name' => 'required|string|max:100',
            'divisions.*.description' => 'nullable|string|max:255',


        ]);




        return $this->tournamentService->store($validatedData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->tournamentService->show($id);
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

    public function getCourses(Request $request, $tournamentId)
    {
        return $this->tournamentService->getCourses($request, $tournamentId);
    }

    /**
     * Validate and execute the handicap formula with MathExecutor
     */
    public function validateFormula(Request $request)
    {
        $validated = $request->validate([
            'formula' => 'required|string',
            'whs_handicap_index' => 'required|numeric',
            'local_handicap_index' => 'required|numeric',
        ]);

        try {
            $executor = new MathExecutor();



            // Register custom math helpers
            $executor->addFunction('ROUND', fn($value, $precision = 0) => round($value, $precision));
            $executor->addFunction('MIN', fn(...$args) => min($args));
            $executor->addFunction('MAX', fn(...$args) => max($args));
            // Add variables to the executor
            $executor->setVar('WHS_HANDICAP_INDEX', (float) $validated['whs_handicap_index']);
            $executor->setVar('LOCAL_HANDICAP_INDEX', (float) $validated['local_handicap_index']);

            // Execute the formula
            $result = $executor->execute($validated['formula']);

            return response()->json([
                'success' => true,
                'result' => round($result, 2),
                'message' => 'Formula executed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Formula validation failed'
            ], 422);
        }
    }
}
