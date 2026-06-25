<?php

namespace App\Http\Controllers;

use App\Models\TeacherGrade;
use App\Models\TeacherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherGradeController extends Controller
{
    // Shared criteria definition: field => [label, max score]
    public static function scoreCriteria(): array
    {
        return [
            'score1'  => ['يوظف أسس المنهاج وخطوطه العريضة في العملية التعليمية', 5],
            'score2'  => ['يتمكن من ربط الأهداف العامة للمنهاج مع أهداف المرحلة', 7],
            'score3'  => ['يتمكن من المحتوى التعليمي ويعمل على إثرائه', 7],
            'score4'  => ['يتمكن من الربط العمودي والأفقي للمحتوى التعليمي', 7],
            'score5'  => ['يستخدم استراتيجيات تدريس متنوعة تتلاءم مع الموقف التعليمي', 7],
            'score6'  => ['يعد الخطط ويطورها وفق أسس علمية', 5],
            'score7'  => ['يوظف تكنولوجيا التعليم بأنواعها في العملية التعليمية', 4],
            'score8'  => ['يوظف المنصات التعليمية الإلكترونية لتعزيز تعلم الطلبة', 4],
            'score9'  => ['يوفر مناخاً تعليمياً آمناً وداعماً يمتاز بالمرونة والابتكار', 6],
            'score10' => ['يوظف القياس والتقويم التربوي بأنواعه', 6],
            'score11' => ['يكلف الطلبة بمهمات تعزز مهارات البحث العلمي', 4],
            'score12' => ['يتمكن من ربط المبحث بسياقات حياتية وتعليمية متنوعة', 6],
            'score13' => ['يوظف طرق التدريس بما ينسجم مع احتياجات الطلبة وقدراتهم', 6],
            'score14' => ['يدمج الطلبة ذوي الاحتياجات الخاصة في العملية التعليمية', 3],
            'score15' => ['يتمكن من أساسيات اللغة العربية والعلوم والرياضيات', 3],
            'score16' => ['يعمق معرفته بالمحتوى العلمي ويوجهها نحو زيادة الإلمام', 4],
            'score17' => ['يستثمر التغذية الراجعة في تطوير أدائه', 3],
            'score18' => ['يوظف مهارات الاتصال والتواصل في العملية التعليمية', 3],
            'score19' => ['يحرص على استمرارية نموه المهني ويعد أبحاثاً في تخصصه', 4],
            'score20' => ['يلتزم بالأنظمة والقوانين واللوائح التربوية', 2],
            'score21' => ['يشارك في اللجان المختلفة والأنشطة التربوية', 2],
            'score22' => ['يحرص على بناء علاقات إنسانية مع الأطراف ذات العلاقة', 2],
        ];
    }

    private function scoreValidationRules(): array
    {
        $rules = [];
        foreach (self::scoreCriteria() as $field => [$label, $max]) {
            $rules[$field] = "required|integer|min:0|max:{$max}";
        }
        return $rules;
    }

    // Excel-like grid: all teachers (scoped to supervisor) with grades, editable inline
    public function sheet()
    {
        if (! Auth::guard('admin')->check()) {
            $user = Auth::guard('web')->user();
            $teachers = TeacherInfo::with(['school', 'grades'])
                ->where('supervisor_id', $user->SuperVisor_id)
                ->orderBy('Teacher_Name')
                ->get();
        } else {
            $teachers = TeacherInfo::with(['school', 'grades'])
                ->orderBy('Teacher_Name')
                ->get();
        }

        $scores = self::scoreCriteria();

        return view('grades.sheet', compact('teachers', 'scores'));
    }

    // Auto-save one teacher's row from the sheet (AJAX)
    public function quickUpdate(Request $request, TeacherInfo $teacher)
    {
        if (! Auth::guard('admin')->check()
            && $teacher->supervisor_id !== Auth::guard('web')->user()->SuperVisor_id) {
            abort(403);
        }

        $validated = $request->validate($this->scoreValidationRules());
        $validated['total'] = array_sum($validated);

        $teacher->grades()->update($validated);

        return response()->json([
            'success' => true,
            'total'   => $validated['total'],
        ]);
    }

    public function edit(TeacherInfo $teacher)
    {
        $grades = $teacher->grades;
        return view('grades.edit', compact('teacher', 'grades'));
    }

    public function update(Request $request, TeacherInfo $teacher)
    {
        $validated = $request->validate($this->scoreValidationRules());

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