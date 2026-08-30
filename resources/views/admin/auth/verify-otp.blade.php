@extends('admin.auth.layouts.auth')

@section('title', 'Verify Code')

@push('style')
    <style>
        .management-otp {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 7px;
        }

        .management-otp input {
            width: 100%;
            min-width: 0;
            height: 55px;
            padding: 0;
            border: 1px solid #d7e3e9;
            border-radius: 12px;
            outline: none;
            color: #075985;
            font-size: 23px;
            font-weight: bold;
            text-align: center;
        }

        .management-otp input:focus {
            border-color: #0ea5b7;
            box-shadow: 0 0 0 4px rgba(14, 165, 183, .12);
        }

        .management-resend {
            margin-top: 18px;
            text-align: center;
        }

        .management-resend button {
            border: 0;
            background: transparent;
            color: #075985;
            font-weight: bold;
            cursor: pointer;
        }

        @media (max-width: 400px) {
            .management-otp {
                gap: 4px;
            }

            .management-otp input {
                height: 47px;
                border-radius: 9px;
                font-size: 19px;
            }
        }
    </style>
@endpush

@section('content')
    <a class="management-auth-back" href="{{ route('staff.password.request') }}">
        <i class="bi bi-arrow-left"></i>
        Change email
    </a>

    <div class="management-auth-icon">
        <i class="bi bi-envelope-check"></i>
    </div>

    <h2>Verify your code</h2>

    <p class="management-auth-description">
        Enter the six-digit code sent to
        <strong>{{ $email }}</strong>.
    </p>

    <form class="management-auth-form" id="otpForm" action="{{ route('staff.password.otp.verify') }}" method="POST">
        @csrf

        <input id="otpValue" type="hidden" name="otp">

        <div class="management-otp">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" inputmode="numeric" maxlength="1" aria-label="OTP digit {{ $i + 1 }}">
            @endfor
        </div>

        @error('otp')
            <div class="management-auth-error">
                {{ $message }}
            </div>
        @enderror

        <button class="management-auth-button" type="submit">
            Verify code
            <i class="bi bi-shield-check"></i>
        </button>
    </form>

    <form class="management-resend" action="{{ route('staff.password.otp.resend') }}" method="POST">
        @csrf

        <button type="submit">
            Resend code
        </button>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {
                var form = document.getElementById(
                    'otpForm'
                );

                var hiddenInput = document.getElementById(
                    'otpValue'
                );

                var inputs = Array.from(
                    document.querySelectorAll(
                        '.management-otp input'
                    )
                );

                function updateOtp() {
                    hiddenInput.value = inputs
                        .map(function(input) {
                            return input.value;
                        })
                        .join('');
                }

                inputs.forEach(function(input, index) {
                    input.addEventListener(
                        'input',
                        function() {
                            input.value = input.value
                                .replace(/\D/g, '')
                                .slice(0, 1);

                            updateOtp();

                            if (
                                input.value &&
                                index < inputs.length - 1
                            ) {
                                inputs[index + 1].focus();
                            }
                        }
                    );

                    input.addEventListener(
                        'keydown',
                        function(event) {
                            if (
                                event.key === 'Backspace' &&
                                !input.value &&
                                index > 0
                            ) {
                                inputs[index - 1].focus();
                            }
                        }
                    );

                    input.addEventListener(
                        'paste',
                        function(event) {
                            var value = event.clipboardData
                                .getData('text')
                                .replace(/\D/g, '')
                                .slice(0, 6);

                            if (!value) {
                                return;
                            }

                            event.preventDefault();

                            inputs.forEach(function(
                                otpInput,
                                otpIndex
                            ) {
                                otpInput.value =
                                    value[otpIndex] || '';
                            });

                            updateOtp();
                        }
                    );
                });

                form.addEventListener(
                    'submit',
                    function(event) {
                        updateOtp();

                        if (
                            !/^\d{6}$/.test(
                                hiddenInput.value
                            )
                        ) {
                            event.preventDefault();

                            alert(
                                'Please enter all six digits.'
                            );
                        }
                    }
                );

                inputs[0].focus();
            }
        );
    </script>
@endpush
