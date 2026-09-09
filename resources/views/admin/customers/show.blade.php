@extends('admin.layouts.main')

@section('page_title', 'Customer ' . $user->name)
@section('meta_description', 'Detail customer restoran.')
@section('header_title', 'Detail Customer')
@section('header_subtitle', 'Riwayat akun, pesanan, dan reservasi customer.')
@section('header_icon', 'user')

@section('header_action')
    <a href="{{ route('management.customers.index') }}" class="btn btn-light">
        <i data-feather="arrow-left" class="me-2"></i>
        Kembali
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('management.customers.index') }}">
            Data Customer
        </a>
    </li>

    <li class="breadcrumb-item active">
        {{ $user->name }}
    </li>
@endsection

@php
    $orderStatusClasses = [
        'pending' => 'bg-warning text-dark',
        'confirmed' => 'bg-info text-dark',
        'preparing' => 'bg-primary',
        'ready' => 'bg-secondary',
        'served' => 'bg-success',
        'completed' => 'bg-success',
        'cancelled' => 'bg-danger',
    ];

    $reservationStatusClasses = [
        'pending' => 'bg-warning text-dark',
        'confirmed' => 'bg-primary',
        'seated' => 'bg-info text-dark',
        'completed' => 'bg-success',
        'cancelled' => 'bg-danger',
        'no_show' => 'bg-secondary',
    ];

    $avatarUrl = null;

    if ($user->avatar) {
        $avatarUrl = \Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://'])
            ? $user->avatar
            : asset('storage/' . $user->avatar);
    }
@endphp

@section('content')
    <div class="card shadow-sm mb-4 overflow-hidden">
        <div class="customer-profile-header">
            <div class="d-flex flex-wrap align-items-center gap-3">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="customer-profile-avatar">
                @else
                    <div class="customer-profile-placeholder">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <h4 class="text-white mb-1">
                        {{ $user->name }}
                    </h4>

                    <div class="text-white-50">
                        {{ '@' . $user->username }}
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @if ($user->google_id)
                            <span class="badge bg-danger">
                                Google Account
                            </span>
                        @else
                            <span class="badge bg-light text-dark">
                                Akun Reguler
                            </span>
                        @endif

                        @if ($user->email_verified_at)
                            <span class="badge bg-success">
                                Email Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-xl-end mt-3 mt-xl-0">
                <div class="text-white-50 small">
                    Bergabung sejak
                </div>

                <div class="text-white fw-semibold">
                    {{ $user->created_at->format('d F Y') }}
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6 col-xl-3">
                    <div class="customer-info-box">
                        <div class="text-muted small">Email</div>

                        <div class="fw-semibold text-break">
                            {{ $user->email ?: '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="customer-info-box">
                        <div class="text-muted small">Nomor Telepon</div>

                        <div class="fw-semibold">
                            {{ $user->phone ?: '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="customer-info-box">
                        <div class="text-muted small">Jenis Kelamin</div>

                        <div class="fw-semibold">
                            @if ($user->gender === 'male')
                                Laki-laki
                            @elseif ($user->gender === 'female')
                                Perempuan
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="customer-info-box">
                        <div class="text-muted small">Tanggal Lahir</div>

                        <div class="fw-semibold">
                            {{ optional($user->date_of_birth)->format('d F Y') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Pesanan</div>

                    <div class="customer-stat-value">
                        {{ number_format($statistics['total_orders']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Pesanan Selesai</div>

                    <div class="customer-stat-value">
                        {{ number_format($statistics['completed_orders']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Transaksi Lunas</div>

                    <div class="customer-stat-money">
                        Rp{{ number_format((float) $statistics['total_spending'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Reservasi</div>

                    <div class="customer-stat-value">
                        {{ number_format($statistics['total_reservations']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i data-feather="shopping-cart" class="me-2"></i>
            Riwayat Pesanan
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Pesanan</th>
                            <th>Jenis</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('management.orders.show', $order) }}"
                                        class="fw-semibold text-decoration-none">
                                        {{ $order->order_number }}
                                    </a>
                                </td>

                                <td>
                                    {{ ucwords(str_replace('_', ' ', $order->order_type)) }}

                                    @if ($order->order_type === 'dine_in')
                                        <div class="small text-muted">
                                            Meja
                                            {{ $order->restaurantTable?->table_number ?? '-' }}
                                        </div>
                                    @elseif ($order->order_type === 'room_service')
                                        <div class="small text-muted">
                                            Kamar {{ $order->room_number ?? '-' }}
                                        </div>
                                    @endif
                                </td>

                                <td class="fw-semibold">
                                    Rp{{ number_format((float) $order->grand_total, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="badge {{ $orderStatusClasses[$order->status] ?? 'bg-secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>

                                <td>
                                    @if ($order->payment_status === 'paid')
                                        <span class="badge bg-success">
                                            Paid
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ optional($order->ordered_at)->format('d M Y H:i') ?? '-' }}
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('management.orders.show', $order) }}"
                                        class="btn btn-sm btn-outline-primary" title="Lihat pesanan">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="text-center py-4 text-muted">
                                        Customer belum memiliki pesanan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i data-feather="calendar" class="me-2"></i>
            Riwayat Reservasi
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Reservasi</th>
                            <th>Meja</th>
                            <th>Jumlah Tamu</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tableReservations as $reservation)
                            <tr>
                                <td>
                                    <a href="{{ route('management.reservations.show', $reservation) }}"
                                        class="fw-semibold text-decoration-none">
                                        {{ $reservation->reservation_number }}
                                    </a>
                                </td>

                                <td>
                                    {{ $reservation->restaurantTable?->table_number ?? '-' }}

                                    <div class="small text-muted">
                                        {{ $reservation->restaurantTable?->area ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ number_format($reservation->guest_count) }}
                                    orang
                                </td>

                                <td>
                                    {{ optional($reservation->reservation_start)->format('d M Y H:i') ?? '-' }}
                                </td>

                                <td>
                                    <span
                                        class="badge {{ $reservationStatusClasses[$reservation->status] ?? 'bg-secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $reservation->status)) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('management.reservations.show', $reservation) }}"
                                        class="btn btn-sm btn-outline-primary" title="Lihat reservasi">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-4 text-muted">
                                        Customer belum memiliki reservasi.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($tableReservations->hasPages())
            <div class="card-footer">
                {{ $tableReservations->links() }}
            </div>
        @endif
    </div>
@endsection

@push('style')
    <style>
        .customer-profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg,
                    #0061f2 0%,
                    #6900c7 100%);
        }

        .customer-profile-avatar,
        .customer-profile-placeholder {
            width: 5rem;
            height: 5rem;
            border: 4px solid rgba(255, 255, 255, 0.35);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .customer-profile-avatar {
            object-fit: cover;
        }

        .customer-profile-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0061f2;
            background: #fff;
            font-size: 2rem;
            font-weight: 700;
        }

        .customer-info-box {
            height: 100%;
            padding: 1rem;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .customer-stat-value {
            margin-top: 0.35rem;
            color: #363d47;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .customer-stat-money {
            margin-top: 0.35rem;
            color: #363d47;
            font-size: 1.25rem;
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
            .customer-profile-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .table {
                min-width: 900px;
            }
        }
    </style>
@endpush
