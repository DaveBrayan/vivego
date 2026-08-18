<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        $isHttps = request()->secure() 
            || request()->header('x-forwarded-proto') === 'https' 
            || request()->server('HTTP_X_FORWARDED_PROTO') === 'https'
            || (config('app.url') && str_starts_with(config('app.url'), 'https://'));

        if ($isHttps) {
            URL::forceScheme('https');
        }
    }
}
