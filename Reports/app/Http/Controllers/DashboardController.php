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

         // Full, unfiltered set — used ONLY for the charts
            $allTeachers = TeacherInfo::with(['school', 'grades'])
                ->where('supervisor_id', $user->SuperVisor_id)
                ->get();


            $totalTeachers = $allTeachers->count();
            $avgTotal      = $allTeachers->avg(fn($t) => $t->grades->total ?? 0);
            $highestScore  = $allTeachers->max(fn($t) => $t->grades->total ?? 0);

             $chartLabels = $allTeachers->pluck('Teacher_Name');
            $chartData   = $allTeachers->map(fn($t) => $t->grades->total ?? 0);

            $scoreMax = [
                'score1' => 5, 'score2' => 7, 'score3' => 7, 'score4' => 7, 'score5' => 7,
                'score6' => 5, 'score7' => 4, 'score8' => 4, 'score9' => 6, 'score10' => 6,
                'score11' => 4, 'score12' => 6, 'score13' => 6, 'score14' => 3, 'score15' => 3,
                'score16' => 4, 'score17' => 3, 'score18' => 3, 'score19' => 4, 'score20' => 2,
                'score21' => 2, 'score22' => 2,
            ];

            // Schools dropdown options (only schools this supervisor actually has teachers in)
            $schools = School::whereIn('School_ID', $allTeachers->pluck('school_id'))->get();

        // Filtered + paginated query — used for the table only
            $query = TeacherInfo::with(['school', 'grades'])
                ->where('supervisor_id', $user->SuperVisor_id);

         if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('Teacher_Name', 'like', "%{$search}%")
                      ->orWhere('teacher_major', 'like', "%{$search}%")
                      ->orWhere('teacher_qualify', 'like', "%{$search}%");
                });
            }

         if ($request->filled('school_id')) {
                $query->where('school_id', $request->school_id);
            }

            if ($request->filled('min_score')) {
                $query->whereHas('grades', fn($q) => $q->where('total', '>=', $request->min_score));
            }

            if ($request->filled('max_score')) {
                $query->whereHas('grades', fn($q) => $q->where('total', '<=', $request->max_score));
            }

        $teachers = $query->paginate(10)->withQueryString();

            return view('supervisor-dashboard', compact(
                'teachers',
                'totalTeachers',
                'avgTotal',
                'highestScore',
                'chartLabels',
                'chartData',
                'schools'
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