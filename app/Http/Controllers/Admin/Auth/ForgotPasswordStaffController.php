<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ForgotPasswordStaffController extends Controller
{
    private const OTP_EXPIRE_MINUTES = 5;

    private const RESET_EXPIRE_MINUTES = 10;

    public function index(): View
    {
        return view(
            'admin.auth.forgot-password'
        );
    }

    public function sendOtp(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        $email = Str::lower(
            trim($validated['email'])
        );

        $rateKey =
            'management-otp-send|' .
            $email .
            '|' .
            $request->ip();

        if (
            RateLimiter::tooManyAttempts(
                $rateKey,
                3
            )
        ) {
            $seconds = RateLimiter::availableIn(
                $rateKey
            );

            throw ValidationException::withMessages([
                'email' =>
                "Please wait {$seconds} seconds before requesting another code.",
            ]);
        }

        $user = $this->findManagementUser(
            $email
        );

        if (!$user) {
            throw ValidationException::withMessages([
                'email' =>
                'This email is not registered as an administrator or staff account.',
            ]);
        }

        $this->createAndSendOtp($user);

        RateLimiter::hit(
            $rateKey,
            60
        );

        $request->session()->put(
            'staff_reset_email',
            $email
        );

        $request->session()->forget([
            'staff_reset_verified',
            'staff_reset_verified_at',
        ]);

        return redirect()
            ->route('staff.password.otp.form')
            ->with(
                'success',
                'Verification code sent successfully.'
            );
    }

    public function showVerifyOtp(
        Request $request
    ): View|RedirectResponse {
        $email = $request->session()->get(
            'staff_reset_email'
        );

        if (!$email) {
            return redirect()
                ->route('staff.password.request');
        }

        return view(
            'admin.auth.verify-otp',
            compact('email')
        );
    }

    public function verifyOtp(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ]);

        $email = $request->session()->get(
            'staff_reset_email'
        );

        if (!$email) {
            return redirect()
                ->route('staff.password.request');
        }

        $rateKey =
            'management-otp-verify|' .
            $email .
            '|' .
            $request->ip();

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
                'otp' =>
                "Too many attempts. Try again in {$seconds} seconds.",
            ]);
        }

        $record = DB::table(
            'password_reset_tokens'
        )
            ->where('email', $email)
            ->first();

        if (
            !$record ||
            !$record->created_at
        ) {
            RateLimiter::hit($rateKey, 300);

            throw ValidationException::withMessages([
                'otp' =>
                'The verification code is invalid.',
            ]);
        }

        $expired = Carbon::parse(
            $record->created_at
        )
            ->addMinutes(
                self::OTP_EXPIRE_MINUTES
            )
            ->isPast();

        if ($expired) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            throw ValidationException::withMessages([
                'otp' =>
                'The verification code has expired.',
            ]);
        }

        if (
            !Hash::check(
                $validated['otp'],
                $record->token
            )
        ) {
            RateLimiter::hit($rateKey, 300);

            throw ValidationException::withMessages([
                'otp' =>
                'The verification code is incorrect.',
            ]);
        }

        RateLimiter::clear($rateKey);

        $request->session()->put([
            'staff_reset_verified' => true,
            'staff_reset_verified_at' =>
            now()->timestamp,
        ]);

        return redirect()
            ->route(
                'staff.password.reset.form'
            );
    }

    public function resendOtp(
        Request $request
    ): RedirectResponse {
        $email = $request->session()->get(
            'staff_reset_email'
        );

        if (!$email) {
            return redirect()
                ->route('staff.password.request');
        }

        $user = $this->findManagementUser(
            $email
        );

        if (!$user) {
            return redirect()
                ->route('staff.password.request');
        }

        $rateKey =
            'management-otp-send|' .
            $email .
            '|' .
            $request->ip();

        if (
            RateLimiter::tooManyAttempts(
                $rateKey,
                3
            )
        ) {
            $seconds = RateLimiter::availableIn(
                $rateKey
            );

            throw ValidationException::withMessages([
                'otp' =>
                "Please wait {$seconds} seconds before requesting another code.",
            ]);
        }

        $this->createAndSendOtp($user);

        RateLimiter::hit($rateKey, 60);

        return back()->with(
            'success',
            'A new verification code has been sent.'
        );
    }

    public function showResetPassword(
        Request $request
    ): View|RedirectResponse {
        if (!$this->verified($request)) {
            return redirect()
                ->route('staff.password.request')
                ->withErrors([
                    'email' =>
                    'Your verification session has expired.',
                ]);
        }

        return view(
            'admin.auth.reset-password'
        );
    }

    public function resetPassword(
        Request $request
    ): RedirectResponse {
        if (!$this->verified($request)) {
            return redirect()
                ->route('staff.password.request');
        }

        $validated = $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],
        ]);

        $email = $request->session()->get(
            'staff_reset_email'
        );

        $user = $this->findManagementUser(
            $email
        );

        if (!$user) {
            return redirect()
                ->route('staff.password.request');
        }

        $user->forceFill([
            'password' => Hash::make(
                $validated['password']
            ),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        $request->session()->forget([
            'staff_reset_email',
            'staff_reset_verified',
            'staff_reset_verified_at',
        ]);

        return redirect()
            ->route('login.staff')
            ->with(
                'success',
                'Password reset successfully. Please sign in.'
            );
    }

    private function createAndSendOtp(
        User $user
    ): void {
        $otp = (string) random_int(
            100000,
            999999
        );

        DB::table('password_reset_tokens')
            ->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($otp),
                    'created_at' => now(),
                ]
            );

        Mail::send(
            'admin.auth.emails.password-otp',
            [
                'user' => $user,
                'otp' => $otp,
                'expirationMinutes' =>
                self::OTP_EXPIRE_MINUTES,
            ],
            function ($message) use ($user) {
                $message
                    ->to(
                        $user->email,
                        $user->name
                    )
                    ->subject(
                        'Management Password Reset Code'
                    );
            }
        );
    }

    private function findManagementUser(
        string $email
    ): ?User {
        return User::query()
            ->where('email', $email)
            ->whereIn(
                'role',
                ['admin', 'staff']
            )
            ->whereNotNull('password')
            ->first();
    }

    private function verified(
        Request $request
    ): bool {
        $verified = $request->session()->get(
            'staff_reset_verified'
        );

        $verifiedAt = $request->session()->get(
            'staff_reset_verified_at'
        );

        if (!$verified || !$verifiedAt) {
            return false;
        }

        return Carbon::createFromTimestamp(
            $verifiedAt
        )
            ->addMinutes(
                self::RESET_EXPIRE_MINUTES
            )
            ->isFuture();
    }
}
