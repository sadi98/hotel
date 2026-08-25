<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi OTP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('users/css/auth.css') }}">
</head>

<body class="auth-body">
    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-card">
                <div class="row g-0 auth-row">

                    <div class="col-lg-6 auth-banner-column">
                        <section class="auth-banner">
                            <div class="auth-banner-content">
                                <a href="{{ url('/') }}" class="auth-brand">
                                    <span class="auth-brand-icon">
                                        <i class="bi bi-envelope-check-fill"></i>
                                    </span>
                                    Nama Aplikasi
                                </a>

                                <h1>Periksa email Anda.</h1>

                                <p>
                                    Kami telah mengirimkan kode OTP sebanyak
                                    6 angka. Masukkan kode tersebut untuk
                                    melanjutkan proses reset password.
                                </p>

                                <ul class="auth-benefits">
                                    <li>
                                        <i class="bi bi-shield-lock-fill"></i>
                                        OTP hanya boleh digunakan satu kali
                                    </li>
                                    <li>
                                        <i class="bi bi-clock-history"></i>
                                        OTP memiliki batas waktu
                                    </li>
                                    <li>
                                        <i class="bi bi-incognito"></i>
                                        Jangan berikan OTP kepada siapa pun
                                    </li>
                                </ul>
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-6">
                        <section class="auth-form-section">
                            <div class="auth-form-wrapper">

                                <a href="{{ url('/') }}" class="auth-mobile-brand">
                                    <span class="auth-brand-icon">
                                        <i class="bi bi-envelope-check-fill"></i>
                                    </span>
                                    Nama Aplikasi
                                </a>

                                <h2 class="auth-title">Verifikasi OTP</h2>

                                <p class="auth-subtitle">
                                    Masukkan kode OTP yang dikirim ke

                                    <strong>
                                        {{ session('reset_email') ?? (old('email') ?? 'email Anda') }}
                                    </strong>.
                                </p>

                                @if (session('success'))
                                    <div class="alert alert-success auth-alert" role="alert">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>{{ session('success') }}</div>
                                    </div>
                                @endif

                                @if (session('status'))
                                    <div class="alert alert-success auth-alert" role="alert">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>{{ session('status') }}</div>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger auth-alert" role="alert">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <div>{{ session('error') }}</div>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger auth-alert" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill"></i>

                                        <div>
                                            <strong>Verifikasi belum berhasil.</strong>

                                            <ul class="alert-error-list">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <form id="otpForm" action="{{ route('password.otp.verify') }}" method="POST"
                                    class="auth-form" novalidate>
                                    @csrf

                                    <input type="hidden" name="email"
                                        value="{{ session('reset_email') ?? old('email') }}">

                                    <input type="hidden" name="otp" id="otpValue" value="{{ old('otp') }}">

                                    <div class="mb-4">
                                        <label class="form-label d-block text-center mb-3">
                                            Kode OTP
                                        </label>

                                        <div class="otp-container">
                                            @for ($i = 0; $i < 6; $i++)
                                                <input type="text" inputmode="numeric" maxlength="1"
                                                    class="otp-input @error('otp') is-invalid @enderror"
                                                    aria-label="Angka OTP ke-{{ $i + 1 }}"
                                                    autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}">
                                            @endfor
                                        </div>

                                        @error('otp')
                                            <span class="otp-error">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror

                                        <div id="clientOtpError" class="otp-error d-none">
                                            Kode OTP harus terdiri dari 6 angka.
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-auth w-100">
                                        <span class="button-text">
                                            Verifikasi kode OTP
                                        </span>

                                        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>

                                        <i class="bi bi-shield-check button-icon"></i>
                                    </button>
                                </form>

                                <div class="otp-timer mt-4">
                                    <div id="timerText">
                                        Kirim ulang kode dalam
                                        <strong id="countdown">01:00</strong>
                                    </div>

                                    <form action="{{ route('password.otp.resend') }}" method="POST" class="mt-2">
                                        @csrf

                                        <input type="hidden" name="email"
                                            value="{{ session('reset_email') ?? old('email') }}">

                                        <button type="submit" id="resendButton" class="resend-button" disabled>
                                            Kirim ulang kode OTP
                                        </button>
                                    </form>
                                </div>

                                <p class="auth-footer-text">
                                    Email yang dimasukkan salah?
                                    <a href="{{ route('password.request') }}" class="auth-link">
                                        Ganti email
                                    </a>
                                </p>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const otpForm = document.getElementById('otpForm');
        const otpInputs = Array.from(
            document.querySelectorAll('.otp-input')
        );

        const otpValue = document.getElementById('otpValue');
        const clientOtpError = document.getElementById('clientOtpError');

        function updateOtpValue() {
            otpValue.value = otpInputs
                .map(function(input) {
                    return input.value;
                })
                .join('');
        }

        otpInputs.forEach(function(input, index) {
            input.addEventListener('input', function() {
                input.value = input.value.replace(/\D/g, '');

                if (input.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }

                updateOtpValue();
                clientOtpError.classList.add('d-none');
            });

            input.addEventListener('keydown', function(event) {
                if (
                    event.key === 'Backspace' &&
                    input.value === '' &&
                    index > 0
                ) {
                    otpInputs[index - 1].focus();
                }

                if (event.key === 'ArrowLeft' && index > 0) {
                    otpInputs[index - 1].focus();
                }

                if (
                    event.key === 'ArrowRight' &&
                    index < otpInputs.length - 1
                ) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('paste', function(event) {
                event.preventDefault();

                const pastedValue = (
                        event.clipboardData || window.clipboardData
                    )
                    .getData('text')
                    .replace(/\D/g, '')
                    .slice(0, 6);

                pastedValue.split('').forEach(function(number, pastedIndex) {
                    if (otpInputs[pastedIndex]) {
                        otpInputs[pastedIndex].value = number;
                    }
                });

                updateOtpValue();

                const nextEmptyIndex = otpInputs.findIndex(function(item) {
                    return item.value === '';
                });

                if (nextEmptyIndex >= 0) {
                    otpInputs[nextEmptyIndex].focus();
                } else {
                    otpInputs[otpInputs.length - 1].focus();
                }
            });
        });

        const oldOtp = otpValue.value.replace(/\D/g, '').slice(0, 6);

        if (oldOtp) {
            oldOtp.split('').forEach(function(number, index) {
                if (otpInputs[index]) {
                    otpInputs[index].value = number;
                }
            });
        }

        otpForm.addEventListener('submit', function(event) {
            updateOtpValue();

            if (!/^\d{6}$/.test(otpValue.value)) {
                event.preventDefault();

                clientOtpError.classList.remove('d-none');

                otpInputs.forEach(function(input) {
                    input.classList.add('is-invalid');
                });

                const firstEmpty = otpInputs.find(function(input) {
                    return input.value === '';
                });

                if (firstEmpty) {
                    firstEmpty.focus();
                }

                return;
            }

            const button = otpForm.querySelector('button[type="submit"]');
            const text = button.querySelector('.button-text');
            const spinner = button.querySelector('.spinner-border');
            const icon = button.querySelector('.button-icon');

            button.disabled = true;
            text.textContent = 'Memverifikasi...';
            spinner.classList.remove('d-none');
            icon.classList.add('d-none');
        });

        let remainingSeconds = 60;

        const countdown = document.getElementById('countdown');
        const timerText = document.getElementById('timerText');
        const resendButton = document.getElementById('resendButton');

        const countdownInterval = setInterval(function() {
            remainingSeconds--;

            const minutes = String(
                Math.floor(remainingSeconds / 60)
            ).padStart(2, '0');

            const seconds = String(
                remainingSeconds % 60
            ).padStart(2, '0');

            countdown.textContent = minutes + ':' + seconds;

            if (remainingSeconds <= 0) {
                clearInterval(countdownInterval);
                timerText.classList.add('d-none');
                resendButton.disabled = false;
            }
        }, 1000);

        if (otpInputs.length > 0 && !oldOtp) {
            otpInputs[0].focus();
        }
    </script>
</body>

</html>
