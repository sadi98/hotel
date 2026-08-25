<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Password</title>

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
                                        <i class="bi bi-key-fill"></i>
                                    </span>
                                    Nama Aplikasi
                                </a>

                                <h1>Lupa password?</h1>

                                <p>
                                    Jangan khawatir. Masukkan email yang terdaftar
                                    dan kami akan mengirimkan kode OTP untuk
                                    memverifikasi akun Anda.
                                </p>

                                <ul class="auth-benefits">
                                    <li>
                                        <i class="bi bi-envelope-check-fill"></i>
                                        Kode OTP dikirim melalui email
                                    </li>
                                    <li>
                                        <i class="bi bi-clock-fill"></i>
                                        Kode memiliki batas waktu penggunaan
                                    </li>
                                    <li>
                                        <i class="bi bi-shield-check"></i>
                                        Jangan berikan kode kepada siapa pun
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
                                        <i class="bi bi-key-fill"></i>
                                    </span>
                                    Nama Aplikasi
                                </a>

                                <h2 class="auth-title">Reset password</h2>

                                <p class="auth-subtitle">
                                    Masukkan email yang digunakan ketika mendaftar.
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
                                            <strong>Permintaan belum berhasil.</strong>

                                            <ul class="alert-error-list">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('password.email') }}" method="POST" class="auth-form"
                                    novalidate>
                                    @csrf

                                    <div class="mb-4">
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

                                    <button type="submit" class="btn btn-auth w-100">
                                        <span class="button-text">
                                            Kirim kode OTP
                                        </span>

                                        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>

                                        <i class="bi bi-send button-icon"></i>
                                    </button>
                                </form>

                                <p class="auth-footer-text">
                                    Ingat password Anda?
                                    <a href="{{ route('login') }}" class="auth-link">
                                        Kembali ke login
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
        document.querySelector('.auth-form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            const text = button.querySelector('.button-text');
            const spinner = button.querySelector('.spinner-border');
            const icon = button.querySelector('.button-icon');

            button.disabled = true;
            text.textContent = 'Mengirim OTP...';
            spinner.classList.remove('d-none');
            icon.classList.add('d-none');
        });
    </script>
</body>

</html>
