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