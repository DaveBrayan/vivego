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
        $host = request()->getHost();
        $isLocalHost = in_array($host, ['127.0.0.1', 'localhost']) || str_starts_with($host, '192.168.') || str_starts_with($host, '10.') || str_starts_with($host, '172.');

        if ($isLocalHost || $this->app->isLocal()) {
            URL::forceScheme('http');
        } elseif ($this->app->environment('production') || (config('app.url') && str_contains(config('app.url'), 'https://'))) {
            URL::forceScheme('https');
        }
    }
}
