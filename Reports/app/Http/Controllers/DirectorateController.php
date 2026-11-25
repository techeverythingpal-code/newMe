<?php

namespace App\Http\Controllers;

use App\Models\Directorate;
use Illuminate\Http\Request;

class DirectorateController extends Controller
{
    /**
     * Validation rules for the optional detail fields,
     * shared by store() and update().
     */
    private function detailRules(): array
    {
        return [
            'address'      => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'notes'        => 'nullable|string|max:2000',
        ];
    }

    public function index()
    {
        $directorates = Directorate::withCount('schools')->get();
        return view('directorates.index', compact('directorates'));
    }

    public function show(Directorate $directorate)
    {
        // Schools and supervisors of this directorate, each with its teacher count
        $schools = $directorate->schools()
            ->withCount('teachers')
            ->orderBy('SchoolName')
            ->get();

        $supervisors = $directorate->supervisors()
            ->withCount('teachers')
            ->orderBy('SuperVisor_Name')
            ->get();

        $totalTeachers = $schools->sum('teachers_count');

        return view('directorates.show', compact(
            'directorate',
            'schools',
            'supervisors',
            'totalTeachers',
        ));
    }

    public function create()
    {
        return view('directorates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(array_merge([
            'Directorate_id'   => 'required|integer|unique:directorates',
            'Directorate_Name' => 'required|string|max:255',
        ], $this->detailRules()));

        Directorate::create($validated);

        return redirect()->route('directorates.index')
            ->with('success', 'تم إضافة المديرية بنجاح');
    }

    public function edit(Directorate $directorate)
    {
        return view('directorates.edit', compact('directorate'));
    }

    public function update(Request $request, Directorate $directorate)
    {
        $validated = $request->validate(array_merge([
            'Directorate_Name' => 'required|string|max:255',
        ], $this->detailRules()));

        $directorate->update($validated);

        return redirect()->route('directorates.show', $directorate->Directorate_id)
            ->with('success', 'تم تعديل المديرية بنجاح');
    }

    public function destroy(Directorate $directorate)
    {
        // A directorate that still has schools can't be deleted
        if ($directorate->schools()->count() > 0) {
            return redirect()->route('directorates.index')
                ->with('error', 'لا يمكن حذف هذه المديرية لأنها تحتوي على مدارس');
        }

        $directorate->delete();

        return redirect()->route('directorates.index')
            ->with('success', 'تم حذف المديرية بنجاح');
    }
}