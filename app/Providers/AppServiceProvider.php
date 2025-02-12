<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CloudInitService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CloudInitService::class, function ($app) {
            return new CloudInitService();
        });   
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
