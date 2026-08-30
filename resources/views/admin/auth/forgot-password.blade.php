@extends('admin.auth.layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <a class="management-auth-back" href="{{ route('login.staff') }}">
        <i class="bi bi-arrow-left"></i>
        Back to login
    </a>

    <div class="management-auth-icon">
        <i class="bi bi-key"></i>
    </div>

    <h2>Forgot password?</h2>

    <p class="management-auth-description">
        Enter your management email. We will send
        a six-digit verification code.
    </p>

    <form class="management-auth-form" action="{{ route('staff.password.otp.send') }}" method="POST">
        @csrf

        <div class="management-auth-field">
            <label for="email">Email address</label>

            <div class="management-auth-input-wrap">
                <i class="bi bi-envelope"></i>

                <input class="management-auth-input" id="email" type="email" name="email"
                    value="{{ old('email') }}" placeholder="Management email" required autofocus>
            </div>

            @error('email')
                <div class="management-auth-error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button class="management-auth-button" type="submit">
            Send verification code
            <i class="bi bi-send"></i>
        </button>
    </form>
@endsection
