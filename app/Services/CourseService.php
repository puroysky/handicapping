<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Scorecard;
use App\Models\ScorecardHole;
use App\Models\ScorecardHoleHandicap;
use App\Models\Tee;
use App\Models\Tournament;
use App\Models\TournamentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CourseService
{


    public function index()
    {
        //
    }


    public function create()
    {
        return view('admin.courses.create-course-form');
    }

    public function store($request)
    {

        // Assuming you have a Course model
        $course = new \App\Models\Course();
        $course->course_code = $request['course_code'];
        $course->course_name = $request['course_name'];
        $course->course_desc = $request['course_desc'] ?? null;
        $course->remarks = $request['remarks'] ?? null;
        $course->active = $request['active'] ?? true;
        $course->created_by = Auth::id();
        $course->save();

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course,
            'redirect' => route('admin.courses.index')
        ], 201);
    }

    public function getTees(Request $request, $tournamentCourseId)
    {


        try {

            $gender = 'M';

            $tournamentCourse = TournamentCourse::where('tournament_course_id', $tournamentCourseId)->firstOrFail();

            $pars = ScorecardHole::with([
                'strokeIndex' => function ($query) use ($gender) {
                    $query->where('gender', $gender);
                }
            ])->whereHas('scorecard', function ($query) use ($tournamentCourse) {
                $query->where('scorecard_id', $tournamentCourse->scorecard_id);
            })->where('scorecard_id', $tournamentCourse->scorecard_id)
                ->orderBy('hole')
                ->get();




            $tees = Tee::where('course_id', $tournamentCourse->course_id)->where('active', true)->orderBy('tee_name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Tees fetched successfully',
                'tees' => $tees,
                'holes' => $pars,
                // 'strokeIndexs' => $strokeIndexs
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching tournament tees: ' . $e->getMessage());
            Log::error($e->getLine());
            Log::error($e->getFile());
            return response()->json([
                'message' => 'Error fetching tournament tees : ' . $e->getMessage(),
            ], 500);
        }
    }
}
