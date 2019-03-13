<?php

namespace App\Providers;

use App\Contracts\Authy;
use Authy\AuthyApi;
use Illuminate\Support\ServiceProvider;

class AuthyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }

    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(AuthyApi::class, function ($app) {
            return new AuthyApi(config('services.authy.secret'));
        });
    }
}
