@extends('admin.layouts.main')

@section('page_title', 'Akun Staff')
@section('meta_description', 'Kelola akun staff restoran.')
@section('header_title', 'Akun Staff')
@section('header_subtitle', 'Kelola akun yang memiliki akses operasional restoran.')
@section('header_icon', 'user-check')

@section('header_action')
    <a href="{{ route('management.staff-accounts.create') }}" class="btn btn-light">
        <i data-feather="user-plus" class="me-2"></i>
        Tambah Staff
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item active">
        Akun Staff
    </li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i data-feather="check-circle" class="me-2"></i>
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @foreach ([['Total Staff', $summary['total_staff'], 'users', 'primary'], ['Laki-laki', $summary['male_staff'], 'user', 'info'], ['Perempuan', $summary['female_staff'], 'user', 'danger'], ['Baru Bulan Ini', $summary['new_this_month'], 'user-plus', 'success']] as [$label, $value, $icon, $color])
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="staff-stat-icon bg-{{ $color }}-soft text-{{ $color }}">
                            <i data-feather="{{ $icon }}"></i>
                        </div>

                        <div class="small text-muted mt-3">
                            {{ $label }}
                        </div>

                        <div class="staff-stat-value">
                            {{ number_format($value) }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i data-feather="filter" class="me-2"></i>
            Filter Staff
        </div>

        <div class="card-body">
            <form action="{{ route('management.staff-accounts.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-md-8 mb-3">
                        <label for="search" class="form-label">
                            Pencarian
                        </label>

                        <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                            placeholder="Nama, username, email, telepon...">
                    </div>

                    <div class="col-md-4 mb-3">
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
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Terapkan
                    </button>

                    <a href="{{ route('management.staff-accounts.index') }}" class="btn btn-light">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold">Daftar Staff</div>

                    <small class="text-muted">
                        {{ number_format($staffAccounts->total()) }}
                        akun ditemukan.
                    </small>
                </div>

                <a href="{{ route('management.staff-accounts.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus" class="me-1"></i>
                    Tambah Staff
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Kontak</th>
                            <th>Pesanan Dibuat</th>
                            <th>Pembayaran Diproses</th>
                            <th>Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($staffAccounts as $staff)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $staff->name }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ '@' . $staff->username }}
                                    </div>
                                </td>

                                <td>
                                    <div>{{ $staff->email }}</div>

                                    <div class="small text-muted">
                                        {{ $staff->phone ?: '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ number_format($staff->created_orders_count) }}
                                </td>

                                <td>
                                    {{ number_format($staff->processed_payments_count) }}
                                </td>

                                <td>
                                    {{ $staff->created_at->format('d M Y') }}
                                </td>

                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('management.staff-accounts.show', $staff) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i data-feather="eye"></i>
                                        </a>

                                        <a href="{{ route('management.staff-accounts.edit', $staff) }}"
                                            class="btn btn-sm btn-outline-warning">
                                            <i data-feather="edit-2"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-danger delete-staff"
                                            data-form="delete-staff-{{ $staff->id }}" data-name="{{ $staff->name }}">
                                            <i data-feather="trash-2"></i>
                                        </button>

                                        <form id="delete-staff-{{ $staff->id }}"
                                            action="{{ route('management.staff-accounts.destroy', $staff) }}"
                                            method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5 text-muted">
                                        Belum ada akun staff.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($staffAccounts->hasPages())
            <div class="card-footer">
                {{ $staffAccounts->links() }}
            </div>
        @endif
    </div>
@endsection

@push('style')
    <style>
        .staff-stat-icon {
            width: 2.75rem;
            height: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
        }

        .staff-stat-value {
            color: #363d47;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .table th {
            white-space: nowrap;
            background: #f8f9fa;
            color: #69707a;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .table .btn svg {
            width: 0.95rem;
            height: 0.95rem;
        }

        @media (max-width: 767.98px) {
            .table {
                min-width: 900px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-staff')
                .forEach(function(button) {
                    button.addEventListener('click', function() {
                        Swal.fire({
                            title: 'Hapus akun staff?',
                            text: 'Akun ' + button.dataset.name +
                                ' akan dihapus.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, hapus',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#dc3545',
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                document.getElementById(
                                    button.dataset.form
                                ).submit();
                            }
                        });
                    });
                });
        });
    </script>
@endpush
