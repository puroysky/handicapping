<?php

namespace App\Services;

class ScoreService
{
    // How many strokes to add when input is 'x'
    private int $xStrokePenalty;

    public function __construct(int $xStrokePenalty = 2)
    {
        $this->xStrokePenalty = $xStrokePenalty;
    }


    public function index()
    {

        $scores = \App\Models\Score::with('user')->orderBy('created_at', 'desc')->get();
        $title = 'Scores';
        return view('admin.scores.scores', compact('scores', 'title'));
    }

    public function getTees($request, $courseId)
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
