@php
    $isEdit = isset($user) && $user;

    $phoneValue = old('phone');

    if ($phoneValue === null && $isEdit && $user->phone) {
        $phoneValue = preg_replace('/^\+62/', '', $user->phone);
    }
@endphp

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <strong>Data staff belum dapat disimpan.</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form id="staffForm"
    action="{{ $isEdit ? route('management.staff-accounts.update', $user) : route('management.staff-accounts.store') }}"
    method="POST" autocomplete="off">
    @csrf

    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i data-feather="user" class="me-2"></i>
                    Informasi Staff
                </div>

                <div class="card-body">
                    <div class="row gx-3">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                Nama Lengkap
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $isEdit ? $user->name : '') }}"
                                maxlength="255" required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">
                                Username
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="username" id="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $isEdit ? $user->username : '') }}"
                                minlength="3" maxlength="50" required>

                            <div class="form-text">
                                Gunakan huruf, angka, tanda minus, atau underscore.
                            </div>

                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email
                                <span class="text-danger">*</span>
                            </label>

                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $isEdit ? $user->email : '') }}"
                                maxlength="255" required>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                Nomor Telepon
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    +62
                                </span>

                                <input type="text" name="phone" id="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ $phoneValue }}" inputmode="numeric" maxlength="14"
                                    placeholder="81234567890">
                            </div>

                            <div class="form-text">
                                Jangan masukkan angka 0 di bagian depan.
                            </div>

                            @error('phone')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">
                                Jenis Kelamin
                            </label>

                            <select name="gender" id="gender"
                                class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Pilih jenis kelamin</option>

                                <option value="male" @selected(old('gender', $isEdit ? $user->gender : '') === 'male')>
                                    Laki-laki
                                </option>

                                <option value="female" @selected(old('gender', $isEdit ? $user->gender : '') === 'female')>
                                    Perempuan
                                </option>
                            </select>

                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">
                                Tanggal Lahir
                            </label>

                            <input type="date" name="date_of_birth" id="date_of_birth"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth', $isEdit ? optional($user->date_of_birth)->format('Y-m-d') : '') }}"
                                max="{{ now()->format('Y-m-d') }}">

                            @error('date_of_birth')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i data-feather="lock" class="me-2"></i>
                    Keamanan Akun
                </div>

                <div class="card-body">
                    @if ($isEdit)
                        <div class="alert alert-info small">
                            Kosongkan password jika tidak ingin mengganti
                            password staff.
                        </div>
                    @endif

                    <div class="row gx-3">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                Password

                                @if (!$isEdit)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>

                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" minlength="8"
                                    {{ $isEdit ? '' : 'required' }}>

                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    data-target="password">
                                    <i data-feather="eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password

                                @if (!$isEdit)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>

                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" minlength="8" {{ $isEdit ? '' : 'required' }}>

                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    data-target="password_confirmation">
                                    <i data-feather="eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card shadow-sm mb-4 staff-save-card">
                <div class="card-header">
                    <i data-feather="shield" class="me-2"></i>
                    Hak Akses
                </div>

                <div class="card-body">
                    <div class="staff-role-box">
                        <div class="staff-role-icon">
                            <i data-feather="user-check"></i>
                        </div>

                        <div>
                            <div class="fw-bold">Role Staff</div>

                            <div class="small text-muted">
                                Akun mendapat akses operasional restoran,
                                tetapi tidak dapat mengelola akun staff.
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" id="saveStaffButton" class="btn btn-primary w-100">
                        <i data-feather="save" class="me-2"></i>
                        {{ $isEdit ? 'Perbarui Akun Staff' : 'Buat Akun Staff' }}
                    </button>

                    <a href="{{ $isEdit ? route('management.staff-accounts.show', $user) : route('management.staff-accounts.index') }}"
                        class="btn btn-light w-100 mt-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('style')
    <style>
        .staff-save-card {
            position: sticky;
            top: 6rem;
        }

        .staff-role-box {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: 0.75rem;
            background: #f8f9fa;
        }

        .staff-role-icon {
            width: 2.75rem;
            height: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 0.65rem;
            color: #0061f2;
            background: rgba(0, 97, 242, 0.1);
        }

        .staff-role-icon svg,
        .toggle-password svg {
            width: 1rem;
            height: 1rem;
        }

        @media (max-width: 1199.98px) {
            .staff-save-card {
                position: static;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');

            phoneInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');

                value = value.replace(/^62/, '');
                value = value.replace(/^0+/, '');

                this.value = value;
            });

            document.querySelectorAll('.toggle-password')
                .forEach(function(button) {
                    button.addEventListener('click', function() {
                        const target = document.getElementById(
                            button.dataset.target
                        );

                        target.type = target.type === 'password' ?
                            'text' :
                            'password';
                    });
                });

            document.getElementById('staffForm')
                .addEventListener('submit', function() {
                    const button = document.getElementById(
                        'saveStaffButton'
                    );

                    button.disabled = true;
                    button.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Menyimpan...
                    `;
                });
        });
    </script>
@endpush
