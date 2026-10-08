<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ForcePasswordChangeController extends Controller
{
    /**
     * Show the "choose a new password" page.
     * Anyone who does not need it (admins, or supervisors already done) is sent on.
     */
    public function edit(): View|RedirectResponse
    {
        $supervisor = Auth::guard('web')->user();

        if (Auth::guard('admin')->check() || ! $supervisor || ! $supervisor->must_change_password) {
            return redirect()->route('dashboard');
        }

        return view('auth.force-password-change');
    }

    /**
     * Save the new password and clear the flag.
     */
    public function update(Request $request): RedirectResponse
    {
        $supervisor = Auth::guard('web')->user();

        if (Auth::guard('admin')->check() || ! $supervisor) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password'         => [
                'required',
                'confirmed',
                'different:current_password',
                'not_in:ChangeMe123',
                Password::min(8),
            ],
        ]);

        $supervisor->forceFill([
            'password'             => Hash::make($validated['password']),
            'must_change_password' => false,
        ])->save();

        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}