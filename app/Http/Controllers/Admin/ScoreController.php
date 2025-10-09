<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scorecard;
use Illuminate\Http\Request;
use App\Services\ScoreService;

class ScoreController extends Controller
{

    protected ScoreService $scoreService;

    public function __construct(ScoreService $scoreService)
    {
        $this->scoreService = $scoreService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->scoreService->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tee = 2; // Blue
        $scorecard = Scorecard::with([

            // Eager load courseRatings for the selected tee
            'courseRatings' => function ($query) use ($tee) {
                $query->where('tee_id', $tee);
            },

            // Eager load slopeRatings for the selected tee
            'slopeRatings' => function ($query) use ($tee) {
                $query->where('tee_id', $tee);
            },

            // Eager load scorecardDetails
            'scorecardDetails' => function ($query) {
                $query->leftJoin('scorecard_pars', 'scorecard_details.hole', '=', 'scorecard_pars.hole')
                    ->select('scorecard_details.*', 'scorecard_pars.par');
            },

            // Eager load scorecardPars
            'scorecardPars'
        ])
            ->where('scorecard_id', 1)
            ->first();




        // echo '<pre>';
        // print_r($scorecard->toArray());
        // echo '</pre>';
        // return;
        return view('admin.scores.create-score-form', compact('scorecard'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Example usage (wire this when your form posts real data):
        // $validated = $request->validate([
        //     'scores' => 'array',
        //     'scores.*' => 'nullable|string', // digits or x
        //     'pars' => 'array',
        //     'pars.*' => 'required|integer|min:3|max:5',
        // ]);
        // $service = app(\App\Services\ScoreService::class);
        // $result = $service->computeRound($validated['scores'] ?? [], $validated['pars'] ?? []);
        // return back()->with('computed', $result);
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
}
