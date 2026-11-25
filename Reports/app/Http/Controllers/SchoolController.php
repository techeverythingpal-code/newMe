<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Directorate;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Validation rules for the optional detail fields,
     * shared by store() and update().
     */
    private function detailRules(): array
    {
        return [
            'principal_name' => 'nullable|string|max:255',
            'address'        => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'notes'          => 'nullable|string|max:2000',
        ];
    }

    public function index()
    {
        $schools = School::with('directorate')->withCount('teachers')->get();
        return view('schools.index', compact('schools'));
    }

    public function show(School $school)
    {
        $school->load('directorate');

        // Teachers of this school, with their supervisor (one query, no N+1)
        $teachers = $school->teachers()
            ->with('supervisor')
            ->orderBy('Teacher_Name')
            ->get();

        // Number of different supervisors who have teachers in this school
        $supervisorsCount = $teachers->pluck('supervisor_id')->filter()->unique()->count();

        return view('schools.show', compact(
            'school',
            'teachers',
            'supervisorsCount',
        ));
    }

    public function create()
    {
        $directorates = Directorate::all();
        return view('schools.create', compact('directorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(array_merge([
            'School_ID'      => 'required|integer|unique:schools',
            'SchoolName'     => 'required|string|max:255',
            'directorate_id' => 'required|integer|exists:directorates,Directorate_id',
        ], $this->detailRules()));

        School::create($validated);

        return redirect()->route('schools.index')
            ->with('success', 'تم إضافة المدرسة بنجاح');
    }

    public function edit(School $school)
    {
        $directorates = Directorate::all();
        return view('schools.edit', compact('school', 'directorates'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate(array_merge([
            'SchoolName'     => 'required|string|max:255',
            'directorate_id' => 'required|integer|exists:directorates,Directorate_id',
        ], $this->detailRules()));

        $school->update($validated);

        return redirect()->route('schools.show', $school->School_ID)
            ->with('success', 'تم تعديل المدرسة بنجاح');
    }

    public function destroy(School $school)
    {
        // A school that still has teachers can't be deleted
        if ($school->teachers()->count() > 0) {
            return redirect()->route('schools.index')
                ->with('error', 'لا يمكن حذف هذه المدرسة لأنها تحتوي على معلمين');
        }

        $school->delete();

        return redirect()->route('schools.index')
            ->with('success', 'تم حذف المدرسة بنجاح');
    }
}