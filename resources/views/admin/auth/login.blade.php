@extends('admin.auth.layouts.auth')

@section('title', 'Management Login')

@section('content')
    <div class="management-auth-icon">
        <i class="bi bi-shield-lock"></i>
    </div>

    <h2>Welcome back</h2>

    <p class="management-auth-description">
        Sign in using your administrator or staff account.
    </p>

    <form class="management-auth-form" action="{{ route('login.staff.store') }}" method="POST">
        @csrf

        <div class="management-auth-field">
            <label for="login">
                Email or username
            </label>

            <div class="management-auth-input-wrap">
                <i class="bi bi-person"></i>

                <input class="management-auth-input" id="login" type="text" name="login"
                    value="{{ old('login') }}" placeholder="Email or username" required autofocus>
            </div>

            @error('login')
                <div class="management-auth-error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="management-auth-field">
            <label for="password">Password</label>

            <div class="management-auth-input-wrap">
                <i class="bi bi-lock"></i>

                <input class="management-auth-input" id="password" type="password" name="password" placeholder="Password"
                    required>

                <button class="management-auth-toggle" type="button" data-password-toggle="password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="management-auth-error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="management-auth-options">
            <label>
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <a class="management-auth-link" href="{{ route('staff.password.request') }}">
                Forgot password?
            </a>
        </div>

        <button class="management-auth-button" type="submit">
            Sign in
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>
@endsection
