@extends('admin.layouts.main')

@section('page_title', 'Pembayaran Restoran')
@section('meta_description', 'Daftar pembayaran pesanan restoran.')
@section('header_title', 'Pembayaran Restoran')
@section('header_subtitle', 'Pantau transaksi kasir dan payment gateway restoran.')
@section('header_icon', 'credit-card')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Pembayaran
    </li>
@endsection

@php
    $statusClasses = [
        'pending' => 'bg-warning text-dark',
        'paid' => 'bg-success',
        'failed' => 'bg-danger',
        'expired' => 'bg-secondary',
        'refunded' => 'bg-info text-dark',
    ];
@endphp

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <div class="d-flex align-items-center">
                <i data-feather="check-circle" class="me-2"></i>
                <div>{{ session('success') }}</div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <div class="d-flex align-items-center">
                <i data-feather="alert-circle" class="me-2"></i>
                <div>{{ session('error') }}</div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card payment-summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="payment-summary-icon bg-primary-soft text-primary">
                        <i data-feather="credit-card"></i>
                    </div>

                    <div class="mt-3">
                        <div class="text-muted small">Total transaksi</div>
                        <div class="payment-summary-value">
                            {{ number_format($summary['total']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card payment-summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="payment-summary-icon bg-success-soft text-success">
                        <i data-feather="check-circle"></i>
                    </div>

                    <div class="mt-3">
                        <div class="text-muted small">Transaksi lunas</div>
                        <div class="payment-summary-value">
                            {{ number_format($summary['paid']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card payment-summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="payment-summary-icon bg-warning-soft text-warning">
                        <i data-feather="clock"></i>
                    </div>

                    <div class="mt-3">
                        <div class="text-muted small">Menunggu pembayaran</div>
                        <div class="payment-summary-value">
                            {{ number_format($summary['pending']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card payment-summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="payment-summary-icon bg-info-soft text-info">
                        <i data-feather="dollar-sign"></i>
                    </div>

                    <div class="mt-3">
                        <div class="text-muted small">Total diterima</div>
                        <div class="payment-summary-money">
                            Rp{{ number_format((float) $summary['paid_amount'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <i data-feather="filter" class="me-2"></i>
                Filter Pembayaran
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('management.payments.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <label for="search" class="form-label">
                            Pencarian
                        </label>

                        <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                            placeholder="Nomor pembayaran/order...">
                    </div>

                    <div class="col-xl-2 col-md-6 mb-3">
                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select name="status" id="status" class="form-select">
                            <option value="">Semua status</option>

                            @foreach ($statuses as $statusOption)
                                <option value="{{ $statusOption }}" @selected($status === $statusOption)>
                                    {{ ucwords($statusOption) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6 mb-3">
                        <label for="payment_channel" class="form-label">
                            Channel
                        </label>

                        <select name="payment_channel" id="payment_channel" class="form-select">
                            <option value="">Semua channel</option>

                            @foreach ($channels as $channel)
                                <option value="{{ $channel }}" @selected($paymentChannel === $channel)>
                                    {{ $channel === 'cashier' ? 'Kasir' : 'Gateway' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <label for="payment_method" class="form-label">
                            Metode
                        </label>

                        <select name="payment_method" id="payment_method" class="form-select">
                            <option value="">Semua metode</option>

                            @foreach ($methods as $method)
                                <option value="{{ $method }}" @selected($paymentMethod === $method)>
                                    {{ ucwords(str_replace('_', ' ', $method)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6 mb-3">
                        <label for="date" class="form-label">
                            Tanggal
                        </label>

                        <input type="date" name="date" id="date" class="form-control"
                            value="{{ $date }}">
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="search" class="me-2"></i>
                        Terapkan
                    </button>

                    <a href="{{ route('management.payments.index') }}" class="btn btn-light">
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
                <div class="fw-bold">Daftar Pembayaran</div>
                <small class="text-muted">
                    {{ number_format($payments->total()) }} transaksi ditemukan.
                </small>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Pembayaran</th>
                            <th>Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Metode</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>
                                    <a href="{{ route('management.payments.show', $payment) }}"
                                        class="fw-bold text-decoration-none">
                                        {{ $payment->payment_number }}
                                    </a>

                                    <div class="small text-muted">
                                        {{ $payment->payment_channel === 'cashier' ? 'Kasir' : 'Gateway' }}
                                    </div>
                                </td>

                                <td>
                                    @if ($payment->order)
                                        <a href="{{ route('management.orders.show', $payment->order) }}"
                                            class="text-decoration-none">
                                            {{ $payment->order->order_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">Order terhapus</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $payment->order?->customer_name ?? '-' }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $payment->order?->customer_phone ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ ucwords(str_replace('_', ' ', $payment->payment_method ?? '-')) }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ ucfirst($payment->provider ?? 'manual') }}
                                    </div>
                                </td>

                                <td class="fw-bold">
                                    Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="badge {{ $statusClasses[$payment->status] ?? 'bg-secondary' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $payment->created_at->format('d M Y') }}

                                    <div class="small text-muted">
                                        {{ $payment->created_at->format('H:i') }}
                                    </div>
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('management.payments.show', $payment) }}"
                                        class="btn btn-sm btn-outline-primary" title="Lihat pembayaran">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="text-center py-5">
                                        <i data-feather="credit-card" class="text-muted mb-3"
                                            style="width: 48px; height: 48px;"></i>

                                        <div class="fw-bold">
                                            Belum ada pembayaran
                                        </div>

                                        <div class="text-muted small mt-1">
                                            Pembayaran pesanan akan muncul di sini.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($payments->hasPages())
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection

@push('style')
    <style>
        .payment-summary-card {
            border: 0;
            border-radius: 0.75rem;
        }

        .payment-summary-icon {
            width: 2.75rem;
            height: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
        }

        .payment-summary-icon svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .payment-summary-value {
            color: #363d47;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .payment-summary-money {
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
            .table {
                min-width: 1050px;
            }
        }
    </style>
@endpush
