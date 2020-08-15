<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is-superadmin', function ($user) {
            return method_exists($user, 'roles') ? $user->roles()->first()->code == "SADM" : false;
        });

        Gate::define('is-admin', function ($user) {
            return method_exists($user, 'roles') ? ($user->roles()->first()->code == "ADM" || $user->roles()->first()->code == "SADM") : false;
        });

        Gate::define('is-admin-group', function ($user) {
            return method_exists($user, 'roles') ? ($user->roles()->first() != null && $user->roles()->first()->code != "MTRUSR") : false;
        });

        Gate::define('is-meteor', function ($user) {
            return method_exists($user, 'roles') ? $user->roles()->first()->code == "MTRUSR" : false;
        });

        Gate::define('is-student', function ($user) {
            return !method_exists($user, 'roles') && $user->npm != null;
        });

        Gate::define('is-finance-group', function($user) {
            return $user->type == 'finance';
        });

        Gate::define('is-finance-admin', function($user) {
            return $user->is_admin == 1 && $user->type == 'finance';
        });       

        // if not from user method and is admin prodi
        Gate::define('is-prodi-admin', function($user) {
            return $user->is_admin == 1 && $user->type == 'prodi';
        });

        // if not from user method and is not prodi admin
        Gate::define('is-prodi-dosen', function($user) {
            return $user->is_admin == 0 && $user->type == 'prodi';
        });

        Gate::define('is-library-admin', function($user) {
            return $user->is_admin == 1 && $user->type == 'library';
        });

        Gate::define('is-library-user', function($user) {
            return $user->is_admin == 0 && $user->type == 'library';
        });

        Gate::define('is-student-profile-filled', function ($user) {
            return method_exists($user, 'roles') || $user->profile_filled;
        });

        Gate::define('is-student-must-fill-attachment', function ($user) {
            return method_exists($user, 'roles') || $user->must_fill_attachment;
        });

        Gate::define('is-student-semester-active', function ($user) {
            $semester = $user->semester()->first();
            return method_exists($user, 'roles') || $semester->is_active;
        });
    }
}
