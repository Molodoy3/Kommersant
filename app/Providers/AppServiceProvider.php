<?php

namespace App\Providers;


use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
        //ставим ограничение в 60 запросов в минуту на один ip
        RateLimiter::for('api', function (Request $request) {
            //return false;
            return Limit::perMinute(200)->by($request->ip());
        });
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
