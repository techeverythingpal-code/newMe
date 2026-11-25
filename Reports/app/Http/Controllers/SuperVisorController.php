<?php

namespace App\Http\Controllers;

use App\Models\SuperVisor;
use App\Models\TeacherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SuperVisorController extends Controller
{
    public function index()
    {
        $supervisors = SuperVisor::withCount('teachers')->with('directorate')->get();
        return view('supervisors.index', compact('supervisors'));
    }

    public function show(SuperVisor $supervisor)
    {
        $supervisor->load('directorate');

        $teachers = TeacherInfo::with('grades')
            ->where('supervisor_id', $supervisor->SuperVisor_id)
            ->get();

        $totalTeachers = $teachers->count();
        $avgTotal      = $teachers->avg(fn($t) => $t->grades->total ?? 0);
        $highestScore  = $teachers->max(fn($t) => $t->grades->total ?? 0);
        $lowestScore   = $totalTeachers > 0
            ? $teachers->min(fn($t) => $t->grades->total ?? 0)
            : 0;

        return view('supervisors.show', compact(
            'supervisor',
            'teachers',
            'totalTeachers',
            'avgTotal',
            'highestScore',
            'lowestScore',
        ));
    }

    public function create()
    {
        $directorates = \App\Models\Directorate::all();
        return view('supervisors.create', compact('directorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'SuperVisor_id'    => 'required|integer|unique:super_visors,SuperVisor_id',
            'SuperVisor_Name'  => 'required|string|max:255|unique:super_visors',
            'SuperVisor_Major' => 'required|string|max:255',
            'directorate_id'   => 'required|exists:directorates,Directorate_id',
            'password'         => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        SuperVisor::create($validated);

        return redirect()->route('supervisors.index')
            ->with('success', 'تم إضافة المشرف بنجاح');
    }

    public function edit(SuperVisor $supervisor)
    {
        $directorates = \App\Models\Directorate::all();
        return view('supervisors.edit', compact('supervisor', 'directorates'));
    }

    public function update(Request $request, SuperVisor $supervisor)
    {
        $validated = $request->validate([
            'SuperVisor_id'    => 'required|integer|unique:super_visors,SuperVisor_id,' . $supervisor->SuperVisor_id . ',SuperVisor_id',
            'SuperVisor_Name'  => 'required|string|max:255|unique:super_visors,SuperVisor_Name,' . $supervisor->SuperVisor_id . ',SuperVisor_id',
            'SuperVisor_Major' => 'required|string|max:255',
            'directorate_id'   => 'required|exists:directorates,Directorate_id',
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'confirmed', Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $supervisor->update($validated);

        return redirect()->route('supervisors.index')
            ->with('success', 'تم تعديل المشرف بنجاح');
    }

    public function destroy(SuperVisor $supervisor)
    {
        // Only the admin (users table) reaches this page, so the admin can
        // never be one of these supervisors — no "can't delete yourself" check.

        if ($supervisor->teachers()->count() > 0) {
            return redirect()->route('supervisors.index')
                ->with('error', 'لا يمكن حذف هذا المشرف لأنه مرتبط بمعلمين');
        }

        $supervisor->delete();

        return redirect()->route('supervisors.index')
            ->with('success', 'تم حذف المشرف بنجاح');
    }
}