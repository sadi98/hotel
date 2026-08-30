<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestUser
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $guard = Auth::guard('web');

        /*
         * Login admin/staff pada guard staff tidak akan
         * menghalangi halaman login user.
         */
        if (!$guard->check()) {
            return $next($request);
        }

        $user = $guard->user();

        if ($user->role === 'user') {
            return redirect()
                ->route('home');
        }

        /*
         * Jika admin/staff tidak sengaja tersimpan pada
         * guard web, bersihkan sesi web tersebut.
         */
        $guard->logout();

        $request->session()->forget(
            $guard->getName()
        );

        $request->session()->regenerateToken();

        return $next($request);
    }
}
