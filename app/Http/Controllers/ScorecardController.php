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
        $formulas = Formula::select('formula_id as id', 'formula_name as name')->get();

        return view('admin.scorecards.create-scorecard-form', compact('scorecard', 'yardages', 'formulas', 'courses'));
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

        $scorecard = Scorecard::with(
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
        // print_r($scorecard->scorecardHoles->pluck('par')->sum());
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
