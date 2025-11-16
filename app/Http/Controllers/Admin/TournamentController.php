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


    protected TournamentService $tournamentService;

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



            'tournament_handicap_formula_1' => 'required|string|max:255',
            'tournament_handicap_formula_2' => 'required|string|max:255',
            'tournament_handicap_formula_3' => 'required|string|max:255',
            'tournament_handicap_formula_4' => 'nullable|string|max:255',
            'course_handicap_formula_desc' => 'nullable|string|max:255',
            'tournament_handicap_formula_desc' => 'nullable|string|max:255',

            'tournament_handicap_calculation_table' => 'nullable|array|min:1',
            'tournament_handicap_calculation_table.*' => 'required|array',
            'tournament_handicap_calculation_table.*.min' => 'required|integer|min:0',
            'tournament_handicap_calculation_table.*.max' => 'required|integer|min:0|gte:tournament_handicap_calculation_table.*.min',
            'tournament_handicap_calculation_table.*.method' => 'required|string|in:LOWEST,HIGHEST,AVERAGE_OF_LOWEST',
            'tournament_handicap_calculation_table.*.count' => 'required|integer|min:1',
            'tournament_handicap_calculation_table.*.adjustment' => 'required|numeric',

            'divisions' => 'nullable|array|min:1',
            'divisions.*.name' => 'required|string|max:100',
            'divisions.*.type' => 'required|string|in:regular,sponsored',
            'divisions.*.description' => 'nullable|string|max:255',
            'divisions.*.sex' => 'required|string|in:M,F,X',
            'divisions.*.participant_type' => 'required|string|in:member,guest,mixed',
            'divisions.*.age_min' => 'nullable|integer|min:0',
            'divisions.*.age_max' => 'nullable|integer|min:0|gt:divisions.*.age_min',
            'divisions.*.handicap_min' => 'nullable|numeric|min:0',
            'divisions.*.handicap_max' => 'nullable|numeric|min:0|gt:divisions.*.handicap_min',


        ]);



        //there shoud be no gap also in the ranges



        // return response()->json([
        //     'success' => false,
        //     'message' => "Handicap score differential config ranges are valid."
        // ], 422);




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
    public function validateTournamentHandicapFormula(Request $request)
    {
        $validated = $request->validate([
            'formula' => 'required|string',
            'whs_handicap_index' => 'nullable|numeric',
            'local_handicap_index' => 'nullable|numeric',
        ]);

        try {
            $executor = new MathExecutor();



            // Register custom math helpers
            $executor->addFunction('ROUND', fn($value, $precision = 0) => round($value, $precision));
            $executor->addFunction('MIN', fn(...$args) => min($args));
            $executor->addFunction('MAX', fn(...$args) => max($args));

            $executor->addFunction('AVG', function (...$args) {
                if (count($args) === 0) {
                    throw new MathException("AVG requires at least one argument.");
                }
                return array_sum($args) / count($args);
            });
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


    public function validateCourseHandicapFormula(Request $request)
    {
        $validated = $request->validate([
            'formula' => 'required|string',
            'handicap_index' => 'nullable|numeric',
            'slope_rating' => 'nullable|numeric',
            'course_rating' => 'nullable|numeric',
            'par' => 'nullable|integer',
        ]);

        try {
            $executor = new MathExecutor();

            // Register custom math helpers
            $executor->addFunction('ROUND', fn($value, $precision = 0) => round($value, $precision));
            $executor->addFunction('MIN', fn(...$args) => min($args));
            $executor->addFunction('MAX', fn(...$args) => max($args));
            $executor->addFunction('CEIL', fn($value) => ceil($value));
            $executor->addFunction('FLOOR', fn($value) => floor($value));

            $executor->addFunction('AVG', function (...$args) {
                if (count($args) === 0) {
                    throw new MathException("AVG requires at least one argument.");
                }
                return array_sum($args) / count($args);
            });

            // Add variables to the executor
            $executor->setVar('HANDICAP_INDEX', (float) ($validated['handicap_index'] ?? 0));
            $executor->setVar('SLOPE_RATING', (float) ($validated['slope_rating'] ?? 113));
            $executor->setVar('COURSE_RATING', (float) ($validated['course_rating'] ?? 0));
            $executor->setVar('PAR', (int) ($validated['par'] ?? 72));

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
