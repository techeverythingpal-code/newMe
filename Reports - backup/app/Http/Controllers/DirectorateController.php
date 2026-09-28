<?php

namespace App\Http\Controllers;

use App\Models\Directorate;
use Illuminate\Http\Request;

class DirectorateController extends Controller
{
    public function index()
    {
        $directorates = Directorate::withCount('schools')->get();
        return view('directorates.index', compact('directorates'));
    }

    public function create()
    {
        return view('directorates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Directorate_id'   => 'required|integer|unique:directorates',
            'Directorate_Name' => 'required|string|max:255',
        ]);
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
        $validated = $request->validate([
            'Directorate_Name' => 'required|string|max:255',
        ]);
        $directorate->update($validated);
        return redirect()->route('directorates.index')
            ->with('success', 'تم تعديل المديرية بنجاح');
    }

   public function destroy(Directorate $directorate)
{
    // Check if directorate has schools before deleting
    if ($directorate->schools()->count() > 0) {
        return redirect()->route('directorates.index')
            ->with('error', 'لا يمكن حذف هذه المديرية لأنها تحتوي على مدارس');
    }

    $directorate->delete();

    return redirect()->route('directorates.index')
        ->with('success', 'تم حذف المديرية بنجاح');
}
}