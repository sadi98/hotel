<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('users/css/auth.css') }}">
</head>

<body class="auth-body">
    <main class="auth-page">
        <div class="auth-container">
            <div class="auth-card">
                <div class="row g-0 auth-row">

                    {{-- Bagian banner kiri --}}
                    <div class="col-lg-6 auth-banner-column">
                        <section class="auth-banner">
                            <div class="auth-banner-content">
                                <a href="{{ url('/') }}" class="auth-brand">
                                    <span class="auth-brand-icon">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </span>
                                    Nama Aplikasi
                                </a>

                                <h1>Selamat datang kembali.</h1>

                                <p>
                                    Masuk ke akun Anda untuk mengakses seluruh
                                    fitur dan layanan yang tersedia.
                                </p>

                                <ul class="auth-benefits">
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Data akun tersimpan dengan aman
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Tampilan responsif di semua perangkat
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Proses masuk cepat dan mudah
                                    </li>
                                </ul>
                            </div>
                        </section>
                    </div>

                    {{-- Bagian form --}}
                    <div class="col-lg-6">
                        <section class="auth-form-section">
                            <div class="auth-form-wrapper">

                                <a href="{{ url('/') }}" class="auth-mobile-brand">
                                    <span class="auth-brand-icon">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </span>
                                    Nama Aplikasi
                                </a>

                                <h2 class="auth-title">Masuk ke akun</h2>

                                <p class="auth-subtitle">
                                    Silakan masukkan email dan password Anda.
                                </p>

                                {{-- Pesan berhasil --}}
                                @if (session('success'))
                                    <div class="alert alert-success auth-alert" role="alert">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>{{ session('success') }}</div>
                                    </div>
                                @endif

                                {{-- Pesan status Laravel --}}
                                @if (session('status'))
                                    <div class="alert alert-success auth-alert" role="alert">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <div>{{ session('status') }}</div>
                                    </div>
                                @endif

                                {{-- Pesan error umum --}}
                                @if (session('error'))
                                    <div class="alert alert-danger auth-alert" role="alert">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <div>{{ session('error') }}</div>
                                    </div>
                                @endif

                                {{-- Menampilkan seluruh error validasi --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger auth-alert" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill"></i>

                                        <div>
                                            <strong>Login belum berhasil.</strong>

                                            <ul class="alert-error-list">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('login') }}" method="POST" class="auth-form" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            Email
                                        </label>

                                        <div class="auth-input-group">
                                            <i class="bi bi-envelope input-icon"></i>

                                            <input type="email" id="email" name="email"
                                                value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="nama@email.com" autocomplete="email" required autofocus>
                                        </div>

                                        @error('email')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="password" class="form-label">
                                                Password
                                            </label>

                                            <a href="{{ route('password.request') }}" class="auth-link small">
                                                Lupa password?
                                            </a>
                                        </div>

                                        <div class="auth-input-group">
                                            <i class="bi bi-lock input-icon"></i>

                                            <input type="password" id="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Masukkan password" autocomplete="current-password"
                                                required>

                                            <button type="button" class="password-toggle"
                                                data-password-toggle="password" aria-label="Tampilkan password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>

                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="remember" name="remember" value="1"
                                                class="form-check-input" {{ old('remember') ? 'checked' : '' }}>

                                            <label for="remember" class="form-check-label">
                                                Ingat saya
                                            </label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-auth w-100">
                                        <span class="button-text">
                                            Masuk
                                        </span>

                                        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>

                                        <i class="bi bi-arrow-right button-icon"></i>
                                    </button>
                                </form>

                                <p class="auth-footer-text">
                                    Belum memiliki akun?
                                    <a href="{{ route('register') }}" class="auth-link">
                                        Daftar sekarang
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
                const inputId = button.getAttribute('data-password-toggle');
                const input = document.getElementById(inputId);
                const icon = button.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                    button.setAttribute('aria-label', 'Sembunyikan password');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                    button.setAttribute('aria-label', 'Tampilkan password');
                }
            });
        });

        document.querySelectorAll('.auth-form').forEach(function(form) {
            form.addEventListener('submit', function() {
                const button = form.querySelector('button[type="submit"]');
                const text = button.querySelector('.button-text');
                const spinner = button.querySelector('.spinner-border');
                const icon = button.querySelector('.button-icon');

                button.disabled = true;
                text.textContent = 'Memproses...';
                spinner.classList.remove('d-none');

                if (icon) {
                    icon.classList.add('d-none');
                }
            });
        });
    </script>
</body>

</html>
