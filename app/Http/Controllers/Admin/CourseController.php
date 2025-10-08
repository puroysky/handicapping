<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationData;
use App\Services\CourseService;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{

    protected $courseService;


    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.courses.create-course-form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log all request data
        Log::info('Course creation request data:', $request->all());

        $validatedData = $request->validate([
            'course_code' => 'required|string|max:10|unique:courses,course_code',
            'course_name' => 'required|string|max:100',
            'course_desc' => 'nullable|string|max:500',
            'course_type' => 'required|in:public,private,semi_private,resort',
            'total_holes' => 'required|integer|min:9|max:36',
            'remarks' => 'nullable|string',
            'active' => 'sometimes|boolean',
        ]);

        return $this->courseService->store($validatedData);
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
