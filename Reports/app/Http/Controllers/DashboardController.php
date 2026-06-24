<?php

namespace App\Http\Controllers;

use App\Models\TeacherInfo;
use App\Models\School;
use App\Models\SuperVisor;
use App\Models\Directorate;
use App\Models\TeacherGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Regular supervisor → show their own dashboard
        if (! Auth::guard('admin')->check()) {
            $user = Auth::guard('web')->user();

            $teachers = TeacherInfo::with(['school', 'grades'])
                ->where('supervisor_id', $user->SuperVisor_id)
                ->get();

            $totalTeachers = $teachers->count();
            $avgTotal      = $teachers->avg(fn($t) => $t->grades->total ?? 0);
            $highestScore  = $teachers->max(fn($t) => $t->grades->total ?? 0);

            $chartLabels = $teachers->pluck('Teacher_Name');
            $chartData   = $teachers->map(fn($t) => $t->grades->total ?? 0);

            $scoreMax = [
                'score1' => 5, 'score2' => 7, 'score3' => 7, 'score4' => 7, 'score5' => 7,
                'score6' => 5, 'score7' => 4, 'score8' => 4, 'score9' => 6, 'score10' => 6,
                'score11' => 4, 'score12' => 6, 'score13' => 6, 'score14' => 3, 'score15' => 3,
                'score16' => 4, 'score17' => 3, 'score18' => 3, 'score19' => 4, 'score20' => 2,
                'score21' => 2, 'score22' => 2,
            ];

             $radarLabels = [];
            $radarData   = [];


            foreach ($scoreMax as $field => $max) {
                $values = $teachers
                    ->map(fn($t) => $t->grades?->{$field})
                    ->filter(fn($v) => $v !== null);

                $avgPercent = $values->count() > 0
                    ? round(($values->avg() / $max) * 100, 1)
                    : 0;

                $radarLabels[] = (int) str_replace('score', '', $field);
                $radarData[]   = $avgPercent;
            }




            return view('supervisor-dashboard', compact(
                'teachers',
                'totalTeachers',
                'avgTotal',
                'highestScore',
                'chartLabels',
                'chartData',
                'radarLabels',
                'radarData'
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