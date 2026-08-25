@extends('users.auth.layouts.auth')

@section('title', 'Sign In - Kayu Manis Restaurant')

@section('content')
    <div class="ayaka-auth-heading">
        <span class="ayaka-auth-eyebrow">
            Welcome back
        </span>

        <h2 class="ayaka-auth-heading-title">
            Sign in to your account
        </h2>

        <p class="ayaka-auth-heading-description">
            Sign in to order your favourite dishes, manage reservations,
            and review your dining history.
        </p>
    </div>

    <form class="ayaka-auth-form" action="{{ route('login.store') }}" method="POST" novalidate>
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

        <div class="ayaka-auth-field">
            <div class="ayaka-auth-label-row">
                <label class="ayaka-auth-label" for="password">
                    Password
                </label>

                <a class="ayaka-auth-forgot-link" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            </div>

            <div class="ayaka-auth-input-wrapper">
                <span class="ayaka-auth-input-icon">
                    <i class="bi bi-lock"></i>
                </span>

                <input class="ayaka-auth-input @error('password') is-invalid @enderror" id="password" type="password"
                    name="password" placeholder="Enter your password" autocomplete="current-password" required>

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
        </div>

        <div class="ayaka-auth-check">
            <input class="ayaka-auth-checkbox" id="remember" type="checkbox" name="remember" value="1"
                {{ old('remember') ? 'checked' : '' }}>

            <label class="ayaka-auth-check-label" for="remember">
                Remember me on this device
            </label>
        </div>

        <button class="hotel-btn hotel-btn-add hotel-btn-lg hotel-btn-w-100 ayaka-auth-submit" type="submit"
            data-ayaka-submit data-loading-text="Signing in...">
            <span data-ayaka-submit-text>
                Sign in
            </span>

            <svg class="ayaka-auth-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14"></path>
                <path d="m13 6 6 6-6 6"></path>
            </svg>
        </button>

        <div class="ayaka-auth-divider">
            <span>or continue with</span>
        </div>

        <a class="hotel-btn hotel-btn-neutral hotel-btn-lg hotel-btn-w-100 ayaka-auth-google-button"
            href="{{ route('google.redirect') }}">
            <svg width="20" height="20" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill="#4285F4"
                    d="M17.64 9.205c0-.638-.057-1.252-.164-1.841H9v3.481h4.844a4.14 4.14 0 0 1-1.797 2.716v2.258h2.909c1.702-1.567 2.684-3.877 2.684-6.614Z" />

                <path fill="#34A853"
                    d="M9 18c2.43 0 4.467-.806 5.956-2.181l-2.909-2.258c-.806.54-1.835.859-3.047.859-2.344 0-4.328-1.585-5.037-3.714H.956v2.332A9 9 0 0 0 9 18Z" />

                <path fill="#FBBC05"
                    d="M3.963 10.706A5.41 5.41 0 0 1 3.682 9c0-.592.102-1.167.281-1.706V4.962H.956A9 9 0 0 0 0 9c0 1.452.347 2.827.956 4.038l3.007-2.332Z" />

                <path fill="#EA4335"
                    d="M9 3.58c1.321 0 2.507.454 3.441 1.345l2.582-2.582C13.463.891 11.426 0 9 0A9 9 0 0 0 .956 4.962l3.007 2.332C4.672 5.165 6.656 3.58 9 3.58Z" />
            </svg>

            <span>Continue with Google</span>
        </a>

        <p class="ayaka-auth-switch">
            Do not have an account?

            <a class="ayaka-auth-switch-link" href="{{ route('register') }}">
                Create an account
            </a>
        </p>
    </form>
@endsection
