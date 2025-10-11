<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeeService
{


    public function index()
    {
        //
    }


    public function create()
    {


        $courses = \App\Models\Course::where('active', true)->orderBy('course_name')->get();
        $title = 'Create New Tee';
        return view('admin.tees.create-tee-form', compact('courses', 'title'));
    }

    public function store($request)
    {

        // Assuming you have a Tee model
        $course = new \App\Models\Tee();
        $course->tee_code = $request['tee_code'];
        $course->tee_name = $request['tee_name'];
        $course->tee_desc = $request['tee_desc'] ?? null;
        $course->course_id = $request['course_id'];
        $course->remarks = $request['remarks'] ?? null;
        $course->active = $request['active'] ?? true;
        $course->created_by = Auth::id();
        $course->save();

        return response()->json([
            'message' => 'Tee created successfully',
            'tee' => $course,
            'redirect' => route('admin.tees.index')
        ], 201);
    }


    public function getYardages(Request $request, $teeId)
    {


        try {
            // Get yardages with scorecard hole details for the specified tee
            $yardages = \App\Models\ScorecardYard::select(
                'scorecard_yards.scorecard_yard_id',
                'scorecard_yards.yardage',
                'scorecard_holes.hole',
                'scorecard_holes.par',
                'scorecard_holes.scorecard_id'
            )
                ->join('scorecard_holes', 'scorecard_yards.scorecard_hole_id', '=', 'scorecard_holes.scorecard_hole_id')
                ->where('scorecard_yards.tee_id', $teeId)
                ->orderBy('scorecard_holes.hole')
                ->get();



            $handicapHoles = \App\Models\ScorecardHoleHandicap::select(
                'scorecard_handicaps.scorecard_hole_id',
                'scorecard_handicaps.handicap',
                'scorecard_holes.hole',
                'scorecard_holes.par',
                'scorecard_holes.scorecard_id'
            )
                ->join('scorecard_holes', 'scorecard_handicaps.scorecard_hole_id', '=', 'scorecard_holes.scorecard_hole_id')
                ->where('scorecard_handicaps.tee_id', $teeId)
                ->orderBy('scorecard_holes.hole')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Yardages fetched successfully',
                'tee_id' => $teeId,
                'yardages' => $yardages,
                'total_holes' => $yardages->count()
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching tee yardages: ' . $e->getMessage(), [
                'tee_id' => $teeId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching tee yardages',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
