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

        Gate::define('ver_dashboard', function ($user) {
            return in_array($user->perfil, ['admin', 'operador', 'visualizacao']);
        });

        Gate::define('admin', function ($user) {
            return $user->perfil === 'admin';
        });

        Gate::define('operador', function ($user) {
            return in_array($user->perfil, ['admin', 'operador']);
        });

        Gate::define('admin-only', function ($user) {
            return $user->perfil === 'admin';
        });
    }
}
