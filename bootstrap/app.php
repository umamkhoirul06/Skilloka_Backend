<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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

            \App\Http\Middleware\SecureHeaders::class, // Keamanan asli milikmu
        ]);

        $middleware->alias([
            'super-admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'admin-lpk' => \App\Http\Middleware\EnsureAdminLpk::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions) {

        // Mencegat error Spatie secara mutlak
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
            return response()->view('errors.403', [], 403);
        });

        // Mencegat error server secara mutlak
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($e->getCode() == 500 || $e->getCode() == 0) {
                return response()->view('errors.500', [], 500);
            }
        });

    })

    ->create();