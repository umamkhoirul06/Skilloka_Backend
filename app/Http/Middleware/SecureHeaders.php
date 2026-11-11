<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeaders
{
    /**
     * Terapkan OWASP Security Headers ke setiap respon HTTP.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Pastikan respon mendukung penambahan header (bukan stream biner murni yang terkunci)
        if (!method_exists($response, 'header')) {
            return $response;
        }

        // 1. Content Security Policy (CSP)
        $response->header(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http://localhost:5173 http://[::1]:5173 http://127.0.0.1:5173; " .
            "style-src 'self' 'unsafe-inline' https: http://localhost:5173 http://[::1]:5173 http://127.0.0.1:5173; " .
            "img-src 'self' data: https: blob:; " .
            "font-src 'self' data: https: http://localhost:5173 http://[::1]:5173 http://127.0.0.1:5173; " .
            "connect-src 'self' https: http://localhost:5173 ws://localhost:5173 http://127.0.0.1:5173;"
        );

        // 2. Proteksi Clickjacking (OWASP A05)
        $response->header('X-Frame-Options', 'SAMEORIGIN');

        // 3. Cegah MIME Sniffing Drive-by (OWASP A05)
        $response->header('X-Content-Type-Options', 'nosniff');

        // 4. Proteksi XSS Filter Browser (Legacy Browser Protection)
        $response->header('X-XSS-Protection', '1; mode=block');

        // 5. Batasi Bocornya URL Referer (Privasi & Token Leakage)
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 6. Kebijakan Fitur Sensor & Perangkat Keras
        $response->header('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // 7. HSTS (HTTP Strict Transport Security) - Paksa HTTPS di Production
        if ($request->isSecure() || config('app.env') === 'production') {
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
