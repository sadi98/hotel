@extends('admin.layouts.main')

@section('page_title', 'Pesanan Restoran')
@section('meta_description', 'Kelola seluruh pesanan restoran.')
@section('header_title', 'Pesanan Restoran')
@section('header_subtitle', 'Kelola pesanan dine-in, delivery, dan room service.')
@section('header_icon', 'shopping-cart')

@section('header_action')
    <a href="{{ route('management.orders.create') }}" class="btn btn-light">
        <i data-feather="plus" class="me-2"></i>
        Buat Pesanan
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Pesanan
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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i data-feather="alert-circle" class="me-2"></i>
            {{ session('error') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <i data-feather="filter" class="me-2"></i>
                Filter Pesanan
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('management.orders.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <label for="search" class="form-label">
                            Pencarian
                        </label>

                        <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                            placeholder="Nomor order, nama, telepon...">
                    </div>

                    <div class="col-xl-2 col-md-6 mb-3">
                        <label for="status" class="form-label">
                            Status Pesanan
                        </label>

                        <select name="status" id="status" class="form-select">
                            <option value="">Semua status</option>

                            @foreach ($statuses as $statusOption)
                                <option value="{{ $statusOption }}" @selected($status === $statusOption)>
                                    {{ ucwords(str_replace('_', ' ', $statusOption)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6 mb-3">
                        <label for="payment_status" class="form-label">
                            Status Pembayaran
                        </label>

                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="">Semua pembayaran</option>

                            @foreach (['unpaid', 'pending', 'paid', 'failed', 'refunded'] as $paymentOption)
                                <option value="{{ $paymentOption }}" @selected($paymentStatus === $paymentOption)>
                                    {{ ucwords($paymentOption) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6 mb-3">
                        <label for="order_type" class="form-label">
                            Jenis Pesanan
                        </label>

                        <select name="order_type" id="order_type" class="form-select">
                            <option value="">Semua jenis</option>

                            <option value="dine_in" @selected($orderType === 'dine_in')>
                                Dine In
                            </option>

                            <option value="delivery" @selected($orderType === 'delivery')>
                                Delivery
                            </option>

                            <option value="room_service" @selected($orderType === 'room_service')>
                                Room Service
                            </option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <label for="date" class="form-label">
                            Tanggal Pesanan
                        </label>

                        <input type="date" name="date" id="date" class="form-control"
                            value="{{ $date }}">
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="search" class="me-2"></i>
                        Terapkan Filter
                    </button>

                    <a href="{{ route('management.orders.index') }}" class="btn btn-light">
                        <i data-feather="refresh-cw" class="me-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="fw-bold">Daftar Pesanan</div>
                    <small class="text-muted">
                        Total {{ number_format($orders->total()) }} pesanan ditemukan.
                    </small>
                </div>

                <a href="{{ route('management.orders.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus" class="me-1"></i>
                    Buat Pesanan
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Jenis</th>
                            <th>Total</th>
                            <th>Status Pesanan</th>
                            <th>Pembayaran</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($orders as $order)
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

                                $paymentStatusClasses = [
                                    'unpaid' => 'bg-warning text-dark',
                                    'pending' => 'bg-info text-dark',
                                    'paid' => 'bg-success',
                                    'failed' => 'bg-danger',
                                    'refunded' => 'bg-secondary',
                                ];

                                $editable =
                                    !in_array($order->status, ['completed', 'cancelled'], true) &&
                                    $order->payment_status !== 'paid';
                            @endphp

                            <tr>
                                <td>
                                    <a href="{{ route('management.orders.show', $order) }}"
                                        class="fw-bold text-decoration-none">
                                        {{ $order->order_number }}
                                    </a>

                                    <div class="small text-muted">
                                        Dibuat oleh:
                                        {{ $order->createdBy?->name ?? 'Customer' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $order->customer_name }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $order->customer_phone }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ ucwords(str_replace('_', ' ', $order->order_type)) }}
                                    </div>

                                    @if ($order->order_type === 'dine_in')
                                        <div class="small text-muted">
                                            Meja:
                                            {{ $order->restaurantTable?->table_number ?? '-' }}
                                        </div>
                                    @elseif ($order->order_type === 'room_service')
                                        <div class="small text-muted">
                                            Kamar: {{ $order->room_number ?? '-' }}
                                        </div>
                                    @endif
                                </td>

                                <td class="fw-bold">
                                    Rp{{ number_format((float) $order->grand_total, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="badge {{ $orderStatusClasses[$order->status] ?? 'bg-secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>

                                <td>
                                    <span
                                        class="badge {{ $paymentStatusClasses[$order->payment_status] ?? 'bg-secondary' }}">
                                        {{ ucwords($order->payment_status) }}
                                    </span>

                                    <div class="small text-muted mt-1">
                                        {{ $order->payment_timing === 'pay_later' ? 'Bayar nanti' : 'Bayar sekarang' }}
                                    </div>
                                </td>

                                <td>
                                    {{ optional($order->ordered_at)->format('d M Y') ?? '-' }}

                                    <div class="small text-muted">
                                        {{ optional($order->ordered_at)->format('H:i') ?? '-' }}
                                    </div>
                                </td>

                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('management.orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary" title="Lihat pesanan">
                                            <i data-feather="eye"></i>
                                        </a>

                                        @if ($editable)
                                            <a href="{{ route('management.orders.edit', $order) }}"
                                                class="btn btn-sm btn-outline-warning" title="Edit pesanan">
                                                <i data-feather="edit-2"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="text-center py-5">
                                        <i data-feather="shopping-bag" class="text-muted mb-3"
                                            style="width: 48px; height: 48px;"></i>

                                        <div class="fw-bold">
                                            Belum ada pesanan
                                        </div>

                                        <div class="text-muted small mt-1 mb-3">
                                            Pesanan pelanggan akan tampil di halaman ini.
                                        </div>

                                        <a href="{{ route('management.orders.create') }}" class="btn btn-primary btn-sm">
                                            <i data-feather="plus" class="me-1"></i>
                                            Buat Pesanan Pertama
                                        </a>
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
@endsection

@push('style')
    <style>
        .table th {
            white-space: nowrap;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #69707a;
            background: #f8f9fa;
        }

        .table td {
            vertical-align: middle;
        }

        .table .btn svg {
            width: 0.95rem;
            height: 0.95rem;
        }

        @media (max-width: 767.98px) {
            .table {
                min-width: 1050px;
            }
        }
    </style>
@endpush
