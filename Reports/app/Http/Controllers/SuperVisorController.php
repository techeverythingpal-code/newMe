<?php

namespace App\Http\Controllers;

use App\Models\SuperVisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SuperVisorController extends Controller
{
    public function index()
    {
        $supervisors = SuperVisor::withCount('teachers')->with('directorate')->get();
    return view('supervisors.index', compact('supervisors'));
}
    }

    public function create()
    {
        $directorates = \App\Models\Directorate::all();
    return view('supervisors.create', compact('directorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
    'SuperVisor_Name'  => 'required|string|max:255|unique:super_visors',
    'SuperVisor_Major' => 'required|string|max:255',
    'directorate_id'   => 'nullable|exists:directorates,Directorate_id',
    'role'             => 'required|in:admin,user',
    'password'         => 'required|string|min:6|confirmed',
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
    'SuperVisor_Name'  => 'required|string|max:255|unique:super_visors,SuperVisor_Name,' . $supervisor->SuperVisor_id . ',SuperVisor_id',
    'SuperVisor_Major' => 'required|string|max:255',
    'directorate_id'   => 'nullable|exists:directorates,Directorate_id',
    'role'             => 'required|in:admin,user',
]);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $supervisor->update($validated);

        return redirect()->route('supervisors.index')
            ->with('success', 'تم تعديل المشرف بنجاح');
    }

    public function destroy(SuperVisor $supervisor)
    {
        // Prevent deleting yourself
        if ($supervisor->SuperVisor_id === Auth::id()) {
            return redirect()->route('supervisors.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        if ($supervisor->teachers()->count() > 0) {
            return redirect()->route('supervisors.index')
                ->with('error', 'لا يمكن حذف هذا المشرف لأنه مرتبط بمعلمين');
        }

        $supervisor->delete();

        return redirect()->route('supervisors.index')
            ->with('success', 'تم حذف المشرف بنجاح');
    }
}