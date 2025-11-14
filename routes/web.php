<?php

use App\Models\Scorecard;
use App\Models\ScorecardHoleHandicap;
use App\Models\Tournament;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\ScoreController;
use App\Http\Controllers\ScorecardController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\FormulaTypeController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\TournamentPlayerController;
use App\Models\Course;
use App\Models\FormulaType;
use App\Models\PlayerProfile;
use App\Models\Tee;
use App\Models\TournamentCourse;
use App\Models\TournamentPlayer;
use App\Services\ScoreMigrateService;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use NXP\MathExecutor;
use App\Http\Controllers\WhsHandicapIndexController;
use App\Models\Participant;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('test', function () {

    $tournamentId = 1;
    Score::where('tournament_id', $tournamentId)->get();

    DB::raw('SELECT scores.* FROM scores WHERE tournament_id = ' . $tournamentId);




    // $participants = Participant::with('user.profile', 'user.player', 'tournament', 'participantCourseHandicaps.course', 'participantCourseHandicaps.tee')
    //     ->leftJoin('users', 'participants.user_id', '=', 'users.id')
    //     ->leftJoin('tournaments', 'participants.tournament_id', '=', 'tournaments.tournament_id')
    //     ->leftJoin('player_profiles', 'participants.player_profile_id', '=', 'player_profiles.player_profile_id')
    //     ->leftJoin('whs_handicap_indexes', function ($join) {
    //         $join->on('participants.tournament_id', '=', 'whs_handicap_indexes.tournament_id')
    //             ->on('tournaments.whs_handicap_import_id', '=', 'whs_handicap_indexes.whs_handicap_import_id')
    //             ->on('player_profiles.whs_no', '=', 'whs_handicap_indexes.whs_no');
    //     })
    //     ->select('participants.*', 'users.*', 'tournaments.*', 'player_profiles.*', 'whs_handicap_indexes.whs_handicap_index', 'whs_handicap_indexes.final_whs_handicap_index')
    //     ->where('participants.tournament_id', 15)
    //     // ->where('participants.participant_id', 10)
    //     ->get();
    // echo '<pre>';
    // print_r($participants->toArray());
    // echo '</pre>';



    // return;


    $testService = new \App\Services\ImportCheckerService();
    $testService->test('Tournament.xlsx');
})->name('test');




Route::prefix('api')->group(function () {
    // API endpoints
    Route::get('courses', function () {
        $courses = Course::select('course_id', 'course_name', 'course_desc')->where('active', true)->get();
        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    });

    // Formula validation endpoint
    Route::post('tournaments/validate-local-handicap-formula', [App\Http\Controllers\Admin\TournamentController::class, 'validateTournamentHandicapFormula'])->name('tournaments.validate-formula');
    Route::post('tournaments/validate-course-handicap-formula', [App\Http\Controllers\Admin\TournamentController::class, 'validateCourseHandicapFormula'])->name('tournaments.validate-course-handicap-formula');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Player search route must come before resource routes to avoid conflicts
    Route::get('players/available', [PlayerController::class, 'getAvailablePlayers'])->name('players.available');
    Route::get('players/search', [PlayerController::class, 'search'])->name('players.search');
    Route::get('players/{player_id}/recent-scores', [PlayerController::class, 'getRecentScores'])->name('players.recent-scores');
    Route::post('players/import', [PlayerController::class, 'import'])->name('players.import');
    Route::resource('players', PlayerController::class);


    Route::post('scores/migrate', [ScoreController::class, 'migrate'])->name('scores.import');
    Route::resource('scores', ScoreController::class);
    Route::resource('scorecards', ScorecardController::class);

    Route::resource('formulas', FormulaController::class);
    Route::resource('formula-types', FormulaTypeController::class);
    // Settings page routes
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::post('settings/save', [\App\Http\Controllers\Admin\SettingsController::class, 'save'])->name('settings.save');

    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
    Route::get('courses/{course_id}/tees', [App\Http\Controllers\Admin\CourseController::class, 'getTees'])->name('tees.courses');

    Route::resource('tees', App\Http\Controllers\Admin\TeeController::class);
    Route::get('tees/{tee_id}/yardages', [App\Http\Controllers\Admin\TeeController::class, 'getYardages'])->name('tees.yardages');


    Route::resource('whs-handicap-indexes', App\Http\Controllers\WhsHandicapIndexController::class);

    Route::post('whs-handicap-imports/import', [WhsHandicapIndexController::class, 'import']);
    Route::resource('whs-handicap-imports', App\Http\Controllers\WhsHandicapImportController::class);

    Route::resource('tournaments', App\Http\Controllers\Admin\TournamentController::class);
    Route::get('tournaments/{tournament_id}/courses', [App\Http\Controllers\Admin\TournamentController::class, 'getCourses'])->name('tournaments.courses');


    Route::post('participant/calculate-handicap', [ParticipantController::class, 'calculateHandicap'])->name('participants.calculate-handicap');
    Route::post('participant/course', [ParticipantController::class, 'setCourseSelection'])->name('participant.course');
    Route::post('participant/handicap', [ParticipantController::class, 'updateHandicap'])->name('participant.handicap');
    Route::post('participants/import', [ParticipantController::class, 'import'])->name('participants.import');
    Route::get('participants/available', [ParticipantController::class, 'available'])->name('participants.available');
    Route::post('participants/add-bulk', [ParticipantController::class, 'addBulk'])->name('participants.add-bulk');
    Route::resource('participants', ParticipantController::class);
});
