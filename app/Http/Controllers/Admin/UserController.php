<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    protected $userService;


    public function __construct()
    {
        $this->userService = new \App\Services\UserService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->userService->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->userService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log all request data
        Log::info('User creation request data:', $request->all());

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,user',
            'active' => 'sometimes|boolean',
            'user_desc' => 'nullable|string|max:100',
            'birth_date' => 'required|date',
            'sex' => $request->role === 'user' ? 'required|in:MALE,FEMALE' : 'nullable|in:MALE,FEMALE',
            'birth_date' => $request->role === 'user' ? 'required|date' : 'nullable|date',
            'account_no' => $request->role === 'user' ? 'required|string|max:20|unique:player_profiles,account_no' : 'nullable|string|max:20|unique:player_profiles,account_no',
            'whs_no' => $request->role === 'user' ? 'required|string|max:10|unique:player_profiles,whs_no' : 'nullable|string|max:10|unique:player_profiles,whs_no',
            'remarks' => 'nullable|string',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        return $this->userService->store($validatedData);
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
