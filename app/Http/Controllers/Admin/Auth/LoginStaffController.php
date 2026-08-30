<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginStaffController extends Controller
{
    public function index(): View
    {
        return view('admin.auth.login');
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'login' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
            ],
            'remember' => [
                'nullable',
                'boolean',
            ],
        ]);

        $login = trim($validated['login']);

        $field = filter_var(
            $login,
            FILTER_VALIDATE_EMAIL
        ) ? 'email' : 'username';

        if ($field === 'email') {
            $login = Str::lower($login);
        }

        $rateKey = Str::lower(
            $login . '|management|' . $request->ip()
        );

        if (
            RateLimiter::tooManyAttempts(
                $rateKey,
                5
            )
        ) {
            $seconds = RateLimiter::availableIn(
                $rateKey
            );

            throw ValidationException::withMessages([
                'login' =>
                "Too many login attempts. Try again in {$seconds} seconds.",
            ]);
        }

        $success = Auth::guard('staff')->attempt(
            [
                $field => $login,
                'password' => $validated['password'],
                function ($query) {
                    $query->whereIn(
                        'role',
                        ['admin', 'staff']
                    );
                },
            ],
            $request->boolean('remember')
        );

        if (!$success) {
            RateLimiter::hit(
                $rateKey,
                60
            );

            throw ValidationException::withMessages([
                'login' =>
                'Email, username, or password is incorrect.',
            ]);
        }

        RateLimiter::clear($rateKey);

        $request->session()->regenerate();

        $user = Auth::guard('staff')->user();

        return redirect()
            ->intended(route('dashboard'))
            ->with(
                'success',
                $user->role === 'admin'
                    ? 'Welcome back, Administrator.'
                    : 'Welcome back.'
            );
    }

    public function destroy(
        Request $request
    ): RedirectResponse {
        $guard = Auth::guard('staff');

        $guard->logout();

        // Jangan invalidate seluruh session,
        // karena user mungkin masih login pada guard web.
        $request->session()->forget(
            $guard->getName()
        );

        $request->session()->regenerateToken();

        return redirect()
            ->route('login.staff')
            ->with('success', 'You have signed out.');
    }

    private function logoutInvalidUser(
        Request $request
    ): RedirectResponse {
        Auth::guard('staff')->logout();

        return redirect()
            ->route('login.staff')
            ->withErrors([
                'login' =>
                'You are not authorized to access management.',
            ]);
    }
}
