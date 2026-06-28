<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            static $platformSettings = null;
            static $globalAdvisors = null;

            if ($platformSettings === null) {
                $platformSettings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
            }
            if ($globalAdvisors === null) {
                $globalAdvisors = \App\Models\Advisor::where('status', true)->get();
            }

            $view->with('platformSettings', $platformSettings);
            $view->with('globalAdvisors', $globalAdvisors);
        });
    }
}
