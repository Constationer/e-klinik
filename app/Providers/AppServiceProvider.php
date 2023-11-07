<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
\Illuminate\Support\Facades\URL::forceScheme('https');

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
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            URL::forceScheme('https');
        }
    }
}
