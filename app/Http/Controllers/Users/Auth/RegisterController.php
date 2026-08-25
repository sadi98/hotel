<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class RegisterController extends Controller
{
    public function index(): View
    {
        return view('users.auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers(),
                ],
                'terms' => [
                    'accepted',
                ],
            ],
            [
                'name.required' => 'Please enter your full name.',
                'name.min' => 'Your full name must contain at least 3 characters.',
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'An account with this email address already exists.',
                'password.required' => 'Please create a password.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.min' => 'Your password must contain at least 8 characters.',
                'terms.accepted' => 'You must agree to the Terms and Conditions.',
            ]
        );

        try {
            $user = DB::transaction(function () use ($validated) {
                return User::create([
                    'name' => trim($validated['name']),
                    'email' => Str::lower(trim($validated['email'])),
                    'username' => $this->generateUniqueUsername(
                        $validated['name'],
                        $validated['email']
                    ),
                    'phone' => null,
                    'avatar' => null,
                    'gender' => null,
                    'role' => 'user',
                    'password' => Hash::make(
                        $validated['password']
                    ),
                ]);
            });

            event(new Registered($user));

            Auth::login($user);

            $request->session()->regenerate();

            return redirect()
                ->route('home')
                ->with(
                    'success',
                    'Your account has been created successfully.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->withErrors([
                    'register' => 'We could not create your account. Please try again.',
                ]);
        }
    }

    private function generateUniqueUsername(
        string $name,
        string $email
    ): string {
        $emailUsername = Str::before($email, '@');

        $baseUsername = Str::slug(
            $emailUsername ?: $name,
            ''
        );

        if ($baseUsername === '') {
            $baseUsername = 'guest';
        }

        $baseUsername = Str::lower(
            Str::limit($baseUsername, 40, '')
        );

        $username = $baseUsername;
        $counter = 1;

        while (
            User::where('username', $username)->exists()
        ) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
