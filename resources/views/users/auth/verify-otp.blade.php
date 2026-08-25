@extends('users.auth.layouts.auth')

@section('title', 'Verify Your Code - Kayu Manis Restaurant')

@push('styles')
    <style>
        .ayaka-auth-otp-email {
            display: inline;
            overflow-wrap: anywhere;
            color: var(--ayaka-auth-primary);
            font-weight: 800;
        }

        .ayaka-auth-otp-field {
            display: grid;
            gap: 9px;
        }

        .ayaka-auth-otp-label {
            color: var(--ayaka-auth-text);
            font-size: 11px;
            font-weight: 750;
            text-align: center;
        }

        .ayaka-auth-otp-container {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 8px;
            width: 100%;
            max-width: 390px;
            margin: 0 auto;
        }

        .ayaka-auth-otp-input {
            width: 100%;
            min-width: 0;
            aspect-ratio: 0.88;
            padding: 0;
            border: 1px solid var(--ayaka-auth-border);
            border-radius: 13px;
            outline: none;
            background: #f8fbfd;
            color: var(--ayaka-auth-primary);
            font-family: "DM Sans", sans-serif;
            font-size: clamp(20px, 4vw, 27px);
            font-weight: 850;
            line-height: 1;
            text-align: center;
            caret-color: var(--ayaka-auth-secondary);
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .ayaka-auth-otp-input:hover {
            border-color: #acd4df;
            background: #ffffff;
        }

        .ayaka-auth-otp-input:focus {
            border-color: var(--ayaka-auth-secondary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.11);
            transform: translateY(-2px);
        }

        .ayaka-auth-otp-container.has-error .ayaka-auth-otp-input {
            border-color: #fb7185;
            background: #fffafa;
        }

        .ayaka-auth-otp-client-error {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: var(--ayaka-auth-danger);
            font-size: 9px;
            line-height: 1.5;
            text-align: center;
        }

        .ayaka-auth-otp-client-error.is-visible {
            display: flex;
        }

        .ayaka-auth-otp-timer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 11px;
            padding: 12px 13px;
            border: 1px solid #d8edf2;
            border-radius: 14px;
            background: #f2fafc;
        }

        .ayaka-auth-otp-timer-label {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #607485;
            font-size: 10px;
            font-weight: 650;
        }

        .ayaka-auth-otp-timer-label i {
            color: var(--ayaka-auth-primary);
            font-size: 14px;
        }

        .ayaka-auth-otp-timer-value {
            min-width: 52px;
            color: var(--ayaka-auth-primary);
            font-size: 13px;
            font-weight: 850;
            text-align: right;
        }

        .ayaka-auth-otp-timer.is-expired {
            border-color: #fed7aa;
            background: #fff7ed;
        }

        .ayaka-auth-otp-timer.is-expired .ayaka-auth-otp-timer-label,
        .ayaka-auth-otp-timer.is-expired .ayaka-auth-otp-timer-value {
            color: #c2410c;
        }

        .ayaka-auth-resend {
            display: grid;
            justify-items: center;
            gap: 8px;
            padding-top: 2px;
            text-align: center;
        }

        .ayaka-auth-resend-text {
            margin: 0;
            color: var(--ayaka-auth-muted);
            font-size: 10px;
        }

        .ayaka-auth-resend-form {
            margin: 0;
        }

        .ayaka-auth-resend-button {
            min-height: 37px;
        }

        .ayaka-auth-resend-button:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
        }

        @media (max-width: 575px) {
            .ayaka-auth-otp-container {
                gap: 6px;
            }

            .ayaka-auth-otp-input {
                min-height: 50px;
                aspect-ratio: auto;
                border-radius: 11px;
                font-size: 22px;
            }
        }

        @media (max-width: 380px) {
            .ayaka-auth-otp-container {
                gap: 4px;
            }

            .ayaka-auth-otp-input {
                min-height: 46px;
                border-radius: 9px;
                font-size: 20px;
            }
        }

        @media (max-width: 320px) {
            .ayaka-auth-otp-input {
                min-height: 42px;
                font-size: 18px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="ayaka-auth-back-wrapper">
        <a class="hotel-btn hotel-btn-neutral hotel-btn-sm ayaka-auth-back-button" href="{{ route('password.request') }}">
            <svg class="ayaka-auth-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m15 18-6-6 6-6"></path>
                <path d="M9 12h10"></path>
            </svg>

            Change email address
        </a>
    </div>

    <div class="ayaka-auth-state-icon-wrapper">
        <span class="ayaka-auth-state-icon">
            <i class="bi bi-envelope-check"></i>
        </span>
    </div>

    <div class="ayaka-auth-heading is-centered">
        <span class="ayaka-auth-eyebrow">
            Security verification
        </span>

        <h2 class="ayaka-auth-heading-title">
            Enter your verification code
        </h2>

        <p class="ayaka-auth-heading-description">
            We sent a six-digit verification code to

            <strong class="ayaka-auth-otp-email">
                {{ session('otp_email') ?? old('email', 'your email address') }}
            </strong>.
        </p>
    </div>

    <form class="ayaka-auth-form" id="ayakaOtpForm" action="{{ route('password.otp.verify') }}" method="POST" novalidate>
        @csrf

        <input type="hidden" name="email" value="{{ session('otp_email') ?? old('email') }}">

        <input type="hidden" name="otp" id="ayakaOtpValue" value="{{ old('otp') }}">

        <div class="ayaka-auth-otp-field">
            <label class="ayaka-auth-otp-label">
                Verification code
            </label>

            <div class="ayaka-auth-otp-container @error('otp') has-error @enderror" id="ayakaOtpContainer">
                @for ($i = 0; $i < 6; $i++)
                    <input class="ayaka-auth-otp-input" type="text" inputmode="numeric" pattern="[0-9]" maxlength="1"
                        autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                        aria-label="Verification code digit {{ $i + 1 }}" required>
                @endfor
            </div>

            @error('otp')
                <div class="ayaka-auth-field-error" style="justify-content: center;">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <div class="ayaka-auth-otp-client-error" id="ayakaOtpClientError">
                <i class="bi bi-exclamation-circle"></i>

                <span>
                    Please enter a valid six-digit verification code.
                </span>
            </div>
        </div>

        <div class="ayaka-auth-otp-timer" id="ayakaOtpTimerWrapper">
            <span class="ayaka-auth-otp-timer-label">
                <i class="bi bi-clock"></i>
                Your code expires in
            </span>

            <strong class="ayaka-auth-otp-timer-value" id="ayakaOtpTimer" data-duration="300">
                05:00
            </strong>
        </div>

        <button class="hotel-btn hotel-btn-add hotel-btn-lg hotel-btn-w-100 ayaka-auth-submit" type="submit"
            data-ayaka-submit data-loading-text="Verifying code...">
            <svg class="ayaka-auth-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 6 9 17l-5-5"></path>
            </svg>

            <span data-ayaka-submit-text>
                Verify code
            </span>
        </button>
    </form>

    <div class="ayaka-auth-resend">
        <p class="ayaka-auth-resend-text">
            Did not receive the verification code?
        </p>

        <form class="ayaka-auth-resend-form" id="ayakaResendOtpForm" action="{{ route('password.otp.resend') }}"
            method="POST">
            @csrf

            <input type="hidden" name="email" value="{{ session('otp_email') ?? old('email') }}">

            <button class="hotel-btn hotel-btn-neutral hotel-btn-sm ayaka-auth-resend-button" id="ayakaResendOtpButton"
                type="submit" disabled>
                <i class="bi bi-arrow-clockwise"></i>

                <span id="ayakaResendOtpText">
                    Resend code
                </span>
            </button>
        </form>
    </div>

    <div class="ayaka-auth-security-note" style="margin-top: 17px;">
        <i class="bi bi-info-circle"></i>

        <p>
            Check your spam or junk folder if you cannot find the
            verification email in your inbox.
        </p>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var otpForm = document.getElementById(
                'ayakaOtpForm'
            );

            var otpContainer = document.getElementById(
                'ayakaOtpContainer'
            );

            var otpInputs = Array.from(
                document.querySelectorAll(
                    '.ayaka-auth-otp-input'
                )
            );

            var otpValue = document.getElementById(
                'ayakaOtpValue'
            );

            var otpClientError = document.getElementById(
                'ayakaOtpClientError'
            );

            var timerElement = document.getElementById(
                'ayakaOtpTimer'
            );

            var timerWrapper = document.getElementById(
                'ayakaOtpTimerWrapper'
            );

            var resendForm = document.getElementById(
                'ayakaResendOtpForm'
            );

            var resendButton = document.getElementById(
                'ayakaResendOtpButton'
            );

            var resendText = document.getElementById(
                'ayakaResendOtpText'
            );

            /*
            |--------------------------------------------------------------------------
            | RESTORE OLD OTP VALUE
            |--------------------------------------------------------------------------
            */

            if (otpValue && otpValue.value) {
                var previousOtp = otpValue.value
                    .replace(/\D/g, '')
                    .slice(0, 6);

                otpInputs.forEach(function(input, index) {
                    input.value = previousOtp[index] || '';
                });
            }

            /*
            |--------------------------------------------------------------------------
            | COMBINE OTP INPUTS
            |--------------------------------------------------------------------------
            */

            function combineOtp() {
                var combinedValue = otpInputs
                    .map(function(input) {
                        return input.value;
                    })
                    .join('');

                if (otpValue) {
                    otpValue.value = combinedValue;
                }

                return combinedValue;
            }

            function clearOtpError() {
                if (otpContainer) {
                    otpContainer.classList.remove('has-error');
                }

                if (otpClientError) {
                    otpClientError.classList.remove('is-visible');
                }
            }

            /*
            |--------------------------------------------------------------------------
            | OTP INPUT BEHAVIOUR
            |--------------------------------------------------------------------------
            */

            otpInputs.forEach(function(input, index) {
                input.addEventListener('input', function() {
                    input.value = input.value
                        .replace(/\D/g, '')
                        .slice(-1);

                    clearOtpError();
                    combineOtp();

                    if (
                        input.value &&
                        index < otpInputs.length - 1
                    ) {
                        otpInputs[index + 1].focus();
                        otpInputs[index + 1].select();
                    }
                });

                input.addEventListener('keydown', function(event) {
                    if (
                        event.key === 'Backspace' &&
                        !input.value &&
                        index > 0
                    ) {
                        otpInputs[index - 1].focus();
                        otpInputs[index - 1].value = '';
                        combineOtp();
                    }

                    if (
                        event.key === 'ArrowLeft' &&
                        index > 0
                    ) {
                        event.preventDefault();
                        otpInputs[index - 1].focus();
                    }

                    if (
                        event.key === 'ArrowRight' &&
                        index < otpInputs.length - 1
                    ) {
                        event.preventDefault();
                        otpInputs[index + 1].focus();
                    }
                });

                input.addEventListener('focus', function() {
                    input.select();
                });

                input.addEventListener('paste', function(event) {
                    var pastedValue = (
                        event.clipboardData ||
                        window.clipboardData
                    ).getData('text');

                    var digits = pastedValue
                        .replace(/\D/g, '')
                        .slice(0, 6);

                    if (!digits) {
                        return;
                    }

                    event.preventDefault();

                    otpInputs.forEach(function(
                        otpInput,
                        otpIndex
                    ) {
                        otpInput.value =
                            digits[otpIndex] || '';
                    });

                    combineOtp();
                    clearOtpError();

                    var nextIndex = Math.min(
                        digits.length,
                        otpInputs.length
                    ) - 1;

                    otpInputs[Math.max(nextIndex, 0)].focus();
                });
            });

            /*
            |--------------------------------------------------------------------------
            | OTP FORM VALIDATION
            |--------------------------------------------------------------------------
            */

            if (otpForm) {
                otpForm.addEventListener('submit', function(event) {
                    var enteredOtp = combineOtp();

                    if (!/^\d{6}$/.test(enteredOtp)) {
                        event.preventDefault();

                        if (otpContainer) {
                            otpContainer.classList.add('has-error');
                        }

                        if (otpClientError) {
                            otpClientError.classList.add(
                                'is-visible'
                            );
                        }

                        var firstEmptyInput = otpInputs.find(
                            function(input) {
                                return !input.value;
                            }
                        );

                        (
                            firstEmptyInput ||
                            otpInputs[0]
                        ).focus();
                    }
                });
            }

            /*
            |--------------------------------------------------------------------------
            | EXPIRATION TIMER
            |--------------------------------------------------------------------------
            */

            var remainingSeconds = timerElement ?
                Number(timerElement.dataset.duration || 300) :
                300;

            function formatTimer(seconds) {
                var minutes = Math.floor(seconds / 60);
                var remainder = seconds % 60;

                return String(minutes).padStart(2, '0') +
                    ':' +
                    String(remainder).padStart(2, '0');
            }

            function expireOtpTimer() {
                if (timerElement) {
                    timerElement.textContent = 'Expired';
                }

                if (timerWrapper) {
                    timerWrapper.classList.add('is-expired');
                }

                if (resendButton) {
                    resendButton.disabled = false;
                }
            }

            function updateOtpTimer() {
                if (!timerElement) {
                    return;
                }

                if (remainingSeconds <= 0) {
                    expireOtpTimer();
                    return;
                }

                timerElement.textContent = formatTimer(
                    remainingSeconds
                );

                remainingSeconds--;

                window.setTimeout(updateOtpTimer, 1000);
            }

            updateOtpTimer();

            /*
            |--------------------------------------------------------------------------
            | RESEND LOADING STATE
            |--------------------------------------------------------------------------
            */

            if (resendForm) {
                resendForm.addEventListener('submit', function() {
                    if (resendButton) {
                        resendButton.disabled = true;
                        resendButton.classList.add('is-disabled');
                    }

                    if (resendText) {
                        resendText.textContent = 'Sending...';
                    }
                });
            }

            if (otpInputs.length) {
                var firstEmptyInput = otpInputs.find(
                    function(input) {
                        return !input.value;
                    }
                );

                (
                    firstEmptyInput ||
                    otpInputs[otpInputs.length - 1]
                ).focus();
            }
        });
    </script>
@endpush
