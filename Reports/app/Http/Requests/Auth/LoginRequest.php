<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Try the admin (users table) guard first, then fall back to the
     * supervisor (super_visors table) guard.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Force a clean slate before attempting a new login. Without this,
        // leftover state from a previous session (e.g. a "remember me"
        // cookie that wasn't fully cleared on logout) can silently
        // re-authenticate the OLD account on the very next request after
        // switching users — showing the admin dashboard even though you
        // just submitted supervisor credentials.
        Auth::guard('admin')->logout();
        Auth::guard('web')->logout();

        $remember = $this->boolean('remember');

        if (Auth::guard('admin')->attempt([
            'email'    => $this->input('login'),
            'password' => $this->input('password'),
        ], $remember)) {
            RateLimiter::clear($this->throttleKey());

            return;
        }

        if (Auth::guard('web')->attempt([
            'SuperVisor_Name' => $this->input('login'),
            'password'        => $this->input('password'),
        ], $remember)) {
            RateLimiter::clear($this->throttleKey());

            return;
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.failed'),
        ]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}