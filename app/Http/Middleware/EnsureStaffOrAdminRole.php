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
        if (!Auth::check()) {
            return redirect()
                ->route('login.staff')
                ->withErrors([
                    'auth' => 'Please sign in to access the management area.',
                ]);
        }

        if (!in_array(Auth::user()->role, ['admin', 'staff'], true)) {
            return redirect()
                ->route('home')
                ->withErrors([
                    'auth' => 'You are not authorized to access the management area.',
                ]);
        }

        return $next($request);
    }
}
