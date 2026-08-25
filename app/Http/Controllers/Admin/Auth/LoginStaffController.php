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
        $credentials = $request->validate(
            [
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
            ],
            [
                'login.required' => 'Please enter your email address or username.',
                'password.required' => 'Please enter your password.',
            ]
        );

        $login = trim($credentials['login']);

        $loginField = filter_var(
            $login,
            FILTER_VALIDATE_EMAIL
        )
            ? 'email'
            : 'username';

        $throttleKey = Str::transliterate(
            Str::lower($login) . '|' . $request->ip()
        );

        if (
            RateLimiter::tooManyAttempts(
                $throttleKey,
                5
            )
        ) {
            $seconds = RateLimiter::availableIn(
                $throttleKey
            );

            throw ValidationException::withMessages([
                'login' => "Too many sign-in attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $loginSuccessful = Auth::attempt(
            [
                $loginField => $login,
                'password' => $credentials['password'],
                function ($query) {
                    $query->whereIn(
                        'role',
                        ['admin', 'staff']
                    );
                },
            ],
            $request->boolean('remember')
        );

        if (!$loginSuccessful) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'login' => 'The provided credentials are incorrect.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return match (Auth::user()->role) {
            'admin' => redirect()
                ->intended(route('admin.dashboard'))
                ->with(
                    'success',
                    'Welcome back, Administrator.'
                ),

            'staff' => redirect()
                ->intended(route('staff.dashboard'))
                ->with(
                    'success',
                    'Welcome back.'
                ),

            default => $this->rejectUnauthorizedRole(
                $request
            ),
        };
    }

    public function destroy(
        Request $request
    ): RedirectResponse {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login.staff')
            ->with(
                'success',
                'You have signed out successfully.'
            );
    }

    private function rejectUnauthorizedRole(
        Request $request
    ): RedirectResponse {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login.staff')
            ->withErrors([
                'login' => 'You are not authorized to access the management area.',
            ]);
    }
}
