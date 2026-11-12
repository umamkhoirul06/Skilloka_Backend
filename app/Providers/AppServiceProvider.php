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
        // 🔥 Memaksa skema HTTPS untuk semua asset() dan route() di VPS, Azure App Service, Nginx, & Cloudflare
        if (
            config('app.env') === 'production' ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            request()->isSecure() ||
            (request()->header('X-Forwarded-Proto') === 'https') ||
            str_contains(request()->header('Host', ''), 'azurewebsites.net')
        ) {
            URL::forceScheme('https');
        }

        // ⚙️ Integrasi Pengaturan Sistem Platform ke seluruh aplikasi
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $platformSetting = \App\Models\Setting::first();
                if ($platformSetting) {
                    if (!empty($platformSetting->timezone)) {
                        config(['app.timezone' => $platformSetting->timezone]);
                        @date_default_timezone_set($platformSetting->timezone);
                    }
                    if (!empty($platformSetting->platform_name)) {
                        config(['app.name' => $platformSetting->platform_name]);
                    }
                    \Illuminate\Support\Facades\View::share('platformSetting', $platformSetting);
                }
            }
        } catch (\Throwable $e) {
            // Ignore during setup/migrations
        }
    }
}