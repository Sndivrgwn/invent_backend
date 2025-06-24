<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::viaRemember(function ($user) {
            Cookie::queue('remember_web_token', Cookie::get('remember_web_token'), 60 * 48); // 48 jam
        });

        // Define Gates for role-based authorization
        Gate::define('isAdmin', function ($user) {
            return $user->roles_id == 1; // 1 = admin
        });

        Gate::define('isUser', function ($user) {
            return $user->roles_id == 2; // 2 = user
        });

        Gate::define('isSuperAdmin', function ($user) {
            return $user->roles_id == 3; // 3 = super admin
        });

        Gate::define('adminFunction', function ($user) {
    return in_array($user->roles_id, [1, 3]); // Admin dan Super Admin
});

    }
}