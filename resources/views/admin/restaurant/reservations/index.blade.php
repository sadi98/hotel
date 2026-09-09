@extends('admin.layouts.main')

@section('page_title', 'Reservasi Meja || ' . config('app.name'))

@section('meta_description', 'Kelola reservasi meja restoran.')

@section('header_title', 'Reservasi Meja')

@section('header_subtitle', 'Kelola jadwal, meja, jumlah tamu, permintaan khusus, dan status reservasi.')

@section('header_icon', 'calendar')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.reservations.create') }}">
        <i data-feather="plus" class="me-1"></i>
        Tambah Reservasi
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Reservasi
    </li>
@endsection

@push('style')
    <style>
        .reservation-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .reservation-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .reservation-card-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .reservation-card-title svg {
            width: 18px;
            height: 18px;
        }

        .reservation-filter {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .reservation-filter-label {
            margin-bottom: 0.4rem;
            color: #69707a;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .reservation-table {
            margin-bottom: 0;
        }

        .reservation-table thead th {
            padding: 0.85rem 1rem;
            color: #69707a;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e5ec;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .reservation-table tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #edf0f4;
            vertical-align: middle;
        }

        .reservation-number {
            color: #0061f2;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .reservation-customer {
            margin-bottom: 0.15rem;
            color: #1f2d3d;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .reservation-secondary {
            color: #69707a;
            font-size: 0.72rem;
        }

        .reservation-date {
            color: #363d47;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .reservation-table-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            color: #6900c7;
            background-color: rgba(105, 0, 199, 0.1);
            border-radius: 2rem;
            font-size: 0.68rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .reservation-status {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            border-radius: 2rem;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .reservation-status-pending {
            color: #b27400;
            background-color: rgba(244, 161, 0, 0.15);
        }

        .reservation-status-confirmed {
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
        }

        .reservation-status-seated {
            color: #6900c7;
            background-color: rgba(105, 0, 199, 0.1);
        }

        .reservation-status-completed {
            color: #008a56;
            background-color: rgba(0, 172, 105, 0.12);
        }

        .reservation-status-cancelled,
        .reservation-status-no_show {
            color: #c51f1a;
            background-color: rgba(232, 21, 0, 0.1);
        }

        .reservation-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.4rem;
        }

        .reservation-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 0.45rem;
        }

        .reservation-action svg {
            width: 15px;
            height: 15px;
        }

        .reservation-empty {
            padding: 4rem 1.5rem;
            text-align: center;
        }

        .reservation-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-top: 1px solid #e0e5ec;
        }

        .reservation-pagination .pagination {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {

            .reservation-card-header,
            .reservation-pagination {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .reservation-pagination nav {
                width: 100%;
                overflow-x: auto;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'seated' => 'Sudah Duduk',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'no_show' => 'Tidak Hadir',
        ];
    @endphp

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <i data-feather="check-circle" class="me-2"></i>

                <div>
                    <strong>Berhasil!</strong>
                    {{ session('success') }}
                </div>
            </div>

            <button class="btn-close" type="button" data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <i data-feather="alert-circle" class="me-2"></i>

                <div>
                    <strong>Gagal!</strong>
                    {{ session('error') }}
                </div>
            </div>

            <button class="btn-close" type="button" data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <div class="card reservation-card mb-4">
        <div class="reservation-card-header">
            <h2 class="reservation-card-title">
                <i data-feather="filter" class="me-2"></i>
                Pencarian dan Filter
            </h2>

            @if (request()->filled('search') || request()->filled('status') || request()->filled('date'))
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('management.reservations.index') }}">
                    <i data-feather="x" class="me-1"></i>
                    Hapus Filter
                </a>
            @endif
        </div>

        <div class="card-body">
            <form action="{{ route('management.reservations.index') }}" method="GET">

                <div class="reservation-filter">
                    <div class="row gx-3 align-items-end">
                        <div class="col-xl-5 col-md-6 mb-3 mb-xl-0">
                            <label class="reservation-filter-label" for="search">
                                Pencarian
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search"></i>
                                </span>

                                <input class="form-control" id="search" name="search" type="text"
                                    value="{{ $search }}" placeholder="Nomor reservasi, nama, atau telepon...">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <label class="reservation-filter-label" for="status">
                                Status
                            </label>

                            <select class="form-select" id="status" name="status">

                                <option value="">Semua status</option>

                                @foreach ($statuses as $statusOption)
                                    <option value="{{ $statusOption }}" @selected($status === $statusOption)>
                                        {{ $statusLabels[$statusOption] ?? ucfirst($statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-6 mb-3 mb-md-0">
                            <label class="reservation-filter-label" for="date">
                                Tanggal
                            </label>

                            <input class="form-control" id="date" name="date" type="date"
                                value="{{ $date }}">
                        </div>

                        <div class="col-xl-2 col-md-6">
                            <button class="btn btn-primary w-100" type="submit">
                                <i data-feather="search" class="me-1"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card reservation-card mb-4">
        <div class="reservation-card-header">
            <h2 class="reservation-card-title">
                <i data-feather="calendar" class="me-2"></i>
                Daftar Reservasi
            </h2>

            <span class="badge bg-primary-soft text-primary">
                {{ $reservations->total() }} reservasi
            </span>
        </div>

        @if ($reservations->count() > 0)
            <div class="table-responsive">
                <table class="table reservation-table">
                    <thead>
                        <tr>
                            <th>Reservasi</th>
                            <th>Customer</th>
                            <th>Jadwal</th>
                            <th>Meja</th>
                            <th>Tamu</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($reservations as $reservation)
                            <tr>
                                <td>
                                    <div class="reservation-number">
                                        {{ $reservation->reservation_number }}
                                    </div>

                                    <div class="reservation-secondary">
                                        {{ $reservation->user_id ? 'User terdaftar' : 'Guest' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="reservation-customer">
                                        {{ $reservation->customer_name }}
                                    </div>

                                    <div class="reservation-secondary">
                                        {{ $reservation->customer_phone }}
                                    </div>
                                </td>

                                <td>
                                    <div class="reservation-date">
                                        {{ \Illuminate\Support\Carbon::parse($reservation->reservation_start)->translatedFormat('d M Y') }}
                                    </div>

                                    <div class="reservation-secondary">
                                        {{ \Illuminate\Support\Carbon::parse($reservation->reservation_start)->format('H:i') }}
                                        –
                                        {{ \Illuminate\Support\Carbon::parse($reservation->reservation_end)->format('H:i') }}
                                    </div>
                                </td>

                                <td>
                                    <span class="reservation-table-badge">
                                        <i data-feather="layout" class="me-1"></i>
                                        {{ $reservation->restaurantTable?->table_number ?? '-' }}
                                    </span>

                                    <div class="reservation-secondary mt-1">
                                        {{ $reservation->restaurantTable?->area ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="reservation-date">
                                        <i data-feather="users" class="me-1"></i>
                                        {{ $reservation->guest_count }} orang
                                    </div>
                                </td>

                                <td>
                                    <span class="reservation-status reservation-status-{{ $reservation->status }}">
                                        {{ $statusLabels[$reservation->status] ?? ucfirst($reservation->status) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="reservation-actions">
                                        <a class="btn btn-outline-secondary reservation-action"
                                            href="{{ route('management.reservations.show', $reservation) }}"
                                            data-bs-toggle="tooltip" title="Detail reservasi">
                                            <i data-feather="eye"></i>
                                        </a>

                                        <a class="btn btn-outline-primary reservation-action"
                                            href="{{ route('management.reservations.edit', $reservation) }}"
                                            data-bs-toggle="tooltip" title="Edit reservasi">
                                            <i data-feather="edit-2"></i>
                                        </a>

                                        @if (in_array($reservation->status, ['pending', 'cancelled'], true))
                                            <button class="btn btn-outline-danger reservation-action" type="button"
                                                onclick="confirmDeleteReservation(
                                                    {{ $reservation->id }},
                                                    @js($reservation->reservation_number)
                                                )"
                                                data-bs-toggle="tooltip" title="Hapus reservasi">
                                                <i data-feather="trash-2"></i>
                                            </button>

                                            <form id="deleteReservationForm{{ $reservation->id }}"
                                                action="{{ route('management.reservations.destroy', $reservation) }}"
                                                method="POST" class="d-none">

                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="reservation-pagination">
                <div class="text-muted small">
                    Menampilkan
                    <strong>{{ $reservations->firstItem() }}</strong>
                    sampai
                    <strong>{{ $reservations->lastItem() }}</strong>
                    dari
                    <strong>{{ $reservations->total() }}</strong>
                    reservasi
                </div>

                <div>{{ $reservations->links() }}</div>
            </div>
        @else
            <div class="reservation-empty">
                <i data-feather="calendar" class="text-primary mb-3"></i>

                <h3 class="h6 fw-bold">Reservasi belum tersedia</h3>

                <p class="text-muted small">
                    Belum ada reservasi yang sesuai dengan filter.
                </p>

                <a class="btn btn-primary" href="{{ route('management.reservations.create') }}">
                    <i data-feather="plus" class="me-1"></i>
                    Tambah Reservasi
                </a>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document
                .querySelectorAll('[data-bs-toggle="tooltip"]')
                .forEach(function(element) {
                    new bootstrap.Tooltip(element);
                });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonColor: '#0061f2'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });

        function confirmDeleteReservation(reservationId, reservationNumber) {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus reservasi?',
                text: 'Reservasi ' + reservationNumber + ' akan dihapus.',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    document
                        .getElementById(
                            'deleteReservationForm' + reservationId
                        )
                        .submit();
                }
            });
        }
    </script>
@endpush
