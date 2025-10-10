<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TournamentService;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class TournamentController extends Controller
{


    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->tournamentService->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->tournamentService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validate the request data
        $validatedData = $request->validate([
            'tournament_name' => 'required|string|max:100',
            'tournament_desc' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'tournament_start' => 'required|date',
            'tournament_end' => 'required|date|after_or_equal:tournament_start',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,course_id',
        ]);

        return $this->tournamentService->store($validatedData);
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

    public function getCourses(Request $request, $tournamentId)
    {
        return $this->tournamentService->getCourses($request, $tournamentId);
    }
}
