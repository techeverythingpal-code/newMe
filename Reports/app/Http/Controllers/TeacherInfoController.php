<?php

namespace App\Http\Controllers;

use App\Models\TeacherInfo;
use App\Models\TeacherGrade;
use App\Models\School;
use App\Models\SuperVisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherInfoController extends Controller
{
    public function index()
{
   if (! Auth::guard('admin')->check()) {
        // Supervisor sees only their teachers
        $user = Auth::guard('web')->user();
        $teachers = TeacherInfo::with(['school', 'supervisor', 'grades'])
            ->where('supervisor_id', $user->SuperVisor_id)
            ->get();
    } else {
        // Admin sees all teachers
        $teachers = TeacherInfo::with(['school', 'supervisor', 'grades'])->get();
    }

    return view('teachers.index', compact('teachers'));
}

    public function create()
    {
        $schools     = School::all();
        $supervisors = SuperVisor::all();
        return view('teachers.create', compact('schools', 'supervisors'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'Teacher_id'      => 'required|integer|unique:teacher_infos,Teacher_id',
        'Teacher_Name'    => 'required|string|max:255',
        'supervisor_id'   => 'required|integer|exists:super_visors,SuperVisor_id',
        'school_id'       => 'required|integer|exists:schools,School_ID',
        'date'            => 'required|date',
        'teacher_qualify' => 'required|string|max:255',
        'teacher_major'   => 'required|string|max:255',
    ]);

    // If regular supervisor, force assign to themselves
    if (! Auth::guard('admin')->check()) {
        $validated['supervisor_id'] = Auth::guard('web')->user()->SuperVisor_id;
    }

    TeacherInfo::create($validated);

    TeacherGrade::create([
        'teacher_id' => $validated['Teacher_id'],
        'score1' => 0, 'score2' => 0, 'score3' => 0, 'score4' => 0,
        'score5' => 0, 'score6' => 0, 'score7' => 0, 'score8' => 0,
        'score9' => 0, 'score10' => 0, 'score11' => 0, 'score12' => 0,
        'score13' => 0, 'score14' => 0, 'score15' => 0, 'score16' => 0,
        'score17' => 0, 'score18' => 0, 'score19' => 0, 'score20' => 0,
        'score21' => 0, 'score22' => 0, 'total' => 0,
    ]);

    return redirect()->route('teachers.index')
        ->with('success', 'تم إضافة المعلم بنجاح');
}

    public function show(TeacherInfo $teacher)
    {
        $teacher->load(['school', 'supervisor', 'grades']);
        return view('teachers.show', compact('teacher'));
    }

    public function edit(TeacherInfo $teacher)
    {
        $schools     = School::all();
        $supervisors = SuperVisor::all();
        return view('teachers.edit', compact('teacher', 'schools', 'supervisors'));
    }

    public function update(Request $request, TeacherInfo $teacher)
    {
        $validated = $request->validate([
            'Teacher_Name'    => 'required|string|max:255',
            'supervisor_id'   => 'required|integer|exists:super_visors,SuperVisor_id',
            'school_id'       => 'required|integer|exists:schools,School_ID',
            'date'            => 'required|date',
            'teacher_qualify' => 'required|string|max:255',
            'teacher_major'   => 'required|string|max:255',
        ]);

        $teacher->update($validated);

        return redirect()->route('teachers.index')
            ->with('success', 'تم تعديل بيانات المعلم بنجاح');
    }

    public function destroy(TeacherInfo $teacher)
    {
        // Delete grades first then teacher
        $teacher->grades()->delete();
        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'تم حذف المعلم بنجاح');
    }
}