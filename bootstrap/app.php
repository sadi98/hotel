<?php

use App\Http\Middleware\EnsureAdminRole;
use App\Http\Middleware\EnsureStaffOrAdminRole;
use App\Http\Middleware\EnsureStaffRole;
use App\Http\Middleware\EnsureUserRole;
use App\Http\Middleware\GuestStaff;
use App\Http\Middleware\GuestUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: ['webhooks/midtrans']);

        $middleware->alias([
            'guest.user' => GuestUser::class,
            'guest.staff' => GuestStaff::class,
            'auth.user' => EnsureUserRole::class,
            'auth.admin' => EnsureAdminRole::class,
            'auth.staff' => EnsureStaffRole::class,
            'auth.staff_or_admin' => EnsureStaffOrAdminRole::class,
        ]);

        $middleware->redirectGuestsTo(
            function (Request $request): string {
                if (
                    $request->is('admin/*') ||
                    $request->is('staff/*')
                ) {
                    return route('login.staff');
                }

                return route('login');
            }
        );

        $middleware->redirectUsersTo(
            function (Request $request): string {
                $user = $request->user();

                if (!$user) {
                    return route('login');
                }

                return match ($user->role) {
                    'admin' => route('admin.dashboard'),
                    'staff' => route('staff.dashboard'),
                    default => route('home'),
                };
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
