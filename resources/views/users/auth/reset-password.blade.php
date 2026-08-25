@extends('users.auth.layouts.auth')

@section('title', 'Create New Password - Kayu Manis Restaurant')

@section('content')
    <div class="ayaka-auth-state-icon-wrapper">
        <span class="ayaka-auth-state-icon">
            <i class="bi bi-shield-lock"></i>
        </span>
    </div>

    <div class="ayaka-auth-heading is-centered">
        <span class="ayaka-auth-eyebrow">
            Secure your account
        </span>

        <h2 class="ayaka-auth-heading-title">
            Create a new password
        </h2>

        <p class="ayaka-auth-heading-description">
            Choose a strong and unique password that you have not
            previously used for this account.
        </p>
    </div>

    <form class="ayaka-auth-form" id="resetPasswordForm" action="{{ route('password.reset.update') }}" method="POST"
        novalidate>
        @csrf

        <div class="ayaka-auth-field">
            <label class="ayaka-auth-label" for="password">
                New password
            </label>

            <div class="ayaka-auth-input-wrapper">
                <span class="ayaka-auth-input-icon">
                    <i class="bi bi-lock"></i>
                </span>

                <input class="ayaka-auth-input @error('password') is-invalid @enderror" id="password" type="password"
                    name="password" placeholder="Enter your new password" autocomplete="new-password" minlength="8"
                    required autofocus>

                <button class="ayaka-auth-password-toggle" type="button" data-ayaka-password-target="password"
                    aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="ayaka-auth-field-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <div class="ayaka-auth-strength" id="ayakaPasswordStrength" data-strength="0">
                <div class="ayaka-auth-strength-bars">
                    <span class="ayaka-auth-strength-bar"></span>
                    <span class="ayaka-auth-strength-bar"></span>
                    <span class="ayaka-auth-strength-bar"></span>
                    <span class="ayaka-auth-strength-bar"></span>
                </div>

                <small class="ayaka-auth-strength-text" id="ayakaPasswordStrengthText">
                    Use at least 8 characters.
                </small>
            </div>
        </div>

        <div class="ayaka-auth-field">
            <label class="ayaka-auth-label" for="password_confirmation">
                Confirm new password
            </label>

            <div class="ayaka-auth-input-wrapper">
                <span class="ayaka-auth-input-icon">
                    <i class="bi bi-shield-lock"></i>
                </span>

                <input class="ayaka-auth-input" id="password_confirmation" type="password" name="password_confirmation"
                    placeholder="Enter your new password again" autocomplete="new-password" minlength="8" required>

                <button class="ayaka-auth-password-toggle" type="button" data-ayaka-password-target="password_confirmation"
                    aria-label="Show password confirmation">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            <div class="ayaka-auth-match-message" id="ayakaPasswordMatch"></div>
        </div>

        <button class="hotel-btn hotel-btn-add hotel-btn-lg hotel-btn-w-100 ayaka-auth-submit" type="submit"
            data-ayaka-submit data-loading-text="Updating password...">
            <svg class="ayaka-auth-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 6 9 17l-5-5"></path>
            </svg>

            <span data-ayaka-submit-text>
                Reset password
            </span>
        </button>

        <div class="ayaka-auth-security-note">
            <i class="bi bi-shield-check"></i>

            <p>
                Use a combination of uppercase and lowercase letters,
                numbers, and symbols to protect your account.
            </p>
        </div>

        <p class="ayaka-auth-switch">
            Remember your password?

            <a class="ayaka-auth-switch-link" href="{{ route('login') }}">
                Return to sign in
            </a>
        </p>
    </form>
@endsection
