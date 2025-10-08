<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PgSql\Lob;

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
}
