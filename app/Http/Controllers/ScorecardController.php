<?php

namespace App\Http\Controllers;

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
        //
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
            'yardages.hole',
            'scorecardHoles.yardages',
            'ratings.tee',
            'course.tees',
            'strokeIndexes'


        )->findOrFail($id);

        $yardages = [];

        foreach ($scorecard->yardages as $yardage) {
            $yardages[$yardage->tee_id][$yardage->hole->hole] = $yardage->yardage;
        }

        // echo '<pre>';
        // print_r($yardages);
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
