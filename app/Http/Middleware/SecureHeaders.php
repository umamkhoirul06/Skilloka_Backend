<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeaders
{
     public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        return $response->header(
    'Content-Security-Policy',
    "default-src 'self'; " .
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http://localhost:5173 http://[::1]:5173 http://127.0.0.1:5173; " .
    "style-src 'self' 'unsafe-inline' https: http://localhost:5173 http://[::1]:5173 http://127.0.0.1:5173; " .
    "img-src 'self' data: https:; " .
    "font-src 'self' data: https: http://localhost:5173 http://[::1]:5173 http://127.0.0.1:5173;"
);
    }
}