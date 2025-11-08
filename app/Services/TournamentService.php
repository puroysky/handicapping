<?php

namespace App\Services;

use App\Models\TournamentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TournamentService
{

    public function index()
    {
        $tournaments = \App\Models\Tournament::with('tournamentCourses.course', 'tournamentCourses.scorecard')->get();

        // echo '<pre>';
        // print_r($tournaments->toArray());
        // echo '</pre>';
        // return;
        $title = 'Tournaments';
        return view('admin.tournaments.tournaments', compact('tournaments', 'title'));
    }


    public function show($id)
    {
        $tournament = \App\Models\Tournament::where('tournament_id', $id)
            ->with('tournamentCourses.course', 'tournamentCourses.scorecard')
            ->first();

        return response()->json($tournament);
    }
    public function create()
    {
        $scorecards = \App\Models\Scorecard::where('active', true)->orderBy('scorecard_name')->get();
        $courses = \App\Models\Course::where('active', true)->orderBy('course_name')->get();
        $title = 'Create New Tournament';
        return view('admin.tournaments.create-tournament-form', compact('courses', 'title', 'scorecards'));
    }

    public function store($request)
    {

        DB::beginTransaction();

        try {

            $tournament = new \App\Models\Tournament();
            $tournament->tournament_name = $request['tournament_name'];
            $tournament->tournament_desc = $request['tournament_desc'];
            $tournament->remarks = $request['remarks'];
            $tournament->tournament_start = $request['tournament_start'];
            $tournament->tournament_end = $request['tournament_end'];



            $tournament->score_diff_start_date = $request['score_diff_start_date'] ?? null;
            $tournament->score_diff_end_date = $request['score_diff_end_date'] ?? null;
            $tournament->recent_scores_count = $request['recent_scores_count'] ?? null;

            $tournament->scores_to_average = $request['scores_to_average'] ?? null;
            $tournament->handicap_formula_expression = $request['handicap_formula_expression'] ?? null;
            $tournament->created_by = Auth::id();

            $tournament->save();



            // echo '<pre>';
            // print_r($request['course_scorecards']);
            // echo '</pre>';

            // return;



            // return response()->json([
            //     'message' => 'Tournament error occurred',
            //     'tournament' => $tournament
            // ], 500);
            $this->storeTournamentCourses($tournament, $request['course_ids'], $request['course_scorecards']);


            DB::commit();

            return response()->json([
                'message' => 'Tournament created successfully',
                'tournament' => $tournament
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => 'Error creating tournament: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()

            ], 500);
        }
    }

    private function storeTournamentCourses($tournament, $courseIds, $scorecardIds)
    {
        foreach ($courseIds as $courseId) {
            $tournament->tournamentcourses()->create([
                'tournament_id' => $tournament->tournament_id,
                'scorecard_id' => $scorecardIds[$courseId],
                'course_id' => $courseId,
                'created_by' => Auth::id()
            ]);
        }
    }

    public function getCourses($request, $tournamentId)
    {

        try {
            $courses = TournamentCourse::with('course')->where('tournament_id', $tournamentId)
                ->where('active', true)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Courses fetched successfully',
                'courses' => $courses
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching tournament courses: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching tournament courses',
            ], 500);
        }
    }
}
