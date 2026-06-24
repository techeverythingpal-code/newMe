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

            $totalTeachers = $allTeachers->count();
            $avgTotal      = $allTeachers->avg(fn($t) => $t->grades->total ?? 0);
            $highestScore  = $allTeachers->max(fn($t) => $t->grades->total ?? 0);

            

            $schools = School::whereIn('School_ID', $allTeachers->pluck('school_id'))->get();

            // Flatten everything the table/search needs into plain arrays for JS
            $teachersData = $allTeachers->map(fn($t) => [
                'id'        => $t->Teacher_id,
                'name'      => $t->Teacher_Name,
                'school'    => $t->school->SchoolName ?? '-',
                'school_id' => $t->school_id,
                'major'     => $t->teacher_major,
                'qualify'   => $t->teacher_qualify,
                'total'     => $t->grades->total ?? 0,
            ])->values();

            return view('supervisor-dashboard', compact(
                'totalTeachers',
                'avgTotal',
                'highestScore',
                'chartLabels',
                'chartData',
                'schools',
                'teachersData'
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