<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorateController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SuperVisorController;
use App\Http\Controllers\TeacherInfoController;
use App\Http\Controllers\TeacherGradeController;
use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin only
Route::middleware(['auth:admin,web', 'admin'])->group(function () {
    Route::get('schools/export', [ExcelController::class, 'exportSchools'])->name('schools.export');
    Route::post('schools/import', [ExcelController::class, 'importSchools'])->name('schools.import');
    Route::get('supervisors/export', [ExcelController::class, 'exportSupervisors'])->name('supervisors.export');
    Route::post('supervisors/import', [ExcelController::class, 'importSupervisors'])->name('supervisors.import');

    Route::resource('directorates', DirectorateController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('supervisors', SuperVisorController::class);
});

// Admin + Supervisor
Route::middleware(['auth:admin,web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('teachers/export', [ExcelController::class, 'exportTeachers'])->name('teachers.export');
    Route::post('teachers/import', [ExcelController::class, 'importTeachers'])->name('teachers.import');

    Route::get('teachers/grades-sheet', [TeacherGradeController::class, 'sheet'])->name('teacher-grades.sheet');
    Route::resource('teachers', TeacherInfoController::class);
    Route::patch('teachers/{teacher}/grades/quick', [TeacherGradeController::class, 'quickUpdate'])->name('teacher-grades.quick-update');
    Route::get('teachers/{teacher}/grades/edit', [TeacherGradeController::class, 'edit'])->name('teacher-grades.edit');
    Route::get('teachers/{teacher}/justification', [TeacherInfoController::class, 'justification'])->name('teachers.justification');
    Route::post('teachers/{teacher}/justification', [TeacherInfoController::class, 'storeJustification'])->name('teachers.justification.store');
    Route::patch('teachers/{teacher}/supervisor-note', [TeacherInfoController::class, 'updateSupervisorNote'])->name('teachers.supervisor-note.update');
    Route::get('teachers/{teacher}/report', [TeacherGradeController::class, 'printReport'])->name('teacher-grades.print');
    Route::patch('teachers/{teacher}/grades', [TeacherGradeController::class, 'update'])->name('teacher-grades.update');
    Route::delete('teachers/{teacher}/grades/reset', [TeacherGradeController::class, 'resetSingle'])->name('teacher-grades.reset-single');
    Route::delete('teachers-grades/reset-all', [TeacherGradeController::class, 'resetAllForSupervisor'])->name('teacher-grades.reset-all');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';