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
use App\Models\FormulaType;
use App\Models\PlayerProfile;
use App\Models\Tee;
use App\Models\TournamentCourse;
use App\Models\TournamentPlayer;
use Illuminate\Support\Facades\Log;
use NXP\MathExecutor;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('test', function () {



    $playerInfo = PlayerProfile::get()->keyBy('account_no');

    // echo '<pre>';
    // print_r($playerInfo->toArray());
    // echo '</pre>';

    // return;


    $tournamentCourses = TournamentCourse::where('tournament_id', 1)->get();
    $tees = Tee::with('course')
        ->whereIn('course_id', $tournamentCourses->pluck('course_id'))
        ->get();

    $courseTees = [];
    foreach ($tees as $tee) {
        $courseCode = $tee->course->course_code;
        $teeId = $tee->tee_id;
        $teeCode = $tee->tee_code;
        $courseTees[$courseCode]['course_id'] = $tee->course->course_id;
        $courseTees[$courseCode]['tees'][$teeCode] = $teeId;
    }


    echo '<pre>';
    print_r($courseTees);
    echo '</pre>';

    return;



    $participantCourseHandicaps = TournamentCourse::where('tournament_id', 2)->get();

    $tees = Tee::with('course')->whereIn('course_id', $participantCourseHandicaps->pluck('course_id'))->get();

    $courses = [];
    foreach ($tees as $tee) {
        $courses[$tee->course->course_code][$tee->tee_id] = $tee->tee_code;
    }
    // echo '<pre>';
    // print_r($participantCourseHandicaps->toArray());
    // echo '</pre>';

    echo '<pre>';
    print_r($courses);
    echo '</pre>';


    // echo '<pre>';
    // print_r($participantCourseHandicaps->toArray());
    // echo '</pre>';
})->name('test');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Player search route must come before resource routes to avoid conflicts
    Route::get('players/search', [PlayerController::class, 'search'])->name('players.search');
    Route::get('players/{player_id}/recent-scores', [PlayerController::class, 'getRecentScores'])->name('players.recent-scores');
    Route::post('players/import', [PlayerController::class, 'import'])->name('players.import');
    Route::resource('players', PlayerController::class);

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

    Route::resource('tournaments', App\Http\Controllers\Admin\TournamentController::class);
    Route::get('tournaments/{tournament_id}/courses', [App\Http\Controllers\Admin\TournamentController::class, 'getCourses'])->name('tournaments.courses');

    Route::post('participants/import', [ParticipantController::class, 'import'])->name('participants.import');
    Route::resource('participants', ParticipantController::class);
});
