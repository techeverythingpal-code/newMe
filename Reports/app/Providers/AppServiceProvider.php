<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        // One app-wide password rule. Anything using Password::defaults()
        // (profile page, password reset, supervisor forms) follows it.
        Password::defaults(fn () => Password::min(8));
    }
}