<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Directorate;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::with('directorate')->get();
        return view('schools.index', compact('schools'));
    }

    public function create()
    {
        $directorates = Directorate::all();
        return view('schools.create', compact('directorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'School_ID'        => 'required|integer|unique:schools',
            'SchoolName'       => 'required|string|max:255',
            'directorate_id'   => 'required|integer|exists:directorates,Directorate_id',
        ]);
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
        $validated = $request->validate([
            'SchoolName'     => 'required|string|max:255',
            'directorate_id' => 'required|integer|exists:directorates,Directorate_id',
        ]);
        $school->update($validated);
        return redirect()->route('schools.index')
            ->with('success', 'تم تعديل المدرسة بنجاح');
    }

    public function destroy(School $school)
    {
        if ($school->teachers()->count() > 0) {
            return redirect()->route('schools.index')
                ->with('error', 'لا يمكن حذف هذه المدرسة لأنها تحتوي على معلمين');
        }
        $school->delete();
        return redirect()->route('schools.index')
            ->with('success', 'تم حذف المدرسة بنجاح');
    }
}