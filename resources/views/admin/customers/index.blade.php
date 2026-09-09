@extends('admin.layouts.main')

@section('page_title', 'Data Customer')
@section('meta_description', 'Data customer restoran.')
@section('header_title', 'Data Customer')
@section('header_subtitle', 'Pantau akun, pesanan, dan reservasi customer restoran.')
@section('header_icon', 'users')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item active">
        Data Customer
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card customer-stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="customer-stat-icon bg-primary-soft text-primary">
                        <i data-feather="users"></i>
                    </div>

                    <div class="text-muted small mt-3">
                        Total Customer
                    </div>

                    <div class="customer-stat-value">
                        {{ number_format($summary['total']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card customer-stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="customer-stat-icon bg-danger-soft text-danger">
                        <i data-feather="chrome"></i>
                    </div>

                    <div class="text-muted small mt-3">
                        Akun Google
                    </div>

                    <div class="customer-stat-value">
                        {{ number_format($summary['google']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card customer-stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="customer-stat-icon bg-success-soft text-success">
                        <i data-feather="check-circle"></i>
                    </div>

                    <div class="text-muted small mt-3">
                        Email Terverifikasi
                    </div>

                    <div class="customer-stat-value">
                        {{ number_format($summary['verified']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card customer-stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="customer-stat-icon bg-info-soft text-info">
                        <i data-feather="user-plus"></i>
                    </div>

                    <div class="text-muted small mt-3">
                        Baru Bulan Ini
                    </div>

                    <div class="customer-stat-value">
                        {{ number_format($summary['new_this_month']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i data-feather="filter" class="me-2"></i>
            Filter Customer
        </div>

        <div class="card-body">
            <form action="{{ route('management.customers.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-xl-4 col-md-6 mb-3">
                        <label for="search" class="form-label">
                            Pencarian
                        </label>

                        <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                            placeholder="Nama, username, email, telepon...">
                    </div>

                    <div class="col-xl-2 col-md-6 mb-3">
                        <label for="gender" class="form-label">
                            Jenis Kelamin
                        </label>

                        <select name="gender" id="gender" class="form-select">
                            <option value="">Semua</option>

                            <option value="male" @selected($gender === 'male')>
                                Laki-laki
                            </option>

                            <option value="female" @selected($gender === 'female')>
                                Perempuan
                            </option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <label for="account_type" class="form-label">
                            Jenis Akun
                        </label>

                        <select name="account_type" id="account_type" class="form-select">
                            <option value="">Semua akun</option>

                            <option value="regular" @selected($accountType === 'regular')>
                                Akun Reguler
                            </option>

                            <option value="google" @selected($accountType === 'google')>
                                Google Login
                            </option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <label for="registration_date" class="form-label">
                            Tanggal Registrasi
                        </label>

                        <input type="date" name="registration_date" id="registration_date" class="form-control"
                            value="{{ $registrationDate }}">
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="search" class="me-2"></i>
                        Terapkan Filter
                    </button>

                    <a href="{{ route('management.customers.index') }}" class="btn btn-light">
                        <i data-feather="refresh-cw" class="me-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <div>
                <div class="fw-bold">Daftar Customer</div>

                <small class="text-muted">
                    {{ number_format($customers->total()) }}
                    customer ditemukan.
                </small>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Kontak</th>
                            <th>Jenis Akun</th>
                            <th>Pesanan</th>
                            <th>Reservasi</th>
                            <th>Bergabung</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($customers as $customer)
                            @php
                                $avatarUrl = null;

                                if ($customer->avatar) {
                                    $avatarUrl = \Illuminate\Support\Str::startsWith($customer->avatar, [
                                        'http://',
                                        'https://',
                                    ])
                                        ? $customer->avatar
                                        : asset('storage/' . $customer->avatar);
                                }
                            @endphp

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($avatarUrl)
                                            <img src="{{ $avatarUrl }}" alt="{{ $customer->name }}"
                                                class="customer-avatar">
                                        @else
                                            <div class="customer-avatar-placeholder">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <a href="{{ route('management.customers.show', $customer) }}"
                                                class="fw-semibold text-decoration-none">
                                                {{ $customer->name }}
                                            </a>

                                            <div class="small text-muted">
                                                {{ '@' . $customer->username }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ $customer->email ?: '-' }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $customer->phone ?: '-' }}
                                    </div>
                                </td>

                                <td>
                                    @if ($customer->google_id)
                                        <span class="badge bg-danger-soft text-danger">
                                            Google
                                        </span>
                                    @else
                                        <span class="badge bg-primary-soft text-primary">
                                            Reguler
                                        </span>
                                    @endif

                                    @if ($customer->email_verified_at)
                                        <span class="badge bg-success-soft text-success">
                                            Terverifikasi
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($customer->orders_count) }}
                                    </strong>
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($customer->table_reservations_count) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $customer->created_at->format('d M Y') }}

                                    <div class="small text-muted">
                                        {{ $customer->created_at->format('H:i') }}
                                    </div>
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('management.customers.show', $customer) }}"
                                        class="btn btn-sm btn-outline-primary" title="Lihat customer">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="text-center py-5">
                                        <i data-feather="users" class="text-muted mb-3"
                                            style="width: 48px; height: 48px;"></i>

                                        <div class="fw-bold">
                                            Customer tidak ditemukan
                                        </div>

                                        <div class="small text-muted mt-1">
                                            Belum ada customer atau hasil filter kosong.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($customers->hasPages())
            <div class="card-footer">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
@endsection

@push('style')
    <style>
        .customer-stat-card {
            border: 0;
            border-radius: 0.75rem;
        }

        .customer-stat-icon {
            width: 2.75rem;
            height: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
        }

        .customer-stat-icon svg {
            width: 1.2rem;
            height: 1.2rem;
        }

        .customer-stat-value {
            color: #363d47;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .customer-avatar,
        .customer-avatar-placeholder {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .customer-avatar {
            object-fit: cover;
        }

        .customer-avatar-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, #0061f2, #6900c7);
            font-weight: 700;
        }

        .table th {
            white-space: nowrap;
            color: #69707a;
            background: #f8f9fa;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .table .btn svg {
            width: 0.95rem;
            height: 0.95rem;
        }

        @media (max-width: 767.98px) {
            .table {
                min-width: 1000px;
            }
        }
    </style>
@endpush
