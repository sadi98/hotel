<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class ForgotPasswordController extends Controller
{
    private const OTP_EXPIRATION_MINUTES = 5;

    private const MAX_OTP_ATTEMPTS = 5;

    public function index(): View
    {
        return view('users.auth.forgot-password');
    }

    public function sendOtp(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                ],
            ],
            [
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
            ]
        );

        $email = Str::lower(trim($validated['email']));

        $user = User::query()
            ->where('email', $email)
            ->where('role', 'user')
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'We could not find a user account with this email address.',
            ]);
        }

        $rateLimitKey = 'send-password-otp|' .
            $email . '|' . $request->ip();

        if (
            RateLimiter::tooManyAttempts(
                $rateLimitKey,
                3
            )
        ) {
            $seconds = RateLimiter::availableIn(
                $rateLimitKey
            );

            throw ValidationException::withMessages([
                'email' => "Too many verification code requests. Please try again in {$seconds} seconds.",
            ]);
        }

        RateLimiter::hit($rateLimitKey, 300);

        try {
            $this->createAndSendOtp($user);

            $request->session()->put(
                'otp_email',
                $user->email
            );

            $request->session()->forget([
                'password_reset_verified',
                'password_reset_verified_at',
            ]);

            return redirect()
                ->route('password.otp')
                ->with(
                    'success',
                    'A verification code has been sent to your email address.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'We could not send the verification code. Please try again.',
                ]);
        }
    }

    public function showVerifyOtp(
        Request $request
    ): View|RedirectResponse {
        if (!$request->session()->has('otp_email')) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Please enter your email address first.',
                ]);
        }

        return view('users.auth.verify-otp');
    }

    public function verifyOtp(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                ],
                'otp' => [
                    'required',
                    'digits:6',
                ],
            ],
            [
                'email.required' => 'Your email session is missing. Please request a new code.',
                'email.email' => 'The email address is invalid.',
                'otp.required' => 'Please enter the verification code.',
                'otp.digits' => 'The verification code must contain exactly 6 digits.',
            ]
        );

        $sessionEmail = Str::lower(
            (string) $request->session()->get(
                'otp_email'
            )
        );

        $submittedEmail = Str::lower(
            trim($validated['email'])
        );

        if (
            $sessionEmail === '' ||
            !hash_equals($sessionEmail, $submittedEmail)
        ) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Your verification session is invalid. Please request a new code.',
                ]);
        }

        $otpRecord = PasswordOtp::query()
            ->where('email', $sessionEmail)
            ->whereNull('verified_at')
            ->latest('id')
            ->first();

        if (!$otpRecord) {
            throw ValidationException::withMessages([
                'otp' => 'No active verification code was found. Please request a new code.',
            ]);
        }

        if ($otpRecord->hasExpired()) {
            throw ValidationException::withMessages([
                'otp' => 'Your verification code has expired. Please request a new code.',
            ]);
        }

        if (
            $otpRecord->attempts >=
            self::MAX_OTP_ATTEMPTS
        ) {
            throw ValidationException::withMessages([
                'otp' => 'Too many incorrect attempts. Please request a new verification code.',
            ]);
        }

        if (
            !Hash::check(
                $validated['otp'],
                $otpRecord->otp
            )
        ) {
            $otpRecord->increment('attempts');

            $remainingAttempts = max(
                0,
                self::MAX_OTP_ATTEMPTS -
                    ($otpRecord->attempts + 1)
            );

            throw ValidationException::withMessages([
                'otp' => "The verification code is incorrect. {$remainingAttempts} attempt(s) remaining.",
            ]);
        }

        $otpRecord->update([
            'verified_at' => now(),
        ]);

        $request->session()->put([
            'password_reset_verified' => $sessionEmail,
            'password_reset_verified_at' => now()->timestamp,
        ]);

        $request->session()->regenerate();

        return redirect()
            ->route('password.reset.form')
            ->with(
                'success',
                'Your email address has been verified. Please create a new password.'
            );
    }

    public function resendOtp(
        Request $request
    ): RedirectResponse {
        $email = Str::lower(
            trim(
                (string) $request->session()->get(
                    'otp_email',
                    $request->input('email')
                )
            )
        );

        if ($email === '') {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Please enter your email address first.',
                ]);
        }

        $user = User::query()
            ->where('email', $email)
            ->where('role', 'user')
            ->first();

        if (!$user) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'We could not find a user account with this email address.',
                ]);
        }

        $rateLimitKey = 'resend-password-otp|' .
            $email . '|' . $request->ip();

        if (
            RateLimiter::tooManyAttempts(
                $rateLimitKey,
                3
            )
        ) {
            $seconds = RateLimiter::availableIn(
                $rateLimitKey
            );

            throw ValidationException::withMessages([
                'otp' => "Please wait {$seconds} seconds before requesting another code.",
            ]);
        }

        RateLimiter::hit($rateLimitKey, 300);

        try {
            $this->createAndSendOtp($user);

            $request->session()->put(
                'otp_email',
                $user->email
            );

            return redirect()
                ->route('password.otp')
                ->with(
                    'success',
                    'A new verification code has been sent to your email address.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'otp' => 'We could not resend the verification code. Please try again.',
            ]);
        }
    }

    public function showResetPassword(
        Request $request
    ): View|RedirectResponse {
        if (!$this->hasValidResetSession($request)) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Your password reset session has expired. Please request a new verification code.',
                ]);
        }

        return view('users.auth.reset-password');
    }

    public function resetPassword(
        Request $request
    ): RedirectResponse {
        if (!$this->hasValidResetSession($request)) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Your password reset session has expired. Please request a new verification code.',
                ]);
        }

        $validated = $request->validate(
            [
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers(),
                ],
            ],
            [
                'password.required' => 'Please enter your new password.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.min' => 'Your new password must contain at least 8 characters.',
            ]
        );

        $email = Str::lower(
            (string) $request->session()->get(
                'password_reset_verified'
            )
        );

        $user = User::query()
            ->where('email', $email)
            ->where('role', 'user')
            ->first();

        if (!$user) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'The user account could not be found.',
                ]);
        }

        $user->forceFill([
            'password' => Hash::make(
                $validated['password']
            ),
            'remember_token' => Str::random(60),
        ])->save();

        PasswordOtp::query()
            ->where('email', $email)
            ->delete();

        $request->session()->forget([
            'otp_email',
            'password_reset_verified',
            'password_reset_verified_at',
        ]);

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Your password has been reset successfully. You can now sign in.'
            );
    }

    private function createAndSendOtp(
        User $user
    ): void {
        $plainOtp = (string) random_int(
            100000,
            999999
        );

        PasswordOtp::query()
            ->where('email', $user->email)
            ->delete();

        PasswordOtp::create([
            'email' => $user->email,
            'otp' => Hash::make($plainOtp),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(
                self::OTP_EXPIRATION_MINUTES
            ),
        ]);

        Mail::send(
            'emails.password-otp',
            [
                'user' => $user,
                'otp' => $plainOtp,
                'expirationMinutes' =>
                self::OTP_EXPIRATION_MINUTES,
            ],
            function ($message) use ($user) {
                $message
                    ->to($user->email, $user->name)
                    ->subject(
                        'Your Aurora Hotel verification code'
                    );
            }
        );
    }

    private function hasValidResetSession(
        Request $request
    ): bool {
        $email = $request->session()->get(
            'password_reset_verified'
        );

        $verifiedAt = $request->session()->get(
            'password_reset_verified_at'
        );

        if (!$email || !$verifiedAt) {
            return false;
        }

        return now()->timestamp - (int) $verifiedAt <= 600;
    }
}
