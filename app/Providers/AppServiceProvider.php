<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        Paginator::useBootstrapFive();

        /*
        |--------------------------------------------------------------------------
        | Individual Role Gates
        |--------------------------------------------------------------------------
        */

        Gate::define(
            'role-user',
            function (User $user): bool {
                return $user->isUser();
            }
        );

        Gate::define(
            'role-admin',
            function (User $user): bool {
                return $user->isAdmin();
            }
        );

        Gate::define(
            'role-staff',
            function (User $user): bool {
                return $user->isStaff();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Combined Role Gates
        |--------------------------------------------------------------------------
        */

        Gate::define(
            'admin-or-staff',
            function (User $user): bool {
                return $user->isStaffOrAdmin();
            }
        );
    }
}
