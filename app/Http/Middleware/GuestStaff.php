<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestStaff
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $guard = Auth::guard('staff');

        if (!$guard->check()) {
            return $next($request);
        }

        $user = $guard->user();

        if (
            in_array(
                $user->role,
                ['admin', 'staff'],
                true
            )
        ) {
            return redirect()
                ->route('dashboard');
        }

        $guard->logout();

        $request->session()->forget(
            $guard->getName()
        );

        $request->session()->regenerateToken();

        return $next($request);
    }
}
