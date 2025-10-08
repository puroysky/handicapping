<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('players', App\Http\Controllers\Admin\PlayerController::class);
    Route::resource('scores', App\Http\Controllers\Admin\ScoreController::class);
    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
});
