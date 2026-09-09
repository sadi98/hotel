@extends('admin.layouts.main')

@section('page_title', 'Detail Pembayaran ' . $payment->payment_number)
@section('meta_description', 'Detail transaksi pembayaran restoran.')
@section('header_title', 'Detail Pembayaran')
@section('header_subtitle', 'Informasi transaksi ' . $payment->payment_number . '.')
@section('header_icon', 'credit-card')

@section('header_action')
    <a href="{{ route('management.payments.index') }}" class="btn btn-light">
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
        <a href="{{ route('management.payments.index') }}">
            Pembayaran
        </a>
    </li>

    <li class="breadcrumb-item active">
        {{ $payment->payment_number }}
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

    $order = $payment->order;
@endphp

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow-sm mb-4 overflow-hidden">
                <div class="payment-hero">
                    <div>
                        <div class="payment-hero-label">
                            Nomor pembayaran
                        </div>

                        <h4 class="text-white mb-2">
                            {{ $payment->payment_number }}
                        </h4>

                        <span class="badge {{ $statusClasses[$payment->status] ?? 'bg-secondary' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>

                    <div class="text-xl-end mt-3 mt-xl-0">
                        <div class="payment-hero-label">
                            Nominal pembayaran
                        </div>

                        <div class="payment-hero-amount">
                            Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="payment-info-box">
                                <div class="payment-info-icon">
                                    <i data-feather="shopping-cart"></i>
                                </div>

                                <div>
                                    <div class="payment-info-label">
                                        Pesanan
                                    </div>

                                    @if ($order)
                                        <a href="{{ route('management.orders.show', $order) }}"
                                            class="fw-bold text-decoration-none">
                                            {{ $order->order_number }}
                                        </a>
                                    @else
                                        <div class="fw-bold">
                                            Order tidak tersedia
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="payment-info-box">
                                <div class="payment-info-icon">
                                    <i data-feather="user"></i>
                                </div>

                                <div>
                                    <div class="payment-info-label">
                                        Pelanggan
                                    </div>

                                    <div class="fw-bold">
                                        {{ $order?->customer_name ?? '-' }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $order?->customer_phone ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="payment-info-box">
                                <div class="payment-info-icon">
                                    <i data-feather="credit-card"></i>
                                </div>

                                <div>
                                    <div class="payment-info-label">
                                        Metode
                                    </div>

                                    <div class="fw-bold">
                                        {{ ucwords(str_replace('_', ' ', $payment->payment_method ?? '-')) }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ ucfirst($payment->provider ?? 'manual') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="payment-info-box">
                                <div class="payment-info-icon">
                                    <i data-feather="user-check"></i>
                                </div>

                                <div>
                                    <div class="payment-info-label">
                                        Diproses oleh
                                    </div>

                                    <div class="fw-bold">
                                        {{ $payment->processedBy?->name ?? 'Sistem' }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $payment->payment_channel === 'cashier' ? 'Pembayaran kasir' : 'Payment gateway' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($order)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i data-feather="coffee" class="me-2"></i>
                        Item Pesanan
                    </div>

                    <div class="card-body p-0">
                        @foreach ($order->items as $item)
                            <div class="payment-order-item">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $item->quantity }}×
                                            {{ $item->item_name }}
                                        </div>

                                        <div class="small text-muted">
                                            Rp{{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                            per item
                                        </div>
                                    </div>

                                    <strong>
                                        Rp{{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                    </strong>
                                </div>

                                @if ($item->components->isNotEmpty())
                                    <div class="payment-components mt-2">
                                        @foreach ($item->components as $component)
                                            <div class="small text-muted">
                                                {{ $component->total_quantity }}×
                                                {{ $component->menu_name }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($payment->webhooks->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i data-feather="server" class="me-2"></i>
                        Log Payment Gateway
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Provider</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($payment->webhooks as $webhook)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $webhook->event_type ?? '-' }}
                                                </div>

                                                <div class="small text-muted">
                                                    {{ $webhook->event_id }}
                                                </div>
                                            </td>

                                            <td>
                                                {{ ucfirst($webhook->provider) }}
                                            </td>

                                            <td>
                                                @if ($webhook->is_processed)
                                                    <span class="badge bg-success">
                                                        Diproses
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        Menunggu
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                {{ $webhook->created_at->format('d M Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-xl-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i data-feather="file-text" class="me-2"></i>
                    Rincian Transaksi
                </div>

                <div class="card-body">
                    <div class="detail-row">
                        <span>Channel</span>
                        <strong>
                            {{ $payment->payment_channel === 'cashier' ? 'Kasir' : 'Gateway' }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Provider</span>
                        <strong>
                            {{ ucfirst($payment->provider ?? 'Manual') }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Metode</span>
                        <strong>
                            {{ ucwords(str_replace('_', ' ', $payment->payment_method ?? '-')) }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Jumlah tagihan</span>
                        <strong>
                            Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Jumlah diterima</span>
                        <strong>
                            Rp{{ number_format((float) ($payment->paid_amount ?? 0), 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Kembalian</span>
                        <strong class="text-success">
                            Rp{{ number_format((float) $payment->change_amount, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Status</span>
                        <span class="badge {{ $statusClasses[$payment->status] ?? 'bg-secondary' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>

                    <div class="detail-row">
                        <span>Dibuat</span>
                        <strong>
                            {{ $payment->created_at->format('d M Y H:i') }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Dibayar</span>
                        <strong>
                            {{ optional($payment->paid_at)->format('d M Y H:i') ?? '-' }}
                        </strong>
                    </div>

                    @if ($payment->expired_at)
                        <div class="detail-row">
                            <span>Kedaluwarsa</span>
                            <strong>
                                {{ $payment->expired_at->format('d M Y H:i') }}
                            </strong>
                        </div>
                    @endif

                    @if ($payment->provider_transaction_id)
                        <hr>

                        <div>
                            <div class="text-muted small">
                                ID Transaksi Provider
                            </div>

                            <div class="fw-semibold text-break mt-1">
                                {{ $payment->provider_transaction_id }}
                            </div>
                        </div>
                    @endif

                    @if ($payment->note)
                        <hr>

                        <div>
                            <div class="text-muted small">
                                Catatan
                            </div>

                            <div class="mt-1">
                                {{ $payment->note }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($order)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i data-feather="percent" class="me-2"></i>
                        Snapshot Tagihan Pesanan
                    </div>

                    <div class="card-body">
                        <div class="detail-row">
                            <span>Subtotal</span>
                            <strong>
                                Rp{{ number_format((float) $order->subtotal, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="detail-row">
                            <span>Diskon</span>
                            <strong class="text-danger">
                                -
                                Rp{{ number_format((float) $order->discount_amount, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="detail-row">
                            <span>
                                Pajak
                                ({{ number_format((float) $order->tax_percentage, 2, ',', '.') }}%)
                            </span>

                            <strong>
                                Rp{{ number_format((float) $order->tax_amount, 0, ',', '.') }}
                            </strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between gap-3">
                            <strong>Total Pesanan</strong>

                            <strong class="text-primary fs-5">
                                Rp{{ number_format((float) $order->grand_total, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style')
    <style>
        .payment-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg,
                    #0061f2 0%,
                    #6900c7 100%);
        }

        .payment-hero-label {
            margin-bottom: 0.35rem;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .payment-hero-amount {
            color: #fff;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .payment-info-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            height: 100%;
            padding: 1rem;
            border: 1px solid #e0e5ec;
            border-radius: 0.75rem;
        }

        .payment-info-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 0.65rem;
            color: #0061f2;
            background: rgba(0, 97, 242, 0.1);
        }

        .payment-info-icon svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .payment-info-label {
            color: #69707a;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .payment-order-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .payment-order-item:last-child {
            border-bottom: 0;
        }

        .payment-components {
            margin-left: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            background: #f8f9fa;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.85rem;
            color: #69707a;
            font-size: 0.875rem;
        }

        .detail-row strong {
            color: #363d47;
            text-align: right;
        }

        @media (max-width: 767.98px) {
            .payment-hero {
                align-items: flex-start;
                flex-direction: column;
            }

            .payment-hero-amount {
                font-size: 1.4rem;
            }
        }
    </style>
@endpush
