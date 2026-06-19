<?php

namespace App\Http\Controllers;

use App\Models\TeacherInfo;
use App\Models\School;
use App\Models\SuperVisor;
use App\Models\Directorate;
use App\Models\TeacherGrade;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // If regular supervisor → show their own dashboard
        if ($user->role === 'user') {
            $teachers = TeacherInfo::with(['school', 'grades'])
                ->where('supervisor_id', $user->SuperVisor_id)
                ->get();

            $totalTeachers = $teachers->count();
            $avgTotal      = $teachers->avg(fn($t) => $t->grades->total ?? 0);
            $highestScore  = $teachers->max(fn($t) => $t->grades->total ?? 0);

            return view('supervisor-dashboard', compact(
                'teachers',
                'totalTeachers',
                'avgTotal',
                'highestScore'
            ));
        }

        // Admin dashboard
        $totalTeachers     = TeacherInfo::count();
        $totalSchools      = School::count();
        $totalSupervisors  = SuperVisor::count();
        $totalDirectorates = Directorate::count();

        $recentTeachers = TeacherInfo::with(['school', 'supervisor'])
            ->latest()
            ->take(5)
            ->get();

        $avgTotal     = TeacherGrade::avg('total') ?? 0;
        $highestScore = TeacherGrade::max('total') ?? 0;
        $lowestScore  = TeacherGrade::min('total') ?? 0;

        $teachersPerSchool = School::withCount('teachers')
            ->orderByDesc('teachers_count')
            ->take(5)
            ->get();

        $teachersPerSupervisor = SuperVisor::withCount('teachers')
            ->orderByDesc('teachers_count')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalTeachers',
            'totalSchools',
            'totalSupervisors',
            'totalDirectorates',
            'recentTeachers',
            'avgTotal',
            'highestScore',
            'lowestScore',
            'teachersPerSchool',
            'teachersPerSupervisor'
        ));
    }
}