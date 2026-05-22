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
        // 🔥 INI YANG BENAR: Memaksa HTTPS untuk semua link dan form submit di VPS
if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}