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











        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';

        // return;













        // [handicap_score_differential_config] => Array
        // (
        //     [3] => Array
        //         (
        //             [min] => 1
        //             [max] => 3
        //             [method] => LOWEST
        //             [count] => 1
        //             [adjustment] => -2.0
        //         )

        //     [6] => Array
        //         (
        //             [min] => 4
        //             [max] => 6
        //             [method] => AVERAGE_OF_LOWEST
        //             [count] => 2
        //             [adjustment] => -1.0
        //         )

        //     [9] => Array
        //         (
        //             [min] => 7
        //             [max] => 9
        //             [method] => AVERAGE_OF_LOWEST
        //             [count] => 3
        //             [adjustment] => 0
        //         )

        //     [14] => Array
        //         (
        //             [min] => 10
        //             [max] => 14
        //             [method] => AVERAGE_OF_LOWEST
        //             [count] => 4
        //             [adjustment] => 0
        //         )

        //     [19] => Array
        //         (
        //             [min] => 15
        //             [max] => 19
        //             [method] => AVERAGE_OF_LOWEST
        //             [count] => 5
        //             [adjustment] => 0
        //         )

        //     [20] => Array
        //         (
        //             [min] => 20
        //             [max] => 999
        //             [method] => AVERAGE_OF_LOWEST
        //             [count] => 8
        //             [adjustment] => 0
        //         )

        // )







        // [divisions] => Array
        // (
        //     [0] => Array
        //         (
        //             [name] => Men
        //             [description] => 5
        //             [sex] => X
        //             [participant_type] => mixed
        //             [age_min] => 1
        //             [age_max] => 49
        //             [handicap_min] => 1
        //             [handicap_max] => 5
        //         )

        //     [1] => Array
        //         (
        //             [name] => Women
        //             [description] => 7
        //             [sex] => X
        //             [participant_type] => mixed
        //             [age_min] => 1
        //             [age_max] => 80
        //             [handicap_min] => 1
        //             [handicap_max] => 50
        //         )

        // )




        // return;




        //prepare division and handicap score differential config









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



            'local_handicap_formula_1' => 'required|string|max:255',
            'local_handicap_formula_2' => 'required|string|max:255',
            'local_handicap_formula_3' => 'required|string|max:255',
            'local_handicap_formula_4' => 'required|string|max:255',
            'handicap_formula_desc' => 'required|string|max:255',



            'handicap_score_differential_config' => 'nullable|array|min:1',
            'handicap_score_differential_config.*' => 'required|array',
            'handicap_score_differential_config.*.min' => 'required|integer|min:0',
            'handicap_score_differential_config.*.max' => 'required|integer|min:0|gt:handicap_score_differential_config.*.min',
            'handicap_score_differential_config.*.method' => 'required|string|in:LOWEST,HIGHEST,AVERAGE_OF_LOWEST',
            'handicap_score_differential_config.*.count' => 'required|integer|min:1',
            'handicap_score_differential_config.*.adjustment' => 'required|numeric',



            'divisions' => 'nullable|array|min:1',
            'divisions.*.name' => 'required|string|max:100',
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
}
