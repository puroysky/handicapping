<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\PlayerProfile;
use App\Models\Tournament;
use App\Models\Participat;
use App\Services\ParticipantImportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParticipantController extends Controller
{



    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $players = PlayerProfile::with('userProfile')->get();
        $tournament = Tournament::with('tournamentCourses.course.tees')->find($request->id); // Example to get a tournament


        // echo '<pre>';
        // print_r($tournament->toArray());
        // echo '</pre>';
        // return;

        return view('admin.tournaments.create-participant-form', compact('tournament', 'players'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {



        DB::beginTransaction();
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'tournament_id' => 'required|exists:tournaments,tournament_id',
                'player_profile_id' => 'required|exists:player_profiles,player_profile_id',
                'whs_handicap_index' => 'nullable|numeric|min:0|max:54',
                'tournament_course_tee' => 'required|array',
                'tournament_course_tee.*' => 'required|numeric',
                'remarks' => 'nullable|string|max:1000',
            ]);

            // Find the tournament by name since tournament_id is now a string (tournament name)
            $tournament = Tournament::where('tournament_id', $validatedData['tournament_id'])->first();

            if ($tournament->participants()->where('player_profile_id', $validatedData['player_profile_id'])->exists()) {
                return response()->json(['message' => 'Player is already registered for this tournament.'], 422);
            }

            $playerProfile = PlayerProfile::find($validatedData['player_profile_id']);


            // echo '<pre>';
            // print_r($tournament->toArray());
            // echo '</pre>';
            // return;

            // Create a new Participat record
            $tournamentPlayer = new Participant();
            $tournamentPlayer->tournament_id = $tournament->tournament_id; // Use the actual tournament ID
            $tournamentPlayer->player_profile_id = $validatedData['player_profile_id'];
            $tournamentPlayer->user_id = $playerProfile->user_id;
            $tournamentPlayer->whs_handicap_index = $validatedData['whs_handicap_index'] ?? null;
            $tournamentPlayer->remarks = $validatedData['remarks'] ?? null;
            $tournamentPlayer->created_by = Auth::id() ?? 1; // Use authenticated user ID or default to 1
            $tournamentPlayer->save();

            // Save player course handicaps
            foreach ($validatedData['tournament_course_tee'] as $courseId => $teeId) {

                $tournamentPlayer->participantCourseHandicaps()->create([
                    'tournament_id' => $tournament->tournament_id,
                    'tournament_player_id' => $tournamentPlayer->tournament_player_id,
                    'course_id' => $courseId,
                    'tee_id' => $teeId,
                    'created_by' => Auth::id() ?? 1,
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Player added to tournament successfully.'], 201);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error adding player to tournament: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json(['success' => false, 'message' => 'An error occurred while adding the player to the tournament.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tournament = Tournament::find($id);
        $players = Participant::with('user.profile', 'user.player', 'tournament', 'participantCourseHandicaps.course', 'participantCourseHandicaps.tee')->where('tournament_id', $id)->get();
        // echo '<pre>';
        // print_r($players->toArray());
        // return;
        return view('admin.tournaments.participants', ['players' => $players, 'tournamentId' => $id, 'tournament' => $tournament]);
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

        $importService = new ParticipantImportService();
        return $importService->import($request);
    }
}
