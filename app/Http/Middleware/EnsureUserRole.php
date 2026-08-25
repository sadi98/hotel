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
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'auth' => 'Please sign in to continue.',
                ]);
        }

        if (Auth::user()->role !== 'user') {
            return $this->redirectToCorrectDashboard();
        }

        return $next($request);
    }

    private function redirectToCorrectDashboard(): Response
    {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => abort(403, 'You are not authorized to access this page.'),
        };
    }
}
