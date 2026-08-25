<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Akun</title>

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
                                        <i class="bi bi-person-plus-fill"></i>
                                    </span>
                                    Nama Aplikasi
                                </a>

                                <h1>Buat akun baru.</h1>

                                <p>
                                    Daftarkan akun Anda untuk mulai menggunakan
                                    seluruh fitur dalam aplikasi.
                                </p>

                                <ul class="auth-benefits">
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Proses pendaftaran mudah
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Perlindungan data pengguna
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Bisa diakses dari berbagai perangkat
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
                                        <i class="bi bi-person-plus-fill"></i>
                                    </span>
                                    Nama Aplikasi
                                </a>

                                <h2 class="auth-title">Daftar akun</h2>

                                <p class="auth-subtitle">
                                    Lengkapi data berikut untuk membuat akun baru.
                                </p>

                                @if (session('success'))
                                    <div class="alert alert-success auth-alert" role="alert">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>{{ session('success') }}</div>
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
                                            <strong>Pendaftaran belum berhasil.</strong>

                                            <ul class="alert-error-list">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('register') }}" method="POST" class="auth-form" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            Nama lengkap
                                        </label>

                                        <div class="auth-input-group">
                                            <i class="bi bi-person input-icon"></i>

                                            <input type="text" id="name" name="name"
                                                value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Masukkan nama lengkap" autocomplete="name" required
                                                autofocus>
                                        </div>

                                        @error('name')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            Email
                                        </label>

                                        <div class="auth-input-group">
                                            <i class="bi bi-envelope input-icon"></i>

                                            <input type="email" id="email" name="email"
                                                value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="nama@email.com" autocomplete="email" required>
                                        </div>

                                        @error('email')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            Password
                                        </label>

                                        <div class="auth-input-group">
                                            <i class="bi bi-lock input-icon"></i>

                                            <input type="password" id="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Minimal 8 karakter" autocomplete="new-password" required>

                                            <button type="button" class="password-toggle"
                                                data-password-toggle="password" aria-label="Tampilkan password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>

                                        <div class="password-strength">
                                            <div id="passwordStrengthBar" class="password-strength-bar"></div>
                                        </div>

                                        <small id="passwordStrengthText" class="password-strength-text">
                                            Gunakan minimal 8 karakter.
                                        </small>

                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">
                                            Konfirmasi password
                                        </label>

                                        <div class="auth-input-group">
                                            <i class="bi bi-shield-lock input-icon"></i>

                                            <input type="password" id="password_confirmation"
                                                name="password_confirmation" class="form-control"
                                                placeholder="Ulangi password" autocomplete="new-password" required>

                                            <button type="button" class="password-toggle"
                                                data-password-toggle="password_confirmation"
                                                aria-label="Tampilkan konfirmasi password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>

                                        <div id="confirmationFeedback" class="invalid-feedback d-none">
                                            Password dan konfirmasi password tidak sama.
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-auth w-100">
                                        <span class="button-text">
                                            Daftar akun
                                        </span>

                                        <span class="spinner-border spinner-border-sm d-none"
                                            aria-hidden="true"></span>

                                        <i class="bi bi-arrow-right button-icon"></i>
                                    </button>
                                </form>

                                <p class="auth-footer-text">
                                    Sudah memiliki akun?
                                    <a href="{{ route('login') }}" class="auth-link">
                                        Masuk
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
        document.querySelectorAll('[data-password-toggle]').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = document.getElementById(
                    button.getAttribute('data-password-toggle')
                );

                const icon = button.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
        });

        const password = document.getElementById('password');
        const confirmation = document.getElementById('password_confirmation');
        const strengthBar = document.getElementById('passwordStrengthBar');
        const strengthText = document.getElementById('passwordStrengthText');
        const confirmationFeedback = document.getElementById(
            'confirmationFeedback'
        );

        function checkPasswordStrength() {
            const value = password.value;
            let score = 0;

            if (value.length >= 8) score++;
            if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
            if (/\d/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;

            const strengths = [{
                    width: '0%',
                    color: '#e9edf3',
                    text: 'Gunakan minimal 8 karakter.'
                },
                {
                    width: '25%',
                    color: '#dc3545',
                    text: 'Password sangat lemah.'
                },
                {
                    width: '50%',
                    color: '#fd7e14',
                    text: 'Password cukup lemah.'
                },
                {
                    width: '75%',
                    color: '#ffc107',
                    text: 'Password cukup kuat.'
                },
                {
                    width: '100%',
                    color: '#198754',
                    text: 'Password kuat.'
                }
            ];

            const result = strengths[score];

            strengthBar.style.width = result.width;
            strengthBar.style.backgroundColor = result.color;
            strengthText.textContent = result.text;
        }

        function checkPasswordConfirmation() {
            if (confirmation.value === '') {
                confirmation.classList.remove('is-valid', 'is-invalid');
                confirmationFeedback.classList.add('d-none');
                return;
            }

            if (password.value === confirmation.value) {
                confirmation.classList.add('is-valid');
                confirmation.classList.remove('is-invalid');
                confirmationFeedback.classList.add('d-none');
            } else {
                confirmation.classList.add('is-invalid');
                confirmation.classList.remove('is-valid');
                confirmationFeedback.classList.remove('d-none');
            }
        }

        password.addEventListener('input', function() {
            checkPasswordStrength();
            checkPasswordConfirmation();
        });

        confirmation.addEventListener('input', checkPasswordConfirmation);

        document.querySelector('.auth-form').addEventListener('submit', function(event) {
            if (password.value !== confirmation.value) {
                event.preventDefault();
                checkPasswordConfirmation();
                confirmation.focus();
                return;
            }

            const button = this.querySelector('button[type="submit"]');
            const text = button.querySelector('.button-text');
            const spinner = button.querySelector('.spinner-border');
            const icon = button.querySelector('.button-icon');

            button.disabled = true;
            text.textContent = 'Mendaftarkan...';
            spinner.classList.remove('d-none');
            icon.classList.add('d-none');
        });
    </script>
</body>

</html>
