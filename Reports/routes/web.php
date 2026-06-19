<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorateController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SuperVisorController;
use App\Http\Controllers\TeacherInfoController;
use App\Http\Controllers\TeacherGradeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin only
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('directorates', DirectorateController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('supervisors', SuperVisorController::class);
});

// Admin + Supervisor
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('teachers', TeacherInfoController::class);
    Route::get('teachers/{teacher}/grades/edit', [TeacherGradeController::class, 'edit'])->name('teacher-grades.edit');
    Route::patch('teachers/{teacher}/grades', [TeacherGradeController::class, 'update'])->name('teacher-grades.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';