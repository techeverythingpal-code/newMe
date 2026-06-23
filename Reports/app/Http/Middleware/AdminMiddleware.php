<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // If logged in but not admin → redirect to dashboard
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard')
                ->with('error', 'ليس لديك صلاحية للوصول لهذه الصفحة');
        }

        return redirect()->route('login');
    }
}