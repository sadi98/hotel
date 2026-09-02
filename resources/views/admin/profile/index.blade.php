@extends('admin.layouts.main')

@section('page_title', 'Profile Saya || ' . config('app.name'))

@section('meta_description', 'Kelola informasi akun, foto profile, dan keamanan akun.')

@section('header_title', 'Profile Saya')

@section('header_subtitle', 'Kelola informasi pribadi, foto profile, dan keamanan akun Anda.')

@section('header_icon', 'user')

{{-- Daftar nama ikon yang dapat digunakan mengikuti nama ikon Feather, misalnya:

activity
user
users
home
settings
shopping-cart
calendar
clipboard
file-text
database
package
credit-card --}}

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Profile Saya
    </li>
@endsection

@push('style')
    <style>
        .profile-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .profile-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .profile-card-header-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .profile-card-header-title svg {
            width: 18px;
            height: 18px;
        }

        .profile-cover {
            position: relative;
            height: 130px;
            background:
                radial-gradient(circle at top right,
                    rgba(255, 255, 255, 0.25),
                    transparent 35%),
                linear-gradient(135deg, #0061f2 0%, #6900c7 100%);
        }

        .profile-cover::before,
        .profile-cover::after {
            position: absolute;
            content: "";
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .profile-cover::before {
            top: -45px;
            right: 30px;
            width: 150px;
            height: 150px;
        }

        .profile-cover::after {
            bottom: -60px;
            left: -20px;
            width: 130px;
            height: 130px;
        }

        .profile-identity-body {
            position: relative;
            padding: 0 1.25rem 1.5rem;
            text-align: center;
        }

        .profile-avatar-wrapper {
            position: relative;
            width: 145px;
            height: 145px;
            margin: -72px auto 1rem;
        }

        .profile-avatar {
            width: 145px;
            height: 145px;
            object-fit: cover;
            background-color: #ffffff;
            border: 6px solid #ffffff;
            box-shadow: 0 0.4rem 1.5rem rgba(33, 40, 50, 0.2);
        }

        .profile-avatar-status {
            position: absolute;
            right: 10px;
            bottom: 10px;
            width: 20px;
            height: 20px;
            background-color: #00ac69;
            border: 4px solid #ffffff;
            border-radius: 50%;
        }

        .profile-name {
            margin-bottom: 0.25rem;
            color: #1f2d3d;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .profile-username {
            margin-bottom: 0.75rem;
            color: #69707a;
            font-size: 0.9rem;
        }

        .profile-role {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.85rem;
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        .profile-description {
            max-width: 300px;
            margin: 1rem auto 0;
            color: #69707a;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .profile-upload-area {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px dashed #b8c2cc;
            border-radius: 0.65rem;
            transition: 0.2s ease;
        }

        .profile-upload-area:hover {
            background-color: #f1f5f9;
            border-color: #0061f2;
        }

        .profile-upload-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            margin: 0 auto 0.75rem;
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
            border-radius: 50%;
        }

        .profile-upload-icon svg {
            width: 20px;
            height: 20px;
        }

        .profile-information-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 0;
            border-bottom: 1px solid #e0e5ec;
        }

        .profile-information-item:first-child {
            padding-top: 0;
        }

        .profile-information-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .profile-information-icon {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            margin-right: 0.75rem;
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.08);
            border-radius: 0.5rem;
        }

        .profile-information-icon svg {
            width: 17px;
            height: 17px;
        }

        .profile-information-label {
            margin-bottom: 0.1rem;
            color: #69707a;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .profile-information-value {
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 600;
            word-break: break-word;
        }

        .profile-form-section {
            padding-bottom: 1.25rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .profile-form-section:last-of-type {
            padding-bottom: 0;
            margin-bottom: 0;
            border-bottom: 0;
        }

        .profile-section-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .profile-section-number {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            margin-right: 0.65rem;
            color: #ffffff;
            background: linear-gradient(135deg, #0061f2, #6900c7);
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .profile-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .profile-section-subtitle {
            margin: 0.15rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .profile-required {
            color: #e81500;
        }

        .profile-form-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-top: 1.25rem;
            margin-top: 1.25rem;
            border-top: 1px solid #e0e5ec;
        }

        .profile-phone-prefix {
            color: #363d47;
            background-color: #eef2f6;
            font-weight: 700;
            user-select: none;
        }

        .profile-phone-preview {
            display: flex;
            align-items: center;
            margin-top: 0.45rem;
            color: #69707a;
            font-size: 0.75rem;
        }

        .profile-phone-preview svg {
            width: 14px;
            height: 14px;
        }

        .profile-phone-preview strong {
            margin-left: 0.25rem;
            color: #0061f2;
        }

        .profile-password-banner {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1.25rem;
            color: #1f2d3d;
            background:
                linear-gradient(135deg,
                    rgba(0, 97, 242, 0.08),
                    rgba(105, 0, 199, 0.08));
            border: 1px solid rgba(0, 97, 242, 0.12);
            border-radius: 0.65rem;
        }

        .profile-password-banner-icon {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            margin-right: 0.85rem;
            color: #ffffff;
            background: linear-gradient(135deg, #0061f2, #6900c7);
            border-radius: 0.65rem;
        }

        .profile-password-banner-icon svg {
            width: 21px;
            height: 21px;
        }

        .profile-password-banner-title {
            margin-bottom: 0.2rem;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .profile-password-banner-text {
            margin: 0;
            color: #69707a;
            font-size: 0.78rem;
        }

        .profile-password-field {
            position: relative;
        }

        .profile-password-field .form-control {
            padding-right: 3rem;
        }

        .profile-password-toggle {
            position: absolute;
            top: 50%;
            right: 0.6rem;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            color: #69707a;
            background: transparent;
            border: 0;
            border-radius: 0.35rem;
            transform: translateY(-50%);
        }

        .profile-password-toggle:hover {
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.08);
        }

        .profile-password-toggle svg {
            width: 17px;
            height: 17px;
        }

        .profile-password-strength {
            height: 5px;
            margin-top: 0.5rem;
            overflow: hidden;
            background-color: #e0e5ec;
            border-radius: 1rem;
        }

        .profile-password-strength-bar {
            width: 0;
            height: 100%;
            border-radius: 1rem;
            transition: width 0.25s ease, background-color 0.25s ease;
        }

        .profile-password-strength-text {
            display: block;
            margin-top: 0.35rem;
            color: #69707a;
            font-size: 0.72rem;
        }

        @media (max-width: 767.98px) {
            .profile-cover {
                height: 110px;
            }

            .profile-avatar-wrapper,
            .profile-avatar {
                width: 125px;
                height: 125px;
            }

            .profile-avatar-wrapper {
                margin-top: -62px;
            }

            .profile-form-footer {
                display: block;
            }

            .profile-form-footer .btn {
                width: 100%;
            }

            .profile-password-banner {
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    @php
        if ($user->avatar) {
            $avatarUrl = \Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://'])
                ? $user->avatar
                : asset('storage/' . $user->avatar);
        } else {
            $avatarUrl = asset('admin/assets/img/illustrations/profiles/profile-1.png');
        }

        $dateOfBirth = $user->date_of_birth
            ? \Illuminate\Support\Carbon::parse($user->date_of_birth)->format('Y-m-d')
            : '';

        /*
         * Nomor yang ditampilkan pada input hanya bagian setelah +62.
         *
         * +6281234567890 menjadi 81234567890
         * 081234567890 menjadi 81234567890
         */
        $rawPhoneValue = old('phone', $user->phone);
        $phoneInputValue = preg_replace('/[^0-9]/', '', (string) $rawPhoneValue);

        if (str_starts_with($phoneInputValue, '62')) {
            $phoneInputValue = substr($phoneInputValue, 2);
        }

        $phoneInputValue = ltrim($phoneInputValue, '0');

        $profileErrors = $errors->getBag('default');
        $passwordErrors = $errors->getBag('passwordUpdate');
    @endphp

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i data-feather="check-circle" class="me-2"></i>

                <div>
                    <strong>Berhasil!</strong>
                    {{ session('success') }}
                </div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup">
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i data-feather="alert-circle" class="me-2"></i>

                <div>
                    <strong>Gagal!</strong>
                    {{ session('error') }}
                </div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup">
            </button>
        </div>
    @endif

    @if ($profileErrors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i data-feather="alert-triangle" class="me-2 mt-1"></i>

                <div>
                    <strong>Data profile belum valid.</strong>

                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($profileErrors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup">
            </button>
        </div>
    @endif

    @if ($passwordErrors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i data-feather="shield" class="me-2 mt-1"></i>

                <div>
                    <strong>Password belum berhasil diperbarui.</strong>

                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($passwordErrors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup">
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-4">
            <div class="card profile-card mb-4">
                <div class="profile-cover"></div>

                <div class="profile-identity-body">
                    <div class="profile-avatar-wrapper">
                        <img id="avatarPreview" class="profile-avatar rounded-circle" src="{{ $avatarUrl }}"
                            alt="Foto profile {{ $user->name }}">

                        <span class="profile-avatar-status"></span>
                    </div>

                    <h2 class="profile-name">
                        {{ $user->name }}
                    </h2>

                    <div class="profile-username">
                        {{ '@' . $user->username }}
                    </div>

                    <span class="profile-role">
                        <i data-feather="shield" class="me-1"></i>
                        {{ $user->role }}
                    </span>

                    <p class="profile-description">
                        Informasi ini digunakan untuk mengenali akun Anda
                        di dalam sistem {{ config('app.name') }}.
                    </p>
                </div>
            </div>

            <div class="card profile-card mb-4">
                <div class="profile-card-header">
                    <h3 class="profile-card-header-title">
                        <i data-feather="camera" class="me-2"></i>
                        Ubah Foto Profile
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('management.profile.avatar') }}" method="POST" enctype="multipart/form-data"
                        id="avatarForm">

                        @csrf
                        @method('PATCH')

                        <div class="profile-upload-area text-center">
                            <div class="profile-upload-icon">
                                <i data-feather="image"></i>
                            </div>

                            <label class="form-label small fw-bold" for="avatar">
                                Pilih foto dari perangkat
                            </label>

                            <input
                                class="form-control
                                    @error('avatar') is-invalid @enderror"
                                id="avatar" name="avatar" type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                            @error('avatar')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 mt-3" type="submit">

                            <i data-feather="upload" class="me-1"></i>
                            Upload Foto
                        </button>
                    </form>
                </div>
            </div>

            <div class="card profile-card mb-4">
                <div class="profile-card-header">
                    <h3 class="profile-card-header-title">
                        <i data-feather="info" class="me-2"></i>
                        Informasi Akun
                    </h3>
                </div>

                <div class="card-body">
                    <div class="profile-information-item">
                        <div class="profile-information-icon">
                            <i data-feather="mail"></i>
                        </div>

                        <div>
                            <div class="profile-information-label">
                                Alamat Email
                            </div>

                            <div class="profile-information-value">
                                {{ $user->email ?: 'Belum tersedia' }}
                            </div>
                        </div>
                    </div>

                    <div class="profile-information-item">
                        <div class="profile-information-icon">
                            <i data-feather="phone"></i>
                        </div>

                        <div>
                            <div class="profile-information-label">
                                Nomor WhatsApp
                            </div>

                            <div class="profile-information-value">
                                {{ $user->phone ?: 'Belum tersedia' }}
                            </div>
                        </div>
                    </div>

                    <div class="profile-information-item">
                        <div class="profile-information-icon">
                            <i data-feather="link"></i>
                        </div>

                        <div>
                            <div class="profile-information-label">
                                Akun Google
                            </div>

                            <div class="profile-information-value">
                                @if ($user->google_id)
                                    <span class="text-success">
                                        Terhubung
                                    </span>
                                @else
                                    <span class="text-muted">
                                        Tidak terhubung
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="profile-information-item">
                        <div class="profile-information-icon">
                            <i data-feather="calendar"></i>
                        </div>

                        <div>
                            <div class="profile-information-label">
                                Bergabung Sejak
                            </div>

                            <div class="profile-information-value">
                                {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card profile-card mb-4">
                <div class="profile-card-header">
                    <h3 class="profile-card-header-title">
                        <i data-feather="edit-3" class="me-2"></i>
                        Edit Informasi Profile
                    </h3>

                    <span class="badge bg-primary-soft text-primary">
                        Data pribadi
                    </span>
                </div>

                <div class="card-body">
                    <form action="{{ route('management.profile.update') }}" method="POST" id="profileForm">

                        @csrf
                        @method('PATCH')

                        <div class="profile-form-section">
                            <div class="profile-section-heading">
                                <div class="profile-section-number">
                                    1
                                </div>

                                <div>
                                    <h4 class="profile-section-title">
                                        Informasi Utama
                                    </h4>

                                    <p class="profile-section-subtitle">
                                        Nama dan username yang digunakan pada akun.
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="name">
                                    Nama Lengkap
                                    <span class="profile-required">*</span>
                                </label>

                                <input
                                    class="form-control
                                        @error('name') is-invalid @enderror"
                                    id="name" name="name" type="text" placeholder="Masukkan nama lengkap"
                                    value="{{ old('name', $user->name) }}">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <label class="small mb-1" for="username">
                                        Username
                                        <span class="profile-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            @
                                        </span>

                                        <input
                                            class="form-control
                                                @error('username') is-invalid @enderror"
                                            id="username" name="username" type="text"
                                            placeholder="Masukkan username"
                                            value="{{ old('username', $user->username) }}">

                                        @error('username')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-text">
                                        Gunakan huruf, angka, tanda hubung, atau garis bawah.
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="small mb-1" for="role">
                                        Role
                                    </label>

                                    <input class="form-control text-capitalize" id="role" type="text"
                                        value="{{ $user->role }}" disabled>

                                    <div class="form-text">
                                        Role tidak dapat diubah.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-section">
                            <div class="profile-section-heading">
                                <div class="profile-section-number">
                                    2
                                </div>

                                <div>
                                    <h4 class="profile-section-title">
                                        Informasi Kontak
                                    </h4>

                                    <p class="profile-section-subtitle">
                                        Informasi yang digunakan untuk menghubungi Anda.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="email">
                                        Alamat Email
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i data-feather="mail"></i>
                                        </span>

                                        <input
                                            class="form-control
                                                @error('email') is-invalid @enderror"
                                            id="email" name="email" type="email" placeholder="nama@example.com"
                                            value="{{ old('email', $user->email) }}">

                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="phone">
                                        Nomor WhatsApp
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text profile-phone-prefix">
                                            +62
                                        </span>

                                        <input
                                            class="form-control
                                                @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" type="text" inputmode="numeric"
                                            autocomplete="tel" maxlength="13" pattern="[1-9][0-9]{7,12}"
                                            placeholder="81234567890" value="{{ $phoneInputValue }}">

                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="profile-phone-preview">
                                        <i data-feather="message-circle" class="me-1"></i>
                                        Disimpan sebagai:
                                        <strong id="phonePreview">
                                            {{ $phoneInputValue ? '+62' . $phoneInputValue : '+62...' }}
                                        </strong>
                                    </div>

                                    <div class="form-text">
                                        Masukkan nomor tanpa angka 0 di awal.
                                        Contoh: 81234567890.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-section">
                            <div class="profile-section-heading">
                                <div class="profile-section-number">
                                    3
                                </div>

                                <div>
                                    <h4 class="profile-section-title">
                                        Informasi Pribadi
                                    </h4>

                                    <p class="profile-section-subtitle">
                                        Jenis kelamin dan tanggal lahir pengguna.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="small mb-1" for="gender">
                                        Jenis Kelamin
                                    </label>

                                    <select
                                        class="form-select
                                            @error('gender') is-invalid @enderror"
                                        id="gender" name="gender">

                                        <option value="">
                                            Pilih jenis kelamin
                                        </option>

                                        <option value="male" @selected(old('gender', $user->gender) === 'male')>
                                            Laki-laki
                                        </option>

                                        <option value="female" @selected(old('gender', $user->gender) === 'female')>
                                            Perempuan
                                        </option>
                                    </select>

                                    @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1" for="date_of_birth">
                                        Tanggal Lahir
                                    </label>

                                    <input
                                        class="form-control
                                            @error('date_of_birth') is-invalid @enderror"
                                        id="date_of_birth" name="date_of_birth" type="date"
                                        max="{{ now()->format('Y-m-d') }}"
                                        value="{{ old('date_of_birth', $dateOfBirth) }}">

                                    @error('date_of_birth')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-footer">
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Perubahan Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card profile-card mb-4" id="password-section">
                <div class="profile-card-header">
                    <h3 class="profile-card-header-title">
                        <i data-feather="lock" class="me-2"></i>
                        Keamanan dan Password
                    </h3>

                    <span class="badge bg-warning-soft text-warning">
                        Keamanan
                    </span>
                </div>

                <div class="card-body">
                    <div class="profile-password-banner">
                        <div class="profile-password-banner-icon">
                            <i data-feather="shield"></i>
                        </div>

                        <div>
                            <div class="profile-password-banner-title">
                                Lindungi akun Anda
                            </div>

                            <p class="profile-password-banner-text">
                                Gunakan minimal 8 karakter dan jangan menggunakan
                                password yang mudah ditebak.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('management.profile.password') }}" method="POST" id="passwordForm">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="small mb-1" for="current_password">

                                Password Lama
                                <span class="profile-required">*</span>
                            </label>

                            <div class="profile-password-field">
                                <input
                                    class="form-control
                                        @error('current_password', 'passwordUpdate') is-invalid @enderror"
                                    id="current_password" name="current_password" type="password"
                                    placeholder="Masukkan password lama" autocomplete="current-password">

                                <button class="profile-password-toggle" type="button"
                                    data-password-target="current_password" aria-label="Tampilkan password">

                                    <i data-feather="eye"></i>
                                </button>

                                @error('current_password', 'passwordUpdate')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row gx-3">
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1" for="password">

                                    Password Baru
                                    <span class="profile-required">*</span>
                                </label>

                                <div class="profile-password-field">
                                    <input
                                        class="form-control
                                            @error('password', 'passwordUpdate') is-invalid @enderror"
                                        id="password" name="password" type="password" placeholder="Minimal 8 karakter"
                                        autocomplete="new-password">

                                    <button class="profile-password-toggle" type="button"
                                        data-password-target="password" aria-label="Tampilkan password">

                                        <i data-feather="eye"></i>
                                    </button>

                                    @error('password', 'passwordUpdate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="profile-password-strength">
                                    <div class="profile-password-strength-bar" id="passwordStrengthBar">
                                    </div>
                                </div>

                                <span class="profile-password-strength-text" id="passwordStrengthText">
                                    Belum ada password baru.
                                </span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small mb-1" for="password_confirmation">

                                    Konfirmasi Password Baru
                                    <span class="profile-required">*</span>
                                </label>

                                <div class="profile-password-field">
                                    <input
                                        class="form-control
                                            @error('password_confirmation', 'passwordUpdate') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation" type="password"
                                        placeholder="Ulangi password baru" autocomplete="new-password">

                                    <button class="profile-password-toggle" type="button"
                                        data-password-target="password_confirmation" aria-label="Tampilkan password">

                                        <i data-feather="eye"></i>
                                    </button>

                                    @error('password_confirmation', 'passwordUpdate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-text" id="passwordMatchText">
                                    Masukkan kembali password baru.
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-footer">
                            <button class="btn btn-warning" type="submit">
                                <i data-feather="key" class="me-1"></i>
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatarPreview');

            const phoneInput = document.getElementById('phone');
            const phonePreview = document.getElementById('phonePreview');

            const passwordInput = document.getElementById('password');

            const passwordConfirmationInput =
                document.getElementById('password_confirmation');

            const passwordStrengthBar =
                document.getElementById('passwordStrengthBar');

            const passwordStrengthText =
                document.getElementById('passwordStrengthText');

            const passwordMatchText =
                document.getElementById('passwordMatchText');

            /*
             * Input nomor telepon:
             * - Hanya menerima angka.
             * - Menghapus angka 0 di awal.
             * - Awalan +62 berada di luar input sehingga tidak dapat dihapus.
             */
            function cleanPhoneInput(value) {
                return value
                    .replace(/[^0-9]/g, '')
                    .replace(/^0+/, '')
                    .slice(0, 13);
            }

            function updatePhonePreview() {
                if (!phoneInput || !phonePreview) {
                    return;
                }

                const phone = phoneInput.value;

                phonePreview.textContent = phone ?
                    '+62' + phone :
                    '+62...';
            }

            phoneInput?.addEventListener('input', function() {
                phoneInput.value = cleanPhoneInput(phoneInput.value);
                updatePhonePreview();
            });

            /*
             * Jika user paste 0812, 62812, atau +62812,
             * ubah menjadi angka setelah +62.
             */
            phoneInput?.addEventListener('paste', function(event) {
                event.preventDefault();

                const clipboardText =
                    event.clipboardData.getData('text');

                let pastedNumber = clipboardText.replace(/[^0-9]/g, '');

                if (pastedNumber.startsWith('62')) {
                    pastedNumber = pastedNumber.substring(2);
                }

                pastedNumber = pastedNumber
                    .replace(/^0+/, '')
                    .slice(0, 13);

                phoneInput.value = pastedNumber;

                updatePhonePreview();
            });

            updatePhonePreview();

            /*
             * Preview avatar.
             */
            avatarInput?.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (!file) {
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format foto tidak valid',
                        text: 'Gunakan foto berformat JPG, JPEG, PNG, atau WEBP.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });

                    event.target.value = '';

                    return;
                }

                const maximumFileSize = 5 * 1024 * 1024;

                if (file.size > maximumFileSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran foto terlalu besar',
                        text: 'Ukuran foto maksimal adalah 5 MB.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });

                    event.target.value = '';

                    return;
                }

                const reader = new FileReader();

                reader.onload = function(readerEvent) {
                    avatarPreview.src = readerEvent.target.result;
                };

                reader.readAsDataURL(file);
            });

            /*
             * Tampilkan atau sembunyikan password.
             */
            document
                .querySelectorAll('[data-password-target]')
                .forEach(function(button) {
                    button.addEventListener('click', function() {
                        const inputId = button.getAttribute(
                            'data-password-target'
                        );

                        const input = document.getElementById(inputId);

                        if (!input) {
                            return;
                        }

                        const showPassword = input.type === 'password';

                        input.type = showPassword ? 'text' : 'password';

                        button.innerHTML = showPassword ?
                            '<i data-feather="eye-off"></i>' :
                            '<i data-feather="eye"></i>';

                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    });
                });

            function checkPasswordStrength() {
                const password = passwordInput?.value || '';
                let score = 0;

                if (password.length >= 8) {
                    score++;
                }

                if (/[a-z]/.test(password)) {
                    score++;
                }

                if (/[A-Z]/.test(password)) {
                    score++;
                }

                if (/[0-9]/.test(password)) {
                    score++;
                }

                if (/[^A-Za-z0-9]/.test(password)) {
                    score++;
                }

                if (password.length === 0) {
                    passwordStrengthBar.style.width = '0';
                    passwordStrengthBar.style.backgroundColor = '#e0e5ec';
                    passwordStrengthText.textContent =
                        'Belum ada password baru.';
                    passwordStrengthText.style.color = '#69707a';

                    return;
                }

                if (score <= 2) {
                    passwordStrengthBar.style.width = '33%';
                    passwordStrengthBar.style.backgroundColor = '#e81500';
                    passwordStrengthText.textContent =
                        'Kekuatan password: Lemah';
                    passwordStrengthText.style.color = '#e81500';
                } else if (score <= 4) {
                    passwordStrengthBar.style.width = '66%';
                    passwordStrengthBar.style.backgroundColor = '#f4a100';
                    passwordStrengthText.textContent =
                        'Kekuatan password: Sedang';
                    passwordStrengthText.style.color = '#f4a100';
                } else {
                    passwordStrengthBar.style.width = '100%';
                    passwordStrengthBar.style.backgroundColor = '#00ac69';
                    passwordStrengthText.textContent =
                        'Kekuatan password: Kuat';
                    passwordStrengthText.style.color = '#00ac69';
                }
            }

            function checkPasswordConfirmation() {
                const password = passwordInput?.value || '';
                const confirmation =
                    passwordConfirmationInput?.value || '';

                if (confirmation.length === 0) {
                    passwordMatchText.textContent =
                        'Masukkan kembali password baru.';

                    passwordMatchText.className = 'form-text';

                    return;
                }

                if (password === confirmation) {
                    passwordMatchText.textContent =
                        'Konfirmasi password sudah sesuai.';

                    passwordMatchText.className =
                        'form-text text-success';
                } else {
                    passwordMatchText.textContent =
                        'Konfirmasi password belum sesuai.';

                    passwordMatchText.className =
                        'form-text text-danger';
                }
            }

            passwordInput?.addEventListener('input', function() {
                checkPasswordStrength();
                checkPasswordConfirmation();
            });

            passwordConfirmationInput?.addEventListener(
                'input',
                checkPasswordConfirmation
            );

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0061f2'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            @endif

            @if ($profileErrors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Data profile belum valid',
                    text: @json($profileErrors->first()),
                    confirmButtonText: 'Periksa Kembali',
                    confirmButtonColor: '#dc3545'
                });
            @endif

            @if ($passwordErrors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Password belum diperbarui',
                    text: @json($passwordErrors->first()),
                    confirmButtonText: 'Periksa Kembali',
                    confirmButtonColor: '#dc3545'
                }).then(function() {
                    document
                        .getElementById('password-section')
                        ?.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                });
            @endif
        });
    </script>
@endpush
