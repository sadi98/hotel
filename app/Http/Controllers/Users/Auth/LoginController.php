<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View
    {
        return view('users.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate(
            [
                'email' => [
                    'required',
                    'string',
                    'email',
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
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'Please enter your password.',
            ]
        );

        $email = Str::lower(trim($credentials['email']));

        $throttleKey = Str::transliterate(
            $email . '|' . $request->ip()
        );

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Too many sign-in attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $loginSuccessful = Auth::attempt(
            [
                'email' => $email,
                'password' => $credentials['password'],
                'role' => 'user',
            ],
            $request->boolean('remember')
        );

        if (!$loginSuccessful) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'The provided email address or password is incorrect.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return redirect()
            ->intended(route('home'))
            ->with('success', 'Welcome back! You have signed in successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have signed out successfully.');
    }
}
