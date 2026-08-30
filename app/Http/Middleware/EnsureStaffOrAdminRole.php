<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaffOrAdminRole
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
                    'Please sign in to access the management area.',
                ]);
        }

        $user = $guard->user();

        if (
            !in_array(
                $user->role,
                ['admin', 'staff'],
                true
            )
        ) {
            $guard->logout();

            $request->session()->forget(
                $guard->getName()
            );

            $request->session()->regenerateToken();

            return redirect()
                ->route('login.staff')
                ->withErrors([
                    'auth' =>
                    'You are not authorized to access management.',
                ]);
        }

        Auth::shouldUse('staff');

        return $next($request);
    }
}
