<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $guard = Auth::guard('web');

        if (!$guard->check()) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'auth' => 'Please sign in to continue.',
                ]);
        }

        $user = $guard->user();

        if ($user->role !== 'user') {
            return $this->rejectInvalidUserSession(
                $request
            );
        }

        Auth::shouldUse('web');

        return $next($request);
    }

    private function rejectInvalidUserSession(
        Request $request
    ): Response {
        $guard = Auth::guard('web');

        $guard->logout();

        $request->session()->forget(
            $guard->getName()
        );

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'auth' => 'Please use a user account to access this page.',
            ]);
    }
}
