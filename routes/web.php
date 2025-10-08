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
    Route::resource('tees', App\Http\Controllers\Admin\TeeController::class);
});
