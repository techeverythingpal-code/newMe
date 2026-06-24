<?php

namespace App\Http\Controllers;

use App\Models\TeacherGrade;
use App\Models\TeacherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherGradeController extends Controller
{
    public function edit(TeacherInfo $teacher)
    {
        $grades = $teacher->grades;
        return view('grades.edit', compact('teacher', 'grades'));
    }

    public function update(Request $request, TeacherInfo $teacher)
    {
        $validated = $request->validate([
            'score1'  => 'required|integer|min:0|max:5',
            'score2'  => 'required|integer|min:0|max:7',
            'score3'  => 'required|integer|min:0|max:7',
            'score4'  => 'required|integer|min:0|max:7',
            'score5'  => 'required|integer|min:0|max:7',
            'score6'  => 'required|integer|min:0|max:5',
            'score7'  => 'required|integer|min:0|max:4',
            'score8'  => 'required|integer|min:0|max:4',
            'score9'  => 'required|integer|min:0|max:6',
            'score10' => 'required|integer|min:0|max:6',
            'score11' => 'required|integer|min:0|max:4',
            'score12' => 'required|integer|min:0|max:6',
            'score13' => 'required|integer|min:0|max:6',
            'score14' => 'required|integer|min:0|max:3',
            'score15' => 'required|integer|min:0|max:3',
            'score16' => 'required|integer|min:0|max:4',
            'score17' => 'required|integer|min:0|max:3',
            'score18' => 'required|integer|min:0|max:3',
            'score19' => 'required|integer|min:0|max:4',
            'score20' => 'required|integer|min:0|max:2',
            'score21' => 'required|integer|min:0|max:2',
            'score22' => 'required|integer|min:0|max:2',
        ]);

        // Calculate total automatically
        $validated['total'] = array_sum($validated);

        $teacher->grades()->update($validated);

        return redirect()->route('teachers.show', $teacher->Teacher_id)
            ->with('success', 'تم حفظ الدرجات بنجاح');
    }



    private function zeroedScores(): array
    {
        $data = ['total' => 0];

        for ($i = 1; $i <= 22; $i++) {
            $data["score{$i}"] = 0;
        }

        return $data;
    }

    // Reset a single teacher's scores to zero
    public function resetSingle(TeacherInfo $teacher)
    {
        // Supervisors may only reset their own teachers
        if (! Auth::guard('admin')->check()
            && $teacher->supervisor_id !== Auth::guard('web')->user()->SuperVisor_id) {
            abort(403);
        }

        $teacher->grades()->update($this->zeroedScores());

        return back()->with('success', 'تم حذف درجات المعلم بنجاح');
    }

    // Reset scores for ALL teachers belonging to the current supervisor
    public function resetAllForSupervisor()
    {
        $user = Auth::guard('web')->user();

        $teacherIds = TeacherInfo::where('supervisor_id', $user->SuperVisor_id)
            ->pluck('Teacher_id');

        TeacherGrade::whereIn('teacher_id', $teacherIds)->update($this->zeroedScores());

        return back()->with('success', 'تم حذف درجات جميع معلميك بنجاح');
    }
}