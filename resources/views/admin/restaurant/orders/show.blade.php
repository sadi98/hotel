@extends('admin.layouts.main')

@section('page_title', 'Detail Pesanan ' . $order->order_number)
@section('meta_description', 'Detail dan proses pesanan restoran.')
@section('header_title', 'Detail Pesanan')
@section('header_subtitle', 'Pantau item, status, dan pembayaran pesanan ' . $order->order_number . '.')
@section('header_icon', 'shopping-cart')

@section('header_action')
    <div class="d-flex flex-wrap gap-2">
        @if ($order->payment_status !== 'paid' && !in_array($order->status, ['completed', 'cancelled'], true))
            <a href="{{ route('management.orders.edit', $order) }}" class="btn btn-warning">
                <i data-feather="edit-2" class="me-2"></i>
                Edit Pesanan
            </a>
        @endif

        <a href="{{ route('management.orders.index') }}" class="btn btn-light">
            <i data-feather="arrow-left" class="me-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('management.orders.index') }}">Pesanan</a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        {{ $order->order_number }}
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

    $paymentStatusClasses = [
        'unpaid' => 'bg-warning text-dark',
        'pending' => 'bg-info text-dark',
        'paid' => 'bg-success',
        'failed' => 'bg-danger',
        'refunded' => 'bg-secondary',
    ];

    $itemStatusClasses = [
        'pending' => 'bg-warning text-dark',
        'preparing' => 'bg-primary',
        'ready' => 'bg-info text-dark',
        'served' => 'bg-success',
        'cancelled' => 'bg-danger',
    ];

    $orderIsFinal = in_array($order->status, ['completed', 'cancelled'], true);
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <strong>Data belum dapat diproses.</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow-sm mb-4 overflow-hidden">
                <div class="order-hero">
                    <div>
                        <div class="order-number-label">Nomor pesanan</div>
                        <h4 class="mb-2 text-white">{{ $order->order_number }}</h4>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge {{ $orderStatusClasses[$order->status] ?? 'bg-secondary' }}">
                                {{ ucwords(str_replace('_', ' ', $order->status)) }}
                            </span>

                            <span class="badge {{ $paymentStatusClasses[$order->payment_status] ?? 'bg-secondary' }}">
                                Pembayaran {{ ucwords($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="text-xl-end mt-3 mt-xl-0">
                        <div class="order-number-label">Total tagihan</div>
                        <div class="order-hero-total">
                            Rp{{ number_format((float) $order->grand_total, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4">
                            <div class="order-info-box">
                                <div class="order-info-icon">
                                    <i data-feather="user"></i>
                                </div>

                                <div>
                                    <div class="order-info-label">Pelanggan</div>
                                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                                    <div class="small text-muted">{{ $order->customer_phone }}</div>

                                    @if ($order->customer_email)
                                        <div class="small text-muted">{{ $order->customer_email }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="order-info-box">
                                <div class="order-info-icon">
                                    <i data-feather="map-pin"></i>
                                </div>

                                <div>
                                    <div class="order-info-label">Jenis pesanan</div>
                                    <div class="fw-semibold">
                                        {{ ucwords(str_replace('_', ' ', $order->order_type)) }}
                                    </div>

                                    @if ($order->order_type === 'dine_in')
                                        <div class="small text-muted">
                                            Meja {{ $order->restaurantTable?->table_number ?? '-' }}
                                            {{ $order->restaurantTable?->area ? '· ' . $order->restaurantTable->area : '' }}
                                        </div>
                                    @elseif ($order->order_type === 'room_service')
                                        <div class="small text-muted">
                                            Kamar {{ $order->room_number ?? '-' }}
                                        </div>
                                    @else
                                        <div class="small text-muted">
                                            {{ $order->delivery_address ?? '-' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="order-info-box">
                                <div class="order-info-icon">
                                    <i data-feather="clock"></i>
                                </div>

                                <div>
                                    <div class="order-info-label">Waktu pesanan</div>
                                    <div class="fw-semibold">
                                        {{ optional($order->ordered_at)->format('d M Y') ?? '-' }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ optional($order->ordered_at)->format('H:i') ?? '-' }} WIB
                                    </div>

                                    @if ($order->scheduled_at)
                                        <div class="small text-primary mt-1">
                                            Jadwal:
                                            {{ $order->scheduled_at->format('d M Y H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="order-info-box">
                                <div class="order-info-icon">
                                    <i data-feather="users"></i>
                                </div>

                                <div>
                                    <div class="order-info-label">Jumlah tamu</div>
                                    <div class="fw-semibold">
                                        {{ number_format($order->guest_count) }} orang
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="order-info-box">
                                <div class="order-info-icon">
                                    <i data-feather="credit-card"></i>
                                </div>

                                <div>
                                    <div class="order-info-label">Waktu pembayaran</div>
                                    <div class="fw-semibold">
                                        {{ $order->payment_timing === 'pay_later' ? 'Bayar nanti' : 'Bayar sekarang' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="order-info-box">
                                <div class="order-info-icon">
                                    <i data-feather="user-check"></i>
                                </div>

                                <div>
                                    <div class="order-info-label">Dibuat oleh</div>
                                    <div class="fw-semibold">
                                        {{ $order->createdBy?->name ?? ($order->user?->name ?? 'Guest') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <i data-feather="coffee" class="me-2"></i>
                        <div>
                            <div class="fw-bold">Item Pesanan</div>
                            <small class="text-muted">
                                {{ $order->items->count() }} jenis item dalam pesanan.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @forelse ($order->items as $item)
                        <div class="order-item">
                            <div class="d-flex flex-wrap justify-content-between gap-3">
                                <div class="d-flex gap-3">
                                    <div class="order-item-quantity">
                                        {{ $item->quantity }}×
                                    </div>

                                    <div>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <h6 class="mb-0">{{ $item->item_name }}</h6>

                                            <span class="badge bg-light text-dark">
                                                {{ $item->item_type === 'package' ? 'Paket' : 'Menu' }}
                                            </span>

                                            <span class="badge {{ $itemStatusClasses[$item->status] ?? 'bg-secondary' }}">
                                                {{ ucwords($item->status) }}
                                            </span>
                                        </div>

                                        @if ($item->item_sku)
                                            <div class="small text-muted mt-1">
                                                SKU: {{ $item->item_sku }}
                                            </div>
                                        @endif

                                        <div class="small text-muted mt-1">
                                            Rp{{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                            × {{ $item->quantity }}
                                        </div>

                                        @if ($item->note)
                                            <div class="order-note mt-2">
                                                <i data-feather="message-circle"></i>
                                                {{ $item->note }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="fw-bold fs-6">
                                        Rp{{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            @if ($item->components->isNotEmpty())
                                <div class="package-components">
                                    <div class="small fw-semibold text-muted mb-2">
                                        Isi paket
                                    </div>

                                    <div class="row g-2">
                                        @foreach ($item->components as $component)
                                            <div class="col-md-6">
                                                <div class="component-item">
                                                    <div>
                                                        <span class="fw-semibold">
                                                            {{ $component->total_quantity }}×
                                                            {{ $component->menu_name }}
                                                        </span>

                                                        @if ($component->note)
                                                            <div class="small text-muted">
                                                                {{ $component->note }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <span
                                                        class="badge {{ $itemStatusClasses[$component->status] ?? 'bg-secondary' }}">
                                                        {{ ucfirst($component->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!$orderIsFinal)
                                <form method="POST"
                                    action="{{ route('management.orders.items.status', [$order, $item]) }}"
                                    class="item-status-form mt-3">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-8">
                                            <label class="form-label small">
                                                Status item
                                            </label>

                                            <select name="status" class="form-select form-select-sm">
                                                @foreach ($itemStatuses as $itemStatus)
                                                    <option value="{{ $itemStatus }}" @selected($item->status === $itemStatus)>
                                                        {{ ucwords(str_replace('_', ' ', $itemStatus)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                                Perbarui Item
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            Tidak ada item dalam pesanan.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <i data-feather="message-square" class="me-2"></i>
                            Catatan Pelanggan
                        </div>

                        <div class="card-body">
                            {{ $order->customer_note ?: 'Tidak ada catatan pelanggan.' }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <i data-feather="lock" class="me-2"></i>
                            Catatan Internal
                        </div>

                        <div class="card-body">
                            {{ $order->internal_note ?: 'Tidak ada catatan internal.' }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($order->payments->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i data-feather="credit-card" class="me-2"></i>
                        Riwayat Pembayaran
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor Pembayaran</th>
                                        <th>Metode</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Diproses</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($order->payments as $payment)
                                        <tr>
                                            <td>
                                                <a href="{{ route('management.payments.show', $payment) }}"
                                                    class="fw-semibold text-decoration-none">
                                                    {{ $payment->payment_number }}
                                                </a>
                                            </td>

                                            <td>
                                                {{ ucwords(str_replace('_', ' ', $payment->payment_method ?? '-')) }}
                                            </td>

                                            <td>
                                                Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                <span
                                                    class="badge {{ $paymentStatusClasses[$payment->status] ?? 'bg-secondary' }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $payment->processedBy?->name ?? 'Sistem' }}
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
                    <i data-feather="dollar-sign" class="me-2"></i>
                    Ringkasan Tagihan
                </div>

                <div class="card-body">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <strong>
                            Rp{{ number_format((float) $order->subtotal, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="total-row">
                        <span>Diskon</span>
                        <strong class="text-danger">
                            - Rp{{ number_format((float) $order->discount_amount, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="total-row">
                        <span>
                            Service charge
                            ({{ number_format((float) $order->service_charge_percentage, 2, ',', '.') }}%)
                        </span>

                        <strong>
                            Rp{{ number_format((float) $order->service_charge_amount, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="total-row">
                        <span>
                            Pajak
                            ({{ number_format((float) $order->tax_percentage, 2, ',', '.') }}%)
                        </span>

                        <strong>
                            Rp{{ number_format((float) $order->tax_amount, 0, ',', '.') }}
                        </strong>
                    </div>

                    <hr>

                    <div class="grand-total">
                        <span>Total</span>
                        <span>
                            Rp{{ number_format((float) $order->grand_total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i data-feather="activity" class="me-2"></i>
                    Proses Pesanan
                </div>

                <div class="card-body">
                    @if ($orderIsFinal)
                        <div class="alert alert-info mb-0">
                            Pesanan berstatus
                            <strong>{{ ucfirst($order->status) }}</strong>
                            dan tidak dapat dikembalikan ke status sebelumnya.
                        </div>
                    @else
                        <form id="statusForm" method="POST" action="{{ route('management.orders.status', $order) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    Status Pesanan
                                </label>

                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="internal_note" class="form-label">
                                    Catatan Internal
                                </label>

                                <textarea name="internal_note" id="internal_note" class="form-control" rows="3" maxlength="3000">{{ old('internal_note', $order->internal_note) }}</textarea>
                            </div>

                            <div id="cancellationField" class="mb-3 d-none">
                                <label for="cancellation_reason" class="form-label">
                                    Alasan Pembatalan
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3" maxlength="2000">{{ old('cancellation_reason', $order->cancellation_reason) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i data-feather="refresh-cw" class="me-2"></i>
                                Perbarui Status
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if ($order->payment_status !== 'paid' && $order->status !== 'cancelled')
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i data-feather="credit-card" class="me-2"></i>
                        Pembayaran Kasir
                    </div>

                    <div class="card-body">
                        <form id="paymentForm" method="POST" action="{{ route('management.orders.pay', $order) }}">
                            @csrf

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">
                                    Metode Pembayaran
                                </label>

                                <select name="payment_method" id="payment_method"
                                    class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="cash">Tunai</option>
                                    <option value="card">Kartu Debit/Kredit</option>
                                    <option value="qris">QRIS</option>
                                    <option value="ewallet">E-Wallet</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="paid_amount" class="form-label">
                                    Nominal Diterima
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>

                                    <input type="number" name="paid_amount" id="paid_amount"
                                        class="form-control @error('paid_amount') is-invalid @enderror"
                                        min="{{ (float) $order->grand_total }}" step="1"
                                        value="{{ old('paid_amount', (float) $order->grand_total) }}" required>
                                </div>
                            </div>

                            <div class="cash-change-box mb-3">
                                <span>Perkiraan kembalian</span>
                                <strong id="changeAmount">Rp0</strong>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i data-feather="check-circle" class="me-2"></i>
                                Simpan Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            @elseif ($order->payment_status === 'paid')
                <div class="card shadow-sm mb-4 border-success">
                    <div class="card-body text-center py-4">
                        <div class="paid-icon">
                            <i data-feather="check"></i>
                        </div>

                        <h5 class="mt-3 mb-1 text-success">
                            Pesanan Sudah Lunas
                        </h5>

                        <div class="text-muted">
                            Pembayaran pesanan telah berhasil diterima.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style')
    <style>
        .order-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #0061f2 0%, #6900c7 100%);
        }

        .order-number-label {
            margin-bottom: 0.35rem;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .order-hero-total {
            color: #fff;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .order-info-box {
            display: flex;
            gap: 0.75rem;
            height: 100%;
            padding: 1rem;
            border: 1px solid #e0e5ec;
            border-radius: 0.75rem;
            background: #fff;
        }

        .order-info-icon {
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

        .order-info-icon svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .order-info-label {
            color: #69707a;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .order-item {
            padding: 1.25rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .order-item:last-child {
            border-bottom: 0;
        }

        .order-item-quantity {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 0.65rem;
            color: #0061f2;
            background: rgba(0, 97, 242, 0.1);
            font-weight: 700;
        }

        .order-note {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #69707a;
            font-size: 0.8rem;
        }

        .order-note svg {
            width: 0.9rem;
            height: 0.9rem;
        }

        .package-components {
            margin-top: 1rem;
            margin-left: 3.5rem;
            padding: 1rem;
            border-radius: 0.65rem;
            background: #f8f9fa;
        }

        .component-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.65rem;
            border: 1px solid #e0e5ec;
            border-radius: 0.5rem;
            background: #fff;
            font-size: 0.8rem;
        }

        .item-status-form {
            margin-left: 3.5rem;
            padding-top: 1rem;
            border-top: 1px dashed #d8dee8;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.8rem;
            color: #69707a;
            font-size: 0.875rem;
        }

        .total-row strong {
            color: #363d47;
            white-space: nowrap;
        }

        .grand-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .grand-total span:last-child {
            color: #0061f2;
            font-size: 1.35rem;
        }

        .cash-change-box {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.8rem;
            border-radius: 0.5rem;
            background: #f8f9fa;
            font-size: 0.875rem;
        }

        .paid-icon {
            width: 4rem;
            height: 4rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
            background: #00ac69;
        }

        @media (max-width: 767.98px) {
            .order-hero {
                align-items: flex-start;
                flex-direction: column;
            }

            .order-hero-total {
                font-size: 1.4rem;
            }

            .package-components,
            .item-status-form {
                margin-left: 0;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusInput = document.getElementById('status');
            const cancellationField = document.getElementById('cancellationField');
            const cancellationInput = document.getElementById('cancellation_reason');

            function handleCancellationField() {
                if (!statusInput || !cancellationField || !cancellationInput) {
                    return;
                }

                const cancelled = statusInput.value === 'cancelled';

                cancellationField.classList.toggle('d-none', !cancelled);
                cancellationInput.required = cancelled;
            }

            if (statusInput) {
                statusInput.addEventListener('change', handleCancellationField);
                handleCancellationField();
            }

            const paidAmountInput = document.getElementById('paid_amount');
            const changeAmount = document.getElementById('changeAmount');
            const grandTotal = Number(@json((float) $order->grand_total));

            function calculateChange() {
                if (!paidAmountInput || !changeAmount) {
                    return;
                }

                const paidAmount = Number(paidAmountInput.value) || 0;
                const change = Math.max(0, paidAmount - grandTotal);

                changeAmount.textContent = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                }).format(change);
            }

            if (paidAmountInput) {
                paidAmountInput.addEventListener('input', calculateChange);
                calculateChange();
            }

            document.querySelectorAll('.item-status-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Perbarui status item?',
                        text: 'Status item pesanan akan diperbarui.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, perbarui',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#0061f2',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            const statusForm = document.getElementById('statusForm');

            if (statusForm) {
                statusForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const selectedStatus = statusInput.value;
                    const isCancelled = selectedStatus === 'cancelled';

                    Swal.fire({
                        title: isCancelled ?
                            'Batalkan pesanan?' :
                            'Perbarui status pesanan?',
                        text: isCancelled ?
                            'Pesanan yang dibatalkan tidak dapat dikembalikan.' :
                            'Status pesanan akan diperbarui.',
                        icon: isCancelled ? 'warning' : 'question',
                        showCancelButton: true,
                        confirmButtonText: isCancelled ?
                            'Ya, batalkan' :
                            'Ya, perbarui',
                        cancelButtonText: 'Kembali',
                        confirmButtonColor: isCancelled ? '#dc3545' : '#0061f2',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            statusForm.submit();
                        }
                    });
                });
            }

            const paymentForm = document.getElementById('paymentForm');

            if (paymentForm) {
                paymentForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const paidAmount = Number(paidAmountInput.value) || 0;

                    if (paidAmount < grandTotal) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nominal Pembayaran Kurang',
                            text: 'Nominal pembayaran tidak boleh kurang dari total tagihan.',
                            confirmButtonColor: '#0061f2',
                        });

                        return;
                    }

                    Swal.fire({
                        title: 'Simpan pembayaran?',
                        text: 'Pastikan metode dan nominal pembayaran sudah benar.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, simpan',
                        cancelButtonText: 'Periksa kembali',
                        confirmButtonColor: '#00ac69',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            paymentForm.submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush
