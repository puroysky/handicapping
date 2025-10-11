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
        $tournaments = \App\Models\Tournament::with('courses.course')->get();
        $title = 'Tournaments';
        return view('admin.tournaments.tournaments', compact('tournaments', 'title'));
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
            $tournament->created_by = Auth::id();

            $tournament->save();

            $this->storeTournamentCourses($tournament, $request['course_ids']);


            DB::commit();

            return response()->json([
                'message' => 'Tournament created successfully',
                'tournament' => $tournament
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => 'Error creating tournament: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function storeTournamentCourses($tournament, $courseIds)
    {
        foreach ($courseIds as $courseId) {
            $tournament->courses()->create([
                'tournament_id' => $tournament->tournament_id,
                'course_id' => $courseId,
                'created_by' => Auth::id()
            ]);
        }
    }

    public function getCourses($request, $tournamentId)
    {

        try {
            $courses = TournamentCourse::with('course')
                ->where('tournament_id', $tournamentId)
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
