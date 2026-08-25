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
        if (!Auth::check()) {
            return redirect()
                ->route('login.staff')
                ->withErrors([
                    'auth' => 'Please sign in as a staff member.',
                ]);
        }

        if (Auth::user()->role !== 'staff') {
            return $this->redirectToCorrectPage();
        }

        return $next($request);
    }

    private function redirectToCorrectPage(): Response
    {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'user' => redirect()->route('home'),
            default => abort(403, 'You are not authorized to access this page.'),
        };
    }
}
