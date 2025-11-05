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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('test', function () {



    // $tournament = Tournament::with('tournamentCourses.scorecard.scoreDifferentialFormula', 'tournamentCourses.scorecard.ratings')->find(1);
    // $tournament->setRelation('tournamentCourses', $tournament->tournamentCourses->keyBy('course_id'));

    // // Key the ratings by rating_id for each tournament course
    // foreach ($tournament->tournamentCourses as $tournamentCourse) {
    //     if ($tournamentCourse->scorecard && $tournamentCourse->scorecard->ratings) {
    //         $tournamentCourse->scorecard->setRelation('ratings', $tournamentCourse->scorecard->ratings->keyBy('tee_id'));
    //     }
    // }








    // echo '<pre>';
    // print_r($tournament->tournamentCourses[1]->scorecard->ratings[2]->toArray());
    // echo '</pre>';
    // return;
    // $scorecard = Scorecard::with('scoreDifferentialFormula')->find(1);
    // // $players = PlayerProfile::all()->keyBy('account_no');
    // echo '<pre>';
    // print_r($scorecard->scoreDifferentialFormula->formula_expression);
    // echo '</pre>';
    // return;
    // return view('admin.tests', [
    //     'courses' => Course::all()
    // ]);


    $file = storage_path('Tournament.xlsx');

    $data = Excel::toArray([], $file)[0];

    // Check if file has data
    if (empty($data) || count($data) < 2) {
        return [
            'success' => false,
            'message' => 'File is empty or has no data rows.'
        ];
    }

    // Extract header and validate required columns
    $header = array_map('strtolower', array_map('trim', $data[0]));
    $requiredColumns = ['account_no', 'adjusted_gross_score', 'slope_rating', 'course_rating', 'holes_completed', 'date_played', 'tee_id', 'course_id', 'tournament_name'];

    foreach ($requiredColumns as $column) {
        if (!in_array($column, $header)) {
            return [
                'success' => false,
                'message' => "Missing required column: {$column}. Required columns: " . implode(', ', $requiredColumns)
            ];
        }
    }



    $newFormat = [];
    foreach ($data as $index => $row) {

        if ($index === 0) {
            continue;
        }

        $newFormat[$row[8]][$row[7]][$row[9]][$row[5]][$row[3] . '_' . $row[4]][] = $row;
    }
    $errors = [];

    foreach ($newFormat as $course => $courses) {
        foreach ($courses as $tee => $tees) {
            foreach ($tees as $tournament => $tournaments) {
                foreach ($tournaments as $holePlayed => $holesPlayed) {
                    if (count($holesPlayed) > 1) {
                        $errors[] = array(
                            "course" => $course,
                            "tournament" => $tournament,
                            'tee' => $tee,
                            'holes_played' => $holePlayed,
                            'multiple_slope_course_ratings' => array_keys($holesPlayed)

                        );
                    } else {
                        $goods[$tournament][$course][] = array(
                            "course" => $course,
                            "tournament" => $tournament,
                            'tee' => $tee,
                            'holes_played' => $holePlayed,
                            'slope_course_rating' => array_keys($holesPlayed)

                        );
                    }
                }
            }
        }
    }


    // echo '<pre>';
    // print_r($goods);

    // echo '</pre>';

    echo '<pre>';
    print_r($errors);

    echo '</pre>';

    return;


    echo '<pre>';
    print_r($newFormat);
    echo '<pre>';
    return;
})->name('test');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Player search route must come before resource routes to avoid conflicts
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



    Route::resource('tournaments', App\Http\Controllers\Admin\TournamentController::class);
    Route::get('tournaments/{tournament_id}/courses', [App\Http\Controllers\Admin\TournamentController::class, 'getCourses'])->name('tournaments.courses');

    Route::post('participants/import', [ParticipantController::class, 'import'])->name('participants.import');
    Route::resource('participants', ParticipantController::class);
});
