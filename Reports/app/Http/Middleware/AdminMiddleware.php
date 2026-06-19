<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // If logged in but not admin → redirect to dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard')
                ->with('error', 'ليس لديك صلاحية للوصول لهذه الصفحة');
        }

        return redirect()->route('login');
    }
}