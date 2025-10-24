<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeeController extends Controller
{

    protected $teeService;


    public function __construct()
    {
        $this->teeService = new \App\Services\TeeService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tees.tees', [
            'tees' => \App\Models\Tee::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->teeService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log all request data
        Log::info('Tee creation request data:', $request->all());

        $validatedData = $request->validate([
            'tee_code' => 'required|string|max:10|unique:tees,tee_code',
            'tee_name' => 'required|string|max:100',
            'tee_desc' => 'nullable|string|max:500',
            'course_id' => 'required|exists:courses,course_id',
            'remarks' => 'nullable|string',
            'active' => 'sometimes|boolean',
        ]);

        return $this->teeService->store($validatedData);
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

    public function getYardages(Request $request, $teeId)
    {

        return $this->teeService->getYardages($request, $teeId);
    }
}
