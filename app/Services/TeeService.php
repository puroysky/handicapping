<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
