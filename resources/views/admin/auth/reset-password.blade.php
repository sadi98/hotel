@extends('admin.auth.layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="management-auth-icon">
        <i class="bi bi-shield-lock"></i>
    </div>

    <h2>Create new password</h2>

    <p class="management-auth-description">
        Use at least eight characters with uppercase,
        lowercase, and a number.
    </p>

    <form class="management-auth-form" action="{{ route('staff.password.reset.update') }}" method="POST">
        @csrf

        <div class="management-auth-field">
            <label for="password">
                New password
            </label>

            <div class="management-auth-input-wrap">
                <i class="bi bi-lock"></i>

                <input class="management-auth-input" id="password" type="password" name="password"
                    placeholder="New password" required autofocus>

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

        <div class="management-auth-field">
            <label for="password_confirmation">
                Confirm new password
            </label>

            <div class="management-auth-input-wrap">
                <i class="bi bi-shield-check"></i>

                <input class="management-auth-input" id="password_confirmation" type="password" name="password_confirmation"
                    placeholder="Confirm new password" required>

                <button class="management-auth-toggle" type="button" data-password-toggle="password_confirmation">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button class="management-auth-button" type="submit">
            Reset password
            <i class="bi bi-check-circle"></i>
        </button>
    </form>
@endsection
