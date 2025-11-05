<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Formula;
use App\Models\Scorecard;
use App\Services\ScorecardService;
use Illuminate\Http\Request;

class ScorecardController extends Controller
{

    protected $scorecardService;


    public function __construct(ScorecardService $scorecardService)
    {
        $this->scorecardService = $scorecardService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->scorecardService->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $scorecard = Scorecard::with(
            'strokeIndexes',
            'scorecardHoles',
            'ratings.tee',
            'course.tees'



        )->findOrFail(1);

        $courses = Course::get();
        // return;

        $yardages = [];

        foreach ($scorecard->yardages as $yardage) {
            $yardages[$yardage->tee_id][$yardage->hole->hole] = $yardage->yardage;
        }


        // echo '<pre>';
        // print_r($scorecard->scorecardHoles->pluck('par')->sum());
        // echo '</pre>';
        // return;

        // echo '<pre>';
        // print_r($yardages);
        // echo '</pre>';
        // return;



        // Load available formulas for select fields (id,name)
        $formulas = Formula::leftJoin('formula_types', 'formula_types.formula_type_id', '=', 'formulas.formula_type_id')
            ->select('formulas.formula_id as id', 'formulas.formula_name as name', 'formula_types.formula_type_code as code')
            ->get();


        // echo '<pre>';
        // print_r($formulas->where('code', 'AGS'));
        // echo '</pre>';
        // return;

        return view('admin.scorecards.create-scorecard-form', compact('scorecard', 'yardages', 'formulas', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validated = $request->validate([

            'scorecard_code' => 'required|string|max:50|unique:scorecards,scorecard_code',
            'scorecard_name' => 'required|string|max:255',
            'scorecard_desc' => 'nullable|string|max:500',
            'scorecard_type' => 'required|in:tournament,regular',
            'adjusted_gross_score_formula_id' => 'nullable|exists:formulas,formula_id',
            'score_differential_formula_id' => 'nullable|exists:formulas,formula_id',
            'course_handicap_formula_id' => 'nullable|exists:formulas,formula_id',
            'course_id' => 'required|exists:courses,course_id',
            'x_value' => 'required|in:BOGEY,DOUBLE_BOGEY,TRIPLE_BOGEY',
            'active' => 'sometimes|boolean',

            'course_rating' => 'required|array',
            'course_rating.*' => 'required|numeric|min:0',
            'front_nine_course_rating' => 'required|array',
            'front_nine_course_rating.*' => 'required|numeric|min:0',

            'slope_rating' => 'required|array',
            'slope_rating.*' => 'required|numeric|min:55|max:155',
            'front_nine_slope_rating' => 'required|array',
            'front_nine_slope_rating.*' => 'required|numeric|min:55|max:155',

            'yardages' => 'required|array',
            'yardages.*' => 'required|array',
            'yardages.*.*' => 'required|integer|min:50|max:800',

            'par' => 'required|array',
            'par.*' => 'required|integer|min:1|max:50',
            'par' => 'required|array|size:18',


            'male_handicap' => 'required|array',
            'male_handicap.*' => 'required|integer|min:1|max:100',

            'ladies_handicap' => 'required|array',
            'ladies_handicap.*' => 'required|integer|min:1|max:100',


        ]);


        return $this->scorecardService->store($request);
        echo '<pre>';
        print_r($request->all());
        echo '</pre>';
        return;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $scorecard = Scorecard::with(
            'adjustedGrossScoreFormula',
            'scoreDifferentialFormula',
            'courseHandicapFormula',
            'strokeIndexes',
            'scorecardHoles',
            'ratings.tee',
            'course.tees'

        )->findOrFail($id);


        // return;

        $yardages = [];

        foreach ($scorecard->yardages as $yardage) {
            $yardages[$yardage->tee_id][$yardage->hole->hole] = $yardage->yardage;
        }

        // echo '<pre>';
        // print_r($scorecard->toArray());
        // echo '</pre>';
        // return;

        // echo '<pre>';
        // print_r($scorecard->toArray());
        // echo '</pre>';
        // return;



        return view('admin.scorecards.scorecard-preview', compact('scorecard', 'yardages'));
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
}
