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
    public function index(Request $request)
    {
        // Regular supervisor → show their own dashboard
        if (! Auth::guard('admin')->check()) {
            $user = Auth::guard('web')->user();

            $allTeachers = TeacherInfo::with(['school', 'grades'])
                ->where('supervisor_id', $user->SuperVisor_id)
                ->get();

            $totalTeachers   = $allTeachers->count();
            $avgTotal        = $allTeachers->avg(fn($t) => $t->grades->total ?? 0);
            $highestScore    = $allTeachers->max(fn($t) => $t->grades->total ?? 0);
            $excellentCount  = $allTeachers->filter(fn($t) => ($t->grades->total ?? 0) >= 85)->count();

            

            $schools = School::whereIn('School_ID', $allTeachers->pluck('school_id'))->get();

            // Flatten everything the table/search needs into plain arrays for JS
            $supervisorName = Auth::guard('admin')->check()
                ? null
                : Auth::guard('web')->user()->SuperVisor_Name;

            $teachersData = $allTeachers->map(fn($t) => [
                'id'              => $t->Teacher_id,
                'name'            => $t->Teacher_Name,
                'school'          => $t->school->SchoolName ?? '-',
                'school_id'       => $t->school_id,
                'directorate'     => $t->school->directorate->Directorate_Name ?? '',
                'major'           => $t->teacher_major,
                'qualify'         => $t->teacher_qualify,
                'academic_year'   => $t->academic_year ?? '',
                'total'           => $t->grades->total ?? 0,
                'assessment'      => $t->grades->assessment ?? ['label' => '—', 'color' => 'gray'],
                'supervisor_note' => $t->supervisor_note ?? '',
                'scores'          => $t->grades
                    ? collect($t->grades->toArray())->only(array_keys(\App\Http\Controllers\TeacherGradeController::scoreCriteria()))
                    : [],
            ])->values();

            $scoreCriteria = collect(\App\Http\Controllers\TeacherGradeController::scoreCriteria())
                ->map(fn($c, $field) => ['field' => $field, 'label' => $c[0], 'max' => $c[1]])
                ->values();
            $scoreGroups = \App\Http\Controllers\TeacherGradeController::scoreGroups();

            return view('supervisor-dashboard', compact(
                'totalTeachers',
                'avgTotal',
                'highestScore',
                'excellentCount',
                'schools',
                'teachersData',
                'scoreCriteria',
                'scoreGroups'
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