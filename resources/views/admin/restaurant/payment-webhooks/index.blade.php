@extends('admin.layouts.main')

@section('page_title', 'Log Webhook Pembayaran')
@section('meta_description', 'Pantau notifikasi payment gateway restoran.')
@section('header_title', 'Log Webhook')
@section('header_subtitle', 'Pantau callback dan proses notifikasi dari payment gateway.')
@section('header_icon', 'server')

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
        Log Webhook
    </li>
@endsection

@section('content')
    <div class="alert alert-info shadow-sm">
        <div class="d-flex align-items-start">
            <i data-feather="info" class="me-3 flex-shrink-0"></i>

            <div>
                <div class="fw-bold">
                    Halaman audit notifikasi payment gateway
                </div>

                <div class="small mt-1">
                    Log webhook bersifat hanya-baca dan digunakan untuk
                    memeriksa proses perubahan status pembayaran dari provider.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card webhook-summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="webhook-summary-icon bg-primary-soft text-primary">
                        <i data-feather="database"></i>
                    </div>

                    <div class="mt-3 text-muted small">
                        Total event
                    </div>

                    <div class="webhook-summary-value">
                        {{ number_format($summary['total']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card webhook-summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="webhook-summary-icon bg-success-soft text-success">
                        <i data-feather="check-circle"></i>
                    </div>

                    <div class="mt-3 text-muted small">
                        Sudah diproses
                    </div>

                    <div class="webhook-summary-value">
                        {{ number_format($summary['processed']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card webhook-summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="webhook-summary-icon bg-warning-soft text-warning">
                        <i data-feather="clock"></i>
                    </div>

                    <div class="mt-3 text-muted small">
                        Belum diproses
                    </div>

                    <div class="webhook-summary-value">
                        {{ number_format($summary['unprocessed']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card webhook-summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="webhook-summary-icon bg-info-soft text-info">
                        <i data-feather="calendar"></i>
                    </div>

                    <div class="mt-3 text-muted small">
                        Event hari ini
                    </div>

                    <div class="webhook-summary-value">
                        {{ number_format($summary['today']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i data-feather="filter" class="me-2"></i>
            Filter Log Webhook
        </div>

        <div class="card-body">
            <form action="{{ route('management.payment-webhooks.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-xl-4 col-md-6 mb-3">
                        <label for="search" class="form-label">
                            Pencarian
                        </label>

                        <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                            placeholder="Event ID, pembayaran, order...">
                    </div>

                    <div class="col-xl-2 col-md-6 mb-3">
                        <label for="provider" class="form-label">
                            Provider
                        </label>

                        <select name="provider" id="provider" class="form-select">
                            <option value="">Semua provider</option>

                            @foreach ($providers as $providerOption)
                                <option value="{{ $providerOption }}" @selected($provider === $providerOption)>
                                    {{ ucfirst($providerOption) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <label for="processing_status" class="form-label">
                            Status Pemrosesan
                        </label>

                        <select name="processing_status" id="processing_status" class="form-select">
                            <option value="">Semua status</option>

                            <option value="processed" @selected($processingStatus === 'processed')>
                                Sudah diproses
                            </option>

                            <option value="unprocessed" @selected($processingStatus === 'unprocessed')>
                                Belum diproses
                            </option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <label for="date" class="form-label">
                            Tanggal Diterima
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

                    <a href="{{ route('management.payment-webhooks.index') }}" class="btn btn-light">
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
                <div class="fw-bold">
                    Daftar Event Webhook
                </div>

                <small class="text-muted">
                    {{ number_format($paymentWebhooks->total()) }}
                    event ditemukan.
                </small>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Provider</th>
                            <th>Pembayaran</th>
                            <th>Pesanan</th>
                            <th>Status</th>
                            <th>Diterima</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($paymentWebhooks as $webhook)
                            <tr>
                                <td>
                                    <a href="{{ route('management.payment-webhooks.show', $webhook) }}"
                                        class="fw-semibold text-decoration-none">
                                        {{ $webhook->event_type ?: 'Unknown event' }}
                                    </a>

                                    <div class="small text-muted text-truncate" style="max-width: 220px;"
                                        title="{{ $webhook->event_id }}">
                                        {{ $webhook->event_id }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ ucfirst($webhook->provider) }}
                                    </span>
                                </td>

                                <td>
                                    @if ($webhook->payment)
                                        <a href="{{ route('management.payments.show', $webhook->payment) }}"
                                            class="text-decoration-none">
                                            {{ $webhook->payment->payment_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            Tidak ditemukan
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($webhook->payment?->order)
                                        <a href="{{ route('management.orders.show', $webhook->payment->order) }}"
                                            class="text-decoration-none">
                                            {{ $webhook->payment->order->order_number }}
                                        </a>

                                        <div class="small text-muted">
                                            {{ $webhook->payment->order->customer_name }}
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($webhook->is_processed)
                                        <span class="badge bg-success">
                                            Diproses
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Belum diproses
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $webhook->created_at->format('d M Y') }}

                                    <div class="small text-muted">
                                        {{ $webhook->created_at->format('H:i:s') }}
                                    </div>
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('management.payment-webhooks.show', $webhook) }}"
                                        class="btn btn-sm btn-outline-primary" title="Lihat detail webhook">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="text-center py-5">
                                        <i data-feather="server" class="text-muted mb-3"
                                            style="width: 48px; height: 48px;"></i>

                                        <div class="fw-bold">
                                            Belum ada log webhook
                                        </div>

                                        <div class="text-muted small mt-1">
                                            Log akan muncul ketika payment gateway
                                            mengirimkan notifikasi.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($paymentWebhooks->hasPages())
            <div class="card-footer">
                {{ $paymentWebhooks->links() }}
            </div>
        @endif
    </div>
@endsection

@push('style')
    <style>
        .webhook-summary-card {
            border: 0;
            border-radius: 0.75rem;
        }

        .webhook-summary-icon {
            width: 2.75rem;
            height: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
        }

        .webhook-summary-icon svg {
            width: 1.2rem;
            height: 1.2rem;
        }

        .webhook-summary-value {
            color: #363d47;
            font-size: 1.75rem;
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
