<?php

namespace App\Http\Controllers;

use App\Models\TeacherGrade;
use App\Models\TeacherInfo;
use Illuminate\Http\Request;

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
}