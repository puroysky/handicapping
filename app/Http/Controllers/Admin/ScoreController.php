<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\Scorecard;
use App\Models\Tournament;
use App\Models\TournamentCourse;
use Illuminate\Http\Request;
use App\Services\ScoreService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $tee = 1; // Blue
        $scorecardId = 1; // Default scorecard ID (North)
        $scorecard = Scorecard::with([

            // Eager load courseRatings for the selected tee
            'courseRatings' => function ($query) use ($tee) {
                $query->where('tee_id', $tee);
            },

            // Eager load slopeRatings for the selected tee
            'slopeRatings' => function ($query) use ($tee) {
                $query->where('tee_id', $tee);
            },

            'scorecardHoles.yardage' => function ($query) use ($tee) {
                $query->where('tee_id', $tee);
            },

        ])
            ->where('scorecard_id', $scorecardId)
            ->first();


        $tournaments = Tournament::whereDate('tournament_start', '<=', now())
            ->limit(5)
            ->get();



        // echo '<pre>';
        // print_r($scorecard->toArray());
        // echo '</pre>';
        // return;
        return view('admin.scores.create-score-form', compact('scorecard', 'tournaments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        // Example incoming request data (for debugging)
        // {
        //   "player_profile_id":"1",
        //   "tournament_id":"2",
        //   "tournament_course_id":"2",
        //   "tee_id":"1",
        //   "scoring_method":"adjusted_score",
        //   "score_date":"2025-10-15",
        //   "_token":"DVt3lSvYkADxTnn35SVdChkqliEYbVDKT0yrwrm5",
        //   "scores":{
        //     "1":{"stroke":2,"raw_input":"x","par":"4","handicap_index":"3","yardage":"360"},
        //     "2":{"stroke":2,"raw_input":"2","par":"4","handicap_index":"5","yardage":"346"},
        //     "3":{"stroke":2,"raw_input":"2","par":"3","handicap_index":"11","yardage":"192"},
        //     "4":{"stroke":2,"raw_input":"2","par":"3","handicap_index":"13","yardage":"130"},
        //     "5":{"stroke":5,"raw_input":"5","par":"5","handicap_index":"1","yardage":"521"},
        //     "6":{"stroke":6,"raw_input":"6","par":"3","handicap_index":"7","yardage":"164"},
        //     "7":{"stroke":4,"raw_input":"4","par":"5","handicap_index":"17","yardage":"476"},
        //     "8":{"stroke":5,"raw_input":"5","par":"3","handicap_index":"15","yardage":"156"},
        //     "9":{"stroke":4,"raw_input":"4","par":"5","handicap_index":"9","yardage":"469"}
        //   }
        // }


        $validated = $request->validate([
            'player_profile_id' => 'required|exists:player_profiles,player_profile_id',
            'tournament_id' => 'required|exists:tournaments,tournament_id',
            'tournament_course_id' => 'required|exists:tournament_courses,tournament_course_id',
            'tee_id' => 'required|exists:tees,tee_id',
            'scoring_method' => 'required|in:hole_by_hole,adjusted_score',
            'score_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'adjusted_score' => $request->scoring_method === 'adjusted_score' ? 'required|integer|min:1|max:200' : 'nullable',
            'scores' => [
                $request->scoring_method === 'hole_by_hole' ? 'required' : 'nullable',
                'array',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->scoring_method === 'hole_by_hole' && $value) {
                        $count = count($value);
                        if ($count !== 9 && $count !== 18) {
                            $fail('The scores must contain exactly 9 or 18 holes.');
                        }

                        // Validate that holes are sequential and complete for the side played
                        $holeNumbers = array_keys($value);
                        sort($holeNumbers);

                        $hasFront = !empty(array_filter($holeNumbers, fn($h) => $h >= 1 && $h <= 9));
                        $hasBack = !empty(array_filter($holeNumbers, fn($h) => $h >= 10 && $h <= 18));

                        if ($hasFront) {
                            // Check front nine (holes 1-9) are complete
                            $frontHoles = array_filter($holeNumbers, fn($h) => $h >= 1 && $h <= 9);
                            $expectedFront = range(1, 9);
                            $missingFront = array_diff($expectedFront, $frontHoles);
                            if (!empty($missingFront)) {
                                $fail('Front nine is incomplete. Missing holes: ' . implode(', ', $missingFront));
                            }
                        }

                        if ($hasBack) {
                            // Check back nine (holes 10-18) are complete
                            $backHoles = array_filter($holeNumbers, fn($h) => $h >= 10 && $h <= 18);
                            $expectedBack = range(10, 18);
                            $missingBack = array_diff($expectedBack, $backHoles);
                            if (!empty($missingBack)) {
                                $fail('Back nine is incomplete. Missing holes: ' . implode(', ', $missingBack));
                            }
                        }
                    }
                },
            ],
            'scores.*.strokes' => $request->scoring_method === 'hole_by_hole' ? 'required|integer' : 'nullable|integer',
            'scores.*.raw_input' => ['required', 'regex:/^(x|\d+)$/'],
            'scores.*.par' => 'required|integer',
            'scores.*.handicap_index' => 'required|integer',
            'scores.*.yardage' => 'required|integer',
        ]);


        return $this->scoreService->store($request);
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
