<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        // Admins are never forced; guests are handled by the auth middleware.
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        $supervisor = Auth::guard('web')->user();

        if (! $supervisor || ! $supervisor->must_change_password) {
            return $next($request);
        }

        // The only things a flagged supervisor may reach.
        if ($request->routeIs('password.force.edit', 'password.force.update', 'logout')) {
            return $next($request);
        }

        // Background (AJAX) requests get a clear error instead of a redirect.
        if ($request->expectsJson()) {
            return response()->json(
                ['message' => 'يجب تغيير كلمة المرور أولاً.'],
                403
            );
        }

        return redirect()->route('password.force.edit');
    }
}