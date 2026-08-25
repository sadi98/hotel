@extends('users.auth.layouts.auth')

@section('title', 'Forgot Password - Kayu Manis Restaurant')

@section('content')
    <div class="ayaka-auth-back-wrapper">
        <a class="hotel-btn hotel-btn-neutral hotel-btn-sm ayaka-auth-back-button" href="{{ route('login') }}">
            <svg class="ayaka-auth-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m15 18-6-6 6-6"></path>
                <path d="M9 12h10"></path>
            </svg>

            Back to sign in
        </a>
    </div>

    <div class="ayaka-auth-state-icon-wrapper">
        <span class="ayaka-auth-state-icon">
            <i class="bi bi-key"></i>
        </span>
    </div>

    <div class="ayaka-auth-heading is-centered">
        <span class="ayaka-auth-eyebrow">
            Account recovery
        </span>

        <h2 class="ayaka-auth-heading-title">
            Forgot your password?
        </h2>

        <p class="ayaka-auth-heading-description">
            Enter the email address associated with your account.
            We will send you a one-time verification code.
        </p>
    </div>

    <form class="ayaka-auth-form" action="{{ route('password.email') }}" method="POST" novalidate>
        @csrf

        <div class="ayaka-auth-field">
            <label class="ayaka-auth-label" for="email">
                Email address
            </label>

            <div class="ayaka-auth-input-wrapper">
                <span class="ayaka-auth-input-icon">
                    <i class="bi bi-envelope"></i>
                </span>

                <input class="ayaka-auth-input @error('email') is-invalid @enderror" id="email" type="email"
                    name="email" value="{{ old('email') }}" placeholder="Enter your email address" autocomplete="email"
                    required autofocus>
            </div>

            @error('email')
                <div class="ayaka-auth-field-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <button class="hotel-btn hotel-btn-add hotel-btn-lg hotel-btn-w-100 ayaka-auth-submit" type="submit"
            data-ayaka-submit data-loading-text="Sending code...">
            <span data-ayaka-submit-text>
                Send verification code
            </span>

            <svg class="ayaka-auth-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m22 2-7 20-4-9-9-4Z"></path>
                <path d="M22 2 11 13"></path>
            </svg>
        </button>

        <div class="ayaka-auth-security-note">
            <i class="bi bi-shield-check"></i>

            <p>
                Your verification code is only valid for a limited time.
                Never share this code with anyone.
            </p>
        </div>

        <p class="ayaka-auth-switch">
            Remember your password?

            <a class="ayaka-auth-switch-link" href="{{ route('login') }}">
                Sign in
            </a>
        </p>
    </form>
@endsection
