<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))

->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)

->withMiddleware(function (Middleware $middleware) {

    $middleware->use([
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        \App\Http\Middleware\SecureHeaders::class, // tambahkan ini
    ]);


    $middleware->alias([
        'super-admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
        'admin-lpk' => \App\Http\Middleware\EnsureAdminLpk::class,
    ]);

})

->withExceptions(function (Exceptions $exceptions) {
    $exceptions->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
        return response()->view('errors.403', [], 403);
    });

    $exceptions->renderable(function (\Throwable $e, $request) {
        if ($e->getCode() == 500) {
            return response()->view('errors.500', [], 500);
        }
    });
})

->create();