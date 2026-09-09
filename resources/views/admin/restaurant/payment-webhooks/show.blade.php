@extends('admin.layouts.main')

@section('page_title', 'Detail Log Webhook')
@section('meta_description', 'Detail payload notifikasi payment gateway.')
@section('header_title', 'Detail Log Webhook')
@section('header_subtitle', 'Periksa event dan payload yang diterima dari payment gateway.')
@section('header_icon', 'server')

@section('header_action')
    <a href="{{ route('management.payment-webhooks.index') }}" class="btn btn-light">
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
        <a href="{{ route('management.payment-webhooks.index') }}">
            Log Webhook
        </a>
    </li>

    <li class="breadcrumb-item active">
        Detail
    </li>
@endsection

@php
    $payment = $paymentWebhook->payment;
    $order = $payment?->order;

    $formattedPayload = json_encode(
        $paymentWebhook->payload ?? [],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
    );
@endphp

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow-sm mb-4 overflow-hidden">
                <div class="webhook-hero">
                    <div>
                        <div class="webhook-hero-label">
                            Event webhook
                        </div>

                        <h4 class="text-white mb-2">
                            {{ $paymentWebhook->event_type ?: 'Unknown event' }}
                        </h4>

                        @if ($paymentWebhook->is_processed)
                            <span class="badge bg-success">
                                Sudah diproses
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                Belum diproses
                            </span>
                        @endif
                    </div>

                    <div class="text-xl-end mt-3 mt-xl-0">
                        <div class="webhook-hero-label">
                            Provider
                        </div>

                        <div class="text-white fw-bold fs-4">
                            {{ ucfirst($paymentWebhook->provider) }}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="webhook-info-box">
                                <div class="webhook-info-icon">
                                    <i data-feather="hash"></i>
                                </div>

                                <div class="overflow-hidden">
                                    <div class="webhook-info-label">
                                        Event ID
                                    </div>

                                    <div class="fw-semibold text-break">
                                        {{ $paymentWebhook->event_id }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="webhook-info-box">
                                <div class="webhook-info-icon">
                                    <i data-feather="clock"></i>
                                </div>

                                <div>
                                    <div class="webhook-info-label">
                                        Diterima
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $paymentWebhook->created_at->format('d M Y H:i:s') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="webhook-info-box">
                                <div class="webhook-info-icon">
                                    <i data-feather="credit-card"></i>
                                </div>

                                <div>
                                    <div class="webhook-info-label">
                                        Pembayaran
                                    </div>

                                    @if ($payment)
                                        <a href="{{ route('management.payments.show', $payment) }}"
                                            class="fw-semibold text-decoration-none">
                                            {{ $payment->payment_number }}
                                        </a>

                                        <div class="small text-muted">
                                            Status {{ ucfirst($payment->status) }}
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            Tidak terhubung
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="webhook-info-box">
                                <div class="webhook-info-icon">
                                    <i data-feather="shopping-cart"></i>
                                </div>

                                <div>
                                    <div class="webhook-info-label">
                                        Pesanan
                                    </div>

                                    @if ($order)
                                        <a href="{{ route('management.orders.show', $order) }}"
                                            class="fw-semibold text-decoration-none">
                                            {{ $order->order_number }}
                                        </a>

                                        <div class="small text-muted">
                                            {{ $order->customer_name }}
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            Tidak terhubung
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i data-feather="code" class="me-2"></i>
                            Payload Webhook
                        </div>

                        <button type="button" id="copyPayloadButton" class="btn btn-outline-primary btn-sm">
                            <i data-feather="copy" class="me-1"></i>
                            Salin JSON
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <pre id="webhookPayload" class="webhook-payload mb-0">{{ $formattedPayload }}</pre>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i data-feather="activity" class="me-2"></i>
                    Hasil Pemrosesan
                </div>

                <div class="card-body">
                    <div class="webhook-detail-row">
                        <span>Provider</span>
                        <strong>
                            {{ ucfirst($paymentWebhook->provider) }}
                        </strong>
                    </div>

                    <div class="webhook-detail-row">
                        <span>Jenis event</span>
                        <strong>
                            {{ $paymentWebhook->event_type ?: '-' }}
                        </strong>
                    </div>

                    <div class="webhook-detail-row">
                        <span>Status</span>

                        @if ($paymentWebhook->is_processed)
                            <span class="badge bg-success">
                                Diproses
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                Belum diproses
                            </span>
                        @endif
                    </div>

                    <div class="webhook-detail-row">
                        <span>Waktu diproses</span>
                        <strong>
                            {{ optional($paymentWebhook->processed_at)->format('d M Y H:i:s') ?? '-' }}
                        </strong>
                    </div>

                    <hr>

                    <div>
                        <div class="text-muted small mb-2">
                            Pesan pemrosesan
                        </div>

                        <div class="webhook-message">
                            {{ $paymentWebhook->processing_message ?: 'Belum ada pesan pemrosesan.' }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($payment)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i data-feather="dollar-sign" class="me-2"></i>
                        Transaksi Terkait
                    </div>

                    <div class="card-body">
                        <div class="webhook-detail-row">
                            <span>Nominal</span>
                            <strong>
                                Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="webhook-detail-row">
                            <span>Metode</span>
                            <strong>
                                {{ ucwords(str_replace('_', ' ', $payment->payment_method ?? '-')) }}
                            </strong>
                        </div>

                        <div class="webhook-detail-row">
                            <span>Status</span>
                            <strong>
                                {{ ucfirst($payment->status) }}
                            </strong>
                        </div>

                        <div class="webhook-detail-row">
                            <span>Transaction ID</span>
                            <strong class="text-break text-end">
                                {{ $payment->provider_transaction_id ?: '-' }}
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
        .webhook-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg,
                    #0061f2 0%,
                    #6900c7 100%);
        }

        .webhook-hero-label {
            margin-bottom: 0.35rem;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .webhook-info-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            height: 100%;
            padding: 1rem;
            border: 1px solid #e0e5ec;
            border-radius: 0.75rem;
        }

        .webhook-info-icon {
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

        .webhook-info-icon svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .webhook-info-label {
            color: #69707a;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .webhook-payload {
            max-height: 650px;
            overflow: auto;
            padding: 1.25rem;
            color: #e8eaed;
            background: #17202a;
            font-size: 0.8rem;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .webhook-detail-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.85rem;
            color: #69707a;
            font-size: 0.875rem;
        }

        .webhook-detail-row strong {
            color: #363d47;
            text-align: right;
        }

        .webhook-message {
            padding: 0.85rem;
            border-radius: 0.5rem;
            background: #f8f9fa;
            font-size: 0.875rem;
            white-space: pre-wrap;
        }

        @media (max-width: 767.98px) {
            .webhook-hero {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButton = document.getElementById(
                'copyPayloadButton'
            );

            const payloadElement = document.getElementById(
                'webhookPayload'
            );

            copyButton.addEventListener('click', async function() {
                try {
                    await navigator.clipboard.writeText(
                        payloadElement.textContent
                    );

                    Swal.fire({
                        icon: 'success',
                        title: 'Payload Disalin',
                        text: 'JSON webhook berhasil disalin.',
                        timer: 1500,
                        showConfirmButton: false,
                    });
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyalin',
                        text: 'Browser tidak mengizinkan akses clipboard.',
                    });
                }
            });
        });
    </script>
@endpush
