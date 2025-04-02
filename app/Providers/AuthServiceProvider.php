<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define as permissões do sistema
        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-settings', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-products', function ($user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        Gate::define('manage-categories', function ($user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        Gate::define('manage-quotes', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'user']);
        });

        Gate::define('manage-clients', function ($user) {
            return in_array($user->role, ['admin', 'manager', 'user']);
        });

        Gate::define('view-reports', function ($user) {
            return in_array($user->role, ['admin', 'manager']);
        });
    }
} 