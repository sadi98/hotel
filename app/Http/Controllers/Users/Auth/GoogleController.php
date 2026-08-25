<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes([
                'openid',
                'profile',
                'email',
            ])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser->getEmail()) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'google' => 'Google did not provide an email address.',
                    ]);
            }

            $email = Str::lower(
                trim($googleUser->getEmail())
            );

            $existingUser = User::query()
                ->where('email', $email)
                ->first();

            if (
                $existingUser &&
                $existingUser->role !== 'user'
            ) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'google' => 'This Google account cannot be used on the customer sign-in page.',
                    ]);
            }

            $user = DB::transaction(
                function () use (
                    $googleUser,
                    $existingUser,
                    $email
                ) {
                    if ($existingUser) {
                        $existingUser->update([
                            'google_id' => $googleUser->getId(),
                            'avatar' => $googleUser->getAvatar()
                                ?: $existingUser->avatar,
                            'email_verified_at' =>
                            $existingUser->email_verified_at
                                ?: now(),
                        ]);

                        return $existingUser->fresh();
                    }

                    return User::create([
                        'google_id' => $googleUser->getId(),
                        'name' => $googleUser->getName()
                            ?: 'Google User',
                        'email' => $email,
                        'phone' => null,
                        'username' =>
                        $this->generateUniqueUsername(
                            $googleUser->getName()
                                ?: 'Google User',
                            $email
                        ),
                        'avatar' =>
                        $googleUser->getAvatar(),
                        'gender' => null,
                        'role' => 'user',
                        'email_verified_at' => now(),
                        'password' => null,
                    ]);
                }
            );

            Auth::login($user, true);

            request()->session()->regenerate();

            return redirect()
                ->intended(route('home'))
                ->with(
                    'success',
                    'You have signed in with Google successfully.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'Google authentication failed. Please try again.',
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
