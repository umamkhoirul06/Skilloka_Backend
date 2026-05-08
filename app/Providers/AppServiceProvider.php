<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Memaksa semua link CSS/JS (Tailwind) menjadi HTTPS
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('http');
        } else {
            // Khusus VPS/Production ini akan langsung memaksa HTTPS
        }
    }
}