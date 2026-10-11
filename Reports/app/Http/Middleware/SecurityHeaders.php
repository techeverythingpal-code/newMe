<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Stop browsers guessing a file's type (blocks some upload/XSS tricks).
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Stop other websites from embedding these pages in a hidden frame.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Don't leak full page URLs to other sites.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // This app never needs the camera, microphone or location.
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Tell browsers to always use HTTPS for this site (only sent over HTTPS,
        // so local http://localhost development is not affected).
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=15552000');
        }

        return $response;
    }
}