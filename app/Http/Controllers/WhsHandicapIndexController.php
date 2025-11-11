<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\WhsHandicapImport;
use App\Models\WhsHandicapIndex;
use App\Services\WhsHandicapImportService;
use Illuminate\Http\Request;

class WhsHandicapIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $tournamentId = request('tournament_id');


        $tournament = Tournament::findOrFail($tournamentId);



        $whsHandicapIndexes = WhsHandicapIndex::leftJoin('player_profiles', 'whs_handicap_indexes.whs_no', '=', 'player_profiles.whs_no')
            ->leftJoin('user_profiles', 'player_profiles.user_profile_id', '=', 'user_profiles.user_profile_id')
            ->where('whs_handicap_import_id', $tournament->whs_handicap_import_id)
            ->orderBy('player_profiles.account_no', 'asc')
            ->select('whs_handicap_indexes.*', 'user_profiles.first_name', 'user_profiles.last_name', 'player_profiles.account_no')
            // ->limit(100)
            ->get();


        // echo '<pre>';
        // print_r($whsHandicapIndexes->toArray());
        // echo '</pre>';
        // exit;



        return view('admin.whs-handicap-indexes.whs-handicap-indexes', compact('whsHandicapIndexes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function import(Request $request)
    {

        $request->validate([
            'tournament_id' => 'required|exists:tournaments,tournament_id,status,active',
            'whs_import_file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $importService = new WhsHandicapImportService();

        return $importService->import($request);
    }
}
