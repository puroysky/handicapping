<?php

namespace App\Services;

use App\Models\Division;
use App\Models\Tournament;
use App\Models\TournamentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TournamentService
{

    public function index()
    {
        $tournaments = \App\Models\Tournament::with('tournamentCourses.course', 'tournamentCourses.scorecard')->get();

        $title = 'Tournaments';
        return view('admin.tournaments.tournaments', compact('tournaments', 'title'));
    }


    public function show($id)
    {
        $tournament = \App\Models\Tournament::where('tournament_id', $id)
            ->with('tournamentCourses.course', 'tournamentCourses.scorecard')
            ->first();

        return response()->json($tournament);
    }
    public function create()
    {
        $scorecards = \App\Models\Scorecard::where('active', true)->orderBy('scorecard_name')->get();
        $courses = \App\Models\Course::where('active', true)->orderBy('course_name')->get();
        $title = 'Create New Tournament';
        return view('admin.tournaments.create-tournament-form', compact('courses', 'title', 'scorecards'));
    }

    public function store($request)
    {



        $ranges = $this->validateTournamenttRanges($request);
        if (($ranges['success'] ?? false) === false) {
            return response()->json([
                'message' => $ranges['message']
            ], 422);
        }

        DB::beginTransaction();

        try {

            $tournament = $this->createTournament($request);


            $divisionData = $this->prepareDivisionData($request, $tournament);
            $this->storeTournamentCourses($tournament, $request['course_ids'], $request['course_scorecards']);
            $this->createDivisions($divisionData);

            DB::commit();

            return response()->json([
                'message' => 'Tournament created successfully',
                'tournament' => $tournament
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => 'Error creating tournament: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()

            ], 500);
        }
    }

    private function createTournament($request): Tournament
    {
        $tournament = new Tournament();
        $tournament->tournament_name = $request['tournament_name'];
        $tournament->tournament_desc = $request['tournament_desc'];
        $tournament->remarks = $request['remarks'];
        $tournament->tournament_start = $request['tournament_start'];
        $tournament->tournament_end = $request['tournament_end'];



        $tournament->score_diff_start_date = $request['score_diff_start_date'] ?? null;
        $tournament->score_diff_end_date = $request['score_diff_end_date'] ?? null;
        $tournament->recent_scores_count = $request['recent_scores_count'] ?? null;

        $tournament->scores_to_average = $request['scores_to_average'] ?? null;
        $tournament->handicap_formula_expression = $request['handicap_formula_expression'];
        $tournament->handicap_formula_desc = $request['handicap_formula_desc'] ?? null;
        $tournament->handicap_score_differential_config = json_encode($request['handicap_score_differential_config'] ?? []);
        $tournament->created_by = Auth::id();

        $tournament->save();


        return $tournament;
    }

    private function createDivisions($divisionData)
    {

        $division =  Division::insert($divisionData);

        return $division;
    }


    private function prepareDivisionData($request, $tournament)
    {
        $divisionData = [];


        Log::info('Preparing division data for tournament', [
            'tournament_id' => $tournament->tournament_id,
            'divisions' => $request['divisions']
        ]);
        foreach ($request['divisions'] as $division) {
            $divisionData[] = [

                'tournament_id' => $tournament->tournament_id,
                'division_name' => $division['name'],
                'division_desc' => $division['description'] ?? null,
                'division_sex' => $division['sex'],
                'division_participant_type' => $division['participant_type'],
                'age_min' => $division['age_min'] ?? null,
                'age_max' => $division['age_max'] ?? null,
                'handicap_index_min' => $division['handicap_min'] ?? null,
                'handicap_index_max' => $division['handicap_max'] ?? null,
                'created_by' => Auth::id(),
            ];
        }

        return $divisionData;
    }


    private function validateTournamenttRanges($request)
    {



        $validConfig =  $this->validateRanges($request['handicap_score_differential_config'] ?? [], [
            'min' => 'min',
            'max' => 'max'
        ]);






        if (($validConfig['success'] ?? false) === false) {
            return $validConfig;
        }




        return [
            'success' => true,
            'message' => 'Ranges are valid.'
        ];
    }



    private function storeTournamentCourses($tournament, $courseIds, $scorecardIds)
    {
        foreach ($courseIds as $courseId) {
            $tournament->tournamentcourses()->create([
                'tournament_id' => $tournament->tournament_id,
                'scorecard_id' => $scorecardIds[$courseId],
                'course_id' => $courseId,
                'created_by' => Auth::id()
            ]);
        }
    }

    public function getCourses($request, $tournamentId)
    {

        try {
            $courses = TournamentCourse::with('course')->where('tournament_id', $tournamentId)
                ->where('active', true)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Courses fetched successfully',
                'courses' => $courses
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching tournament courses: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching tournament courses',
            ], 500);
        }
    }


    private function validateRanges($data, $minMaxFieldConfig): array
    {

        usort($data, fn($a, $b) => $a[$minMaxFieldConfig['min']] <=> $b[$minMaxFieldConfig['min']]);
        for ($i = 1; $i < count($data); $i++) {
            if ($data[$i][$minMaxFieldConfig['min']] <= $data[$i - 1][$minMaxFieldConfig['max']]) {
                return [
                    'success' => false,
                    'message' => "Handicap score differential config ranges should not overlap. Overlap found between ranges {$data[$i - 1][$minMaxFieldConfig['min']]}-{$data[$i - 1][$minMaxFieldConfig['max']]} and {$data[$i][$minMaxFieldConfig['min']]}-{$data[$i][$minMaxFieldConfig['max']]}."
                ];
            }
            if ($data[$i][$minMaxFieldConfig['min']] != $data[$i - 1][$minMaxFieldConfig['max']] + 1) {
                return [
                    'success' => false,
                    'message' => "Handicap score differential config ranges should not have gaps. Gap found between ranges {$data[$i - 1][$minMaxFieldConfig['min']]}-{$data[$i - 1][$minMaxFieldConfig['max']]} and {$data[$i][$minMaxFieldConfig['min']]}-{$data[$i][$minMaxFieldConfig['max']]}."
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Ranges are valid.'
        ];
    }
}
