<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => 'auth'], function () {
    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::post('schedule', [ScheduleController::class, 'store']);
    Route::get('schedule/{schedule}', [ScheduleController::class, 'show']);
    Route::put('schedule/{schedule}', [ScheduleController::class, 'update']);
    Route::delete('schedule/{schedule}', [ScheduleController::class, 'destroy']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
