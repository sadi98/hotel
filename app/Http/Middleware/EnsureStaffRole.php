<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaffRole
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $guard = Auth::guard('staff');

        if (!$guard->check()) {
            return redirect()
                ->route('login.staff')
                ->withErrors([
                    'auth' =>
                    'Please sign in as a staff member.',
                ]);
        }

        $user = $guard->user();

        if ($user->role !== 'staff') {
            if ($user->role === 'admin') {
                return redirect()
                    ->route('dashboard')
                    ->withErrors([
                        'auth' =>
                        'This page is only available to staff members.',
                    ]);
            }

            $guard->logout();

            return redirect()
                ->route('login.staff');
        }

        Auth::shouldUse('staff');

        return $next($request);
    }
}
