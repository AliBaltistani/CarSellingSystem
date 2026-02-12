<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        // Set default string length for MySQL utf8mb4 compatibility
        Schema::defaultStringLength(191);

        // Share settings with all views
        try {
            \Illuminate\Support\Facades\Log::info('Request: ' . request()->fullUrl() . ' | Previous: ' . url()->previous());
            if (Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
                view()->share('globalSettings', $settings);
            }
        } catch (\Exception $e) {
            // Migrations might not be run yet
        }
    }
}
