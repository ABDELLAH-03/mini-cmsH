<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Define gates (permissions) for your CMS
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-site', function (User $user, $site) {
            return $user->id === $site->user_id || $user->role === 'admin';
        });

        Gate::define('manage-page', function (User $user, $page) {
            return $user->id === $page->site->user_id || $user->role === 'admin';
        });

        // Optional: Set default string length for migrations
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
    }
}