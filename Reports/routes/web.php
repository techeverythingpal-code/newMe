<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SuperVisorController;
use App\Http\Controllers\DirectorateController;
use App\Http\Controllers\TeacherInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Only admin can access these
Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('schools', SchoolController::class);
    Route::resource('supervisors', SuperVisorController::class);
    Route::resource('directorates', DirectorateController::class);
    });

    Route::middleware(['auth'])->group(function () {
    Route::resource('teachers', TeacherInfoController::class);
});
    

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
