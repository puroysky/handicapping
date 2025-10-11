<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('sample-table', function () {
    return view('examples.table-usage');
})->name('sample-table');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('players', App\Http\Controllers\Admin\PlayerController::class);
    Route::resource('scores', App\Http\Controllers\Admin\ScoreController::class);

    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
    Route::get('courses/{course_id}/tees', [App\Http\Controllers\Admin\CourseController::class, 'getTees'])->name('tees.courses');

    Route::resource('tees', App\Http\Controllers\Admin\TeeController::class);
    Route::get('tees/{tee_id}/yardages', [App\Http\Controllers\Admin\TeeController::class, 'getYardages'])->name('tees.yardages');

    Route::resource('tournaments', App\Http\Controllers\Admin\TournamentController::class);
    Route::get('tournaments/{tournament_id}/courses', [App\Http\Controllers\Admin\TournamentController::class, 'getCourses'])->name('tournaments.courses');
});
