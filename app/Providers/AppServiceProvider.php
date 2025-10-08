<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        // Auto-login user with ID 1 for testing (only in local environment)
        if (app()->environment('local') && !Auth::check()) {
            Auth::loginUsingId(1);
        }
    }
}
