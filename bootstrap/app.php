<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\SecureHeaders::class,
        ]);

        $middleware->alias([
            'super-admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'admin-lpk' => \App\Http\Middleware\EnsureAdminLpk::class,
        ]);

        // 🔥 INI KODINGAN AJAIB PENUNJUK JALAN LOGIN-NYA
        $middleware->redirectGuestsTo(fn() => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // HANYA tangkap error 403 (Akses Ditolak)
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() == 403) {
                return response()->view('errors.403', [], 403);
            }
        });

        // Tangkap error 403 khusus Spatie
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            return response()->view('errors.403', [], 403);
        });

        // Biarkan error lainnya dihandle Laravel secara default 
        // agar pesan merah login muncul kembali.
    
    })->create();