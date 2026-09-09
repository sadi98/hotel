@extends('admin.layouts.main')

@section('page_title', 'Buat Pesanan Baru')
@section('meta_description', 'Buat pesanan restoran langsung dari halaman manajemen.')
@section('header_title', 'Buat Pesanan')
@section('header_subtitle', 'Buat pesanan langsung untuk pelanggan dine-in, delivery, atau room service.')
@section('header_icon', 'shopping-cart')

@section('header_action')
    <a href="{{ route('management.orders.index') }}" class="btn btn-light">
        <i data-feather="arrow-left" class="me-2"></i>
        Kembali
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('management.orders.index') }}">Pesanan</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        Buat Pesanan
    </li>
@endsection

@php
    $oldItems = old('items', [
        [
            'item_type' => 'menu_item',
            'item_id' => '',
            'quantity' => 1,
            'note' => '',
        ],
    ]);

    $menuItemOptions = $menuItems
        ->map(function ($menuItem) {
            return [
                'id' => $menuItem->id,
                'type' => 'menu_item',
                'sku' => $menuItem->sku,
                'name' => $menuItem->name,
                'price' => (float) $menuItem->price,
                'minimum_order' => 1,
                'is_available' => (bool) $menuItem->is_available,
            ];
        })
        ->values();

    $menuPackageOptions = $menuPackages
        ->map(function ($menuPackage) {
            return [
                'id' => $menuPackage->id,
                'type' => 'package',
                'sku' => $menuPackage->sku,
                'name' => $menuPackage->name,
                'price' => (float) $menuPackage->package_price,
                'minimum_order' => (int) $menuPackage->minimum_order,
                'serving_count' => (int) $menuPackage->serving_count,
                'is_available' => (bool) $menuPackage->is_available,
            ];
        })
        ->values();

    $paymentTiming = old('payment_timing', 'pay_now');
@endphp

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i data-feather="alert-circle"></i>
                </div>

                <div>
                    <strong>Pesanan belum dapat disimpan.</strong>

                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="orderForm" action="{{ route('management.orders.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="row">
            <div class="col-xl-8">
                {{-- Informasi pelanggan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="order-section-icon me-3">
                                <i data-feather="user"></i>
                            </div>

                            <div>
                                <div class="fw-bold">Informasi Pelanggan</div>
                                <small class="text-muted">
                                    Masukkan identitas pelanggan yang melakukan pemesanan.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row gx-3">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">
                                    Nama Pelanggan
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="customer_name" id="customer_name"
                                    class="form-control @error('customer_name') is-invalid @enderror"
                                    value="{{ old('customer_name') }}" maxlength="150" placeholder="Contoh: Budi Santoso"
                                    required>

                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">
                                    Nomor Telepon
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="customer_phone" id="customer_phone"
                                    class="form-control @error('customer_phone') is-invalid @enderror"
                                    value="{{ old('customer_phone') }}" maxlength="30" placeholder="Contoh: 081234567890"
                                    required>

                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="customer_email" class="form-label">
                                    Email
                                </label>

                                <input type="email" name="customer_email" id="customer_email"
                                    class="form-control @error('customer_email') is-invalid @enderror"
                                    value="{{ old('customer_email') }}" maxlength="255"
                                    placeholder="Contoh: pelanggan@email.com">

                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="guest_count" class="form-label">
                                    Jumlah Tamu
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" name="guest_count" id="guest_count"
                                    class="form-control @error('guest_count') is-invalid @enderror"
                                    value="{{ old('guest_count', 1) }}" min="1" max="100" required>

                                @error('guest_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi pesanan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="order-section-icon me-3">
                                <i data-feather="clipboard"></i>
                            </div>

                            <div>
                                <div class="fw-bold">Informasi Pesanan</div>
                                <small class="text-muted">
                                    Tentukan jenis pesanan, lokasi, dan waktu pelayanan.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row gx-3">
                            <div class="col-md-6 mb-3">
                                <label for="order_type" class="form-label">
                                    Jenis Pesanan
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="order_type" id="order_type"
                                    class="form-select @error('order_type') is-invalid @enderror" required>
                                    <option value="dine_in" @selected(old('order_type', 'dine_in') === 'dine_in')>
                                        Dine In
                                    </option>

                                    <option value="delivery" @selected(old('order_type') === 'delivery')>
                                        Delivery
                                    </option>

                                    <option value="room_service" @selected(old('order_type') === 'room_service')>
                                        Room Service
                                    </option>
                                </select>

                                @error('order_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="payment_timing" class="form-label">
                                    Waktu Pembayaran
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="payment_timing" id="payment_timing"
                                    class="form-select @error('payment_timing') is-invalid @enderror" required>
                                    <option value="pay_now" @selected($paymentTiming === 'pay_now')>
                                        Bayar Sekarang
                                    </option>

                                    <option value="pay_later" @selected($paymentTiming === 'pay_later')>
                                        Bayar Nanti
                                    </option>
                                </select>

                                <div id="paymentTimingHelp" class="form-text"></div>

                                @error('payment_timing')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="tableField" class="col-md-6 mb-3 order-destination-field">
                                <label for="restaurant_table_id" class="form-label">
                                    Meja Restoran
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="restaurant_table_id" id="restaurant_table_id"
                                    class="form-select @error('restaurant_table_id') is-invalid @enderror">
                                    <option value="">Pilih meja restoran</option>

                                    @foreach ($tables as $table)
                                        <option value="{{ $table->id }}" @selected((string) old('restaurant_table_id') === (string) $table->id)>
                                            {{ $table->table_number }}

                                            @if ($table->name)
                                                - {{ $table->name }}
                                            @endif

                                            @if ($table->area)
                                                ({{ $table->area }})
                                            @endif

                                            - Kapasitas {{ $table->capacity }} orang
                                        </option>
                                    @endforeach
                                </select>

                                @error('restaurant_table_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="roomField" class="col-md-6 mb-3 order-destination-field d-none">
                                <label for="room_number" class="form-label">
                                    Nomor Kamar
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="room_number" id="room_number"
                                    class="form-control @error('room_number') is-invalid @enderror"
                                    value="{{ old('room_number') }}" maxlength="30" placeholder="Contoh: 1208">

                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="deliveryField" class="col-12 mb-3 order-destination-field d-none">
                                <label for="delivery_address" class="form-label">
                                    Alamat Pengantaran
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea name="delivery_address" id="delivery_address"
                                    class="form-control @error('delivery_address') is-invalid @enderror" rows="3" maxlength="2000"
                                    placeholder="Masukkan alamat pengantaran secara lengkap">{{ old('delivery_address') }}</textarea>

                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="scheduled_at" class="form-label">
                                    Jadwal Penyajian/Pengantaran
                                </label>

                                <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                    class="form-control @error('scheduled_at') is-invalid @enderror"
                                    value="{{ old('scheduled_at') }}">

                                <div class="form-text">
                                    Kosongkan jika pesanan diproses sekarang.
                                </div>

                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Item pesanan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center">
                                <div class="order-section-icon me-3">
                                    <i data-feather="coffee"></i>
                                </div>

                                <div>
                                    <div class="fw-bold">Menu Pesanan</div>
                                    <small class="text-muted">
                                        Pilih menu satuan atau paket restoran.
                                    </small>
                                </div>
                            </div>

                            <button type="button" id="addOrderItem" class="btn btn-primary btn-sm">
                                <i data-feather="plus" class="me-1"></i>
                                Tambah Menu
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        @error('items')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror

                        <div id="orderItemsContainer"></div>

                        <div id="emptyItemsMessage" class="order-empty-item text-center d-none">
                            <div class="order-empty-icon">
                                <i data-feather="shopping-bag"></i>
                            </div>

                            <div class="fw-semibold mt-3">
                                Belum ada menu dipilih
                            </div>

                            <div class="text-muted small mt-1">
                                Klik tombol Tambah Menu untuk menambahkan pesanan.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="order-section-icon me-3">
                                <i data-feather="message-square"></i>
                            </div>

                            <div>
                                <div class="fw-bold">Catatan Pesanan</div>
                                <small class="text-muted">
                                    Tambahkan permintaan pelanggan atau informasi internal staff.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row gx-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="customer_note" class="form-label">
                                    Catatan Pelanggan
                                </label>

                                <textarea name="customer_note" id="customer_note" class="form-control @error('customer_note') is-invalid @enderror"
                                    rows="5" maxlength="3000" placeholder="Contoh: Jangan terlalu pedas, tanpa bawang...">{{ old('customer_note') }}</textarea>

                                @error('customer_note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="internal_note" class="form-label">
                                    Catatan Internal
                                </label>

                                <textarea name="internal_note" id="internal_note" class="form-control @error('internal_note') is-invalid @enderror"
                                    rows="5" maxlength="3000" placeholder="Catatan ini hanya dapat dilihat oleh admin dan staff.">{{ old('internal_note') }}</textarea>

                                @error('internal_note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="col-xl-4">
                <div class="card shadow-sm mb-4 order-summary-card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="order-section-icon me-3">
                                <i data-feather="file-text"></i>
                            </div>

                            <div>
                                <div class="fw-bold">Ringkasan Pesanan</div>
                                <small class="text-muted">
                                    Perhitungan sementara pesanan.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="summaryItems" class="mb-4">
                            <div class="text-center text-muted py-3">
                                Belum ada menu dipilih.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="discount_amount" class="form-label">
                                Potongan Harga
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    {{ $settings->currency_symbol ?: 'Rp' }}
                                </span>

                                <input type="number" name="discount_amount" id="discount_amount"
                                    class="form-control @error('discount_amount') is-invalid @enderror"
                                    value="{{ old('discount_amount', 0) }}" min="0" step="1">
                            </div>

                            <div class="form-text">
                                Maksimal {{ number_format((float) $settings->maximum_discount_percentage, 2, ',', '.') }}%
                                dari subtotal.
                            </div>

                            @error('discount_amount')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr>

                        <div class="order-total-row">
                            <span>Subtotal</span>
                            <span id="summarySubtotal">Rp0</span>
                        </div>

                        <div class="order-total-row">
                            <span>Diskon</span>
                            <span id="summaryDiscount" class="text-danger">- Rp0</span>
                        </div>

                        <div class="order-total-row">
                            <span>
                                Service Charge
                                (<span id="servicePercentage">
                                    {{ number_format((float) $settings->service_charge_percentage, 2, ',', '.') }}
                                </span>%)
                            </span>

                            <span id="summaryService">Rp0</span>
                        </div>

                        <div class="order-total-row">
                            <span>
                                Pajak
                                (<span id="taxPercentage">
                                    {{ number_format((float) $settings->tax_percentage, 2, ',', '.') }}
                                </span>%)
                            </span>

                            <span id="summaryTax">Rp0</span>
                        </div>

                        <hr>

                        <div class="order-grand-total">
                            <span>Total</span>
                            <span id="summaryGrandTotal">Rp0</span>
                        </div>

                        <div class="alert alert-info small mt-4 mb-0">
                            <div class="d-flex">
                                <i data-feather="info" class="me-2 flex-shrink-0"></i>

                                <div>
                                    Nilai ini merupakan estimasi. Perhitungan akhir tetap dilakukan
                                    kembali oleh sistem ketika pesanan disimpan.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" id="submitOrderButton" class="btn btn-primary w-100">
                            <span id="submitOrderIcon">
                                <i data-feather="save" class="me-2"></i>
                            </span>

                            <span id="submitOrderText">
                                Simpan Pesanan
                            </span>

                            <span id="submitOrderSpinner" class="spinner-border spinner-border-sm ms-2 d-none"
                                role="status" aria-hidden="true"></span>
                        </button>

                        <a href="{{ route('management.orders.index') }}" class="btn btn-light w-100 mt-2">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style')
    <style>
        .order-section-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.65rem;
            color: #0061f2;
            background: rgba(0, 97, 242, 0.1);
            flex-shrink: 0;
        }

        .order-section-icon svg {
            width: 1.15rem;
            height: 1.15rem;
        }

        .order-item-row {
            position: relative;
            border: 1px solid #e0e5ec;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .order-item-row:hover {
            border-color: rgba(0, 97, 242, 0.35);
            box-shadow: 0 0.125rem 0.35rem rgba(33, 40, 50, 0.08);
        }

        .order-item-number {
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #0061f2;
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .order-item-price {
            color: #0061f2;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-remove-item {
            width: 2.35rem;
            height: 2.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .order-remove-item svg {
            width: 1rem;
            height: 1rem;
        }

        .order-empty-item {
            border: 2px dashed #d8dee8;
            border-radius: 0.75rem;
            padding: 2.5rem 1rem;
            background: #f8fafc;
        }

        .order-empty-icon {
            width: 3.5rem;
            height: 3.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #69707a;
            background: #e9ecef;
        }

        .order-summary-card {
            position: sticky;
            top: 6rem;
        }

        .order-summary-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #d8dee8;
        }

        .order-summary-item:last-child {
            border-bottom: 0;
        }

        .order-summary-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #363d47;
        }

        .order-summary-meta {
            margin-top: 0.15rem;
            color: #69707a;
            font-size: 0.75rem;
        }

        .order-summary-price {
            color: #363d47;
            font-size: 0.875rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-total-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.75rem;
            color: #69707a;
            font-size: 0.875rem;
        }

        .order-total-row span:last-child {
            color: #363d47;
            font-weight: 600;
            text-align: right;
            white-space: nowrap;
        }

        .order-grand-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            color: #1f2d3d;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .order-grand-total span:last-child {
            color: #0061f2;
            font-size: 1.35rem;
            text-align: right;
        }

        @media (max-width: 1199.98px) {
            .order-summary-card {
                position: static;
            }
        }

        @media (max-width: 575.98px) {
            .order-item-row {
                padding: 0.85rem;
            }

            .order-item-header {
                align-items: flex-start !important;
            }

            .order-grand-total {
                font-size: 1rem;
            }

            .order-grand-total span:last-child {
                font-size: 1.15rem;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = @json($menuItemOptions);
            const menuPackages = @json($menuPackageOptions);
            const initialItems = @json($oldItems);

            const currencyCode = @json($settings->currency_code ?: 'IDR');
            const currencySymbol = @json($settings->currency_symbol ?: 'Rp');

            const serviceChargeActive = @json((bool) $settings->is_service_charge_active);
            const serviceChargePercentage = Number(
                @json((float) $settings->service_charge_percentage)
            );

            const taxActive = @json((bool) $settings->is_tax_active);
            const taxPercentage = Number(
                @json((float) $settings->tax_percentage)
            );

            const maximumDiscountPercentage = Number(
                @json((float) $settings->maximum_discount_percentage)
            );

            const payLaterAvailability = {
                dine_in: @json($settings->allowsPayLater('dine_in')),
                delivery: @json($settings->allowsPayLater('delivery')),
                room_service: @json($settings->allowsPayLater('room_service')),
            };

            const orderForm = document.getElementById('orderForm');
            const orderTypeInput = document.getElementById('order_type');
            const paymentTimingInput = document.getElementById('payment_timing');
            const paymentTimingHelp = document.getElementById('paymentTimingHelp');

            const tableField = document.getElementById('tableField');
            const roomField = document.getElementById('roomField');
            const deliveryField = document.getElementById('deliveryField');

            const tableInput = document.getElementById('restaurant_table_id');
            const roomInput = document.getElementById('room_number');
            const deliveryInput = document.getElementById('delivery_address');

            const itemsContainer = document.getElementById('orderItemsContainer');
            const emptyItemsMessage = document.getElementById('emptyItemsMessage');
            const addItemButton = document.getElementById('addOrderItem');
            const discountInput = document.getElementById('discount_amount');

            const summaryItems = document.getElementById('summaryItems');
            const summarySubtotal = document.getElementById('summarySubtotal');
            const summaryDiscount = document.getElementById('summaryDiscount');
            const summaryService = document.getElementById('summaryService');
            const summaryTax = document.getElementById('summaryTax');
            const summaryGrandTotal = document.getElementById('summaryGrandTotal');

            const submitButton = document.getElementById('submitOrderButton');
            const submitIcon = document.getElementById('submitOrderIcon');
            const submitText = document.getElementById('submitOrderText');
            const submitSpinner = document.getElementById('submitOrderSpinner');

            let orderItems = [];

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function formatCurrency(value) {
                const number = Number(value) || 0;

                try {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: currencyCode,
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    }).format(number);
                } catch (error) {
                    return currencySymbol + new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    }).format(number);
                }
            }

            function getProduct(type, id) {
                const productId = Number(id);

                if (type === 'package') {
                    return menuPackages.find(function(item) {
                        return Number(item.id) === productId;
                    }) || null;
                }

                return menuItems.find(function(item) {
                    return Number(item.id) === productId;
                }) || null;
            }

            function buildProductOptions(selectedType, selectedId) {
                let html = '<option value="">Pilih menu atau paket</option>';

                html += '<optgroup label="Menu Satuan">';

                menuItems.forEach(function(item) {
                    const value = 'menu_item:' + item.id;
                    const currentValue = selectedType + ':' + selectedId;
                    const selected = value === currentValue ? ' selected' : '';

                    html += `
                        <option value="${value}"${selected}>
                            ${escapeHtml(item.sku)} - ${escapeHtml(item.name)}
                            (${escapeHtml(formatCurrency(item.price))})
                        </option>
                    `;
                });

                html += '</optgroup>';
                html += '<optgroup label="Paket Restoran">';

                menuPackages.forEach(function(item) {
                    const value = 'package:' + item.id;
                    const currentValue = selectedType + ':' + selectedId;
                    const selected = value === currentValue ? ' selected' : '';

                    html += `
                        <option value="${value}"${selected}>
                            ${escapeHtml(item.sku)} - ${escapeHtml(item.name)}
                            (${escapeHtml(formatCurrency(item.price))})
                        </option>
                    `;
                });

                html += '</optgroup>';

                return html;
            }

            function normalizeInitialItems() {
                if (!Array.isArray(initialItems)) {
                    return;
                }

                initialItems.forEach(function(item) {
                    orderItems.push({
                        item_type: item.item_type || 'menu_item',
                        item_id: item.item_id || '',
                        quantity: Math.max(1, Number(item.quantity) || 1),
                        note: item.note || '',
                    });
                });
            }

            function addOrderItem(item) {
                orderItems.push({
                    item_type: item?.item_type || 'menu_item',
                    item_id: item?.item_id || '',
                    quantity: Math.max(1, Number(item?.quantity) || 1),
                    note: item?.note || '',
                });

                renderOrderItems();
            }

            function removeOrderItem(index) {
                orderItems.splice(index, 1);
                renderOrderItems();
            }

            function renderOrderItems() {
                itemsContainer.innerHTML = '';

                emptyItemsMessage.classList.toggle(
                    'd-none',
                    orderItems.length > 0
                );

                orderItems.forEach(function(item, index) {
                    const product = getProduct(item.item_type, item.item_id);
                    const minimumOrder = product ?
                        Math.max(1, Number(product.minimum_order) || 1) :
                        1;

                    if (Number(item.quantity) < minimumOrder) {
                        item.quantity = minimumOrder;
                    }

                    const row = document.createElement('div');
                    row.className = 'order-item-row';
                    row.dataset.index = index;

                    row.innerHTML = `
                        <div class="d-flex justify-content-between gap-3 mb-3 order-item-header">
                            <div class="d-flex align-items-center gap-2">
                                <span class="order-item-number">${index + 1}</span>

                                <div>
                                    <div class="fw-semibold">
                                        Item Pesanan
                                    </div>

                                    <div class="small text-muted">
                                        ${product
                                            ? escapeHtml(product.sku + ' - ' + product.name)
                                            : 'Silakan pilih menu'}
                                    </div>
                                </div>
                            </div>

                            <button
                                type="button"
                                class="btn btn-outline-danger order-remove-item"
                                data-action="remove"
                                title="Hapus item"
                            >
                                <i data-feather="trash-2"></i>
                            </button>
                        </div>

                        <div class="row gx-3">
                            <div class="col-lg-7 mb-3">
                                <label class="form-label">
                                    Menu atau Paket
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    class="form-select product-selector"
                                    data-action="select-product"
                                    required
                                >
                                    ${buildProductOptions(item.item_type, item.item_id)}
                                </select>

                                <input
                                    type="hidden"
                                    name="items[${index}][item_type]"
                                    value="${escapeHtml(item.item_type)}"
                                    class="item-type-input"
                                >

                                <input
                                    type="hidden"
                                    name="items[${index}][item_id]"
                                    value="${escapeHtml(item.item_id)}"
                                    class="item-id-input"
                                >
                            </div>

                            <div class="col-sm-5 col-lg-2 mb-3">
                                <label class="form-label">
                                    Jumlah
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="items[${index}][quantity]"
                                    value="${escapeHtml(item.quantity)}"
                                    min="${minimumOrder}"
                                    max="999"
                                    class="form-control quantity-input"
                                    data-action="quantity"
                                    required
                                >

                                ${product && Number(product.minimum_order) > 1
                                    ? `
                                            <div class="form-text">
                                                Minimal ${escapeHtml(product.minimum_order)}
                                            </div>
                                        `
                                    : ''}
                            </div>

                            <div class="col-sm-7 col-lg-3 mb-3">
                                <label class="form-label">
                                    Harga
                                </label>

                                <div class="form-control bg-light order-item-price">
                                    ${product
                                        ? escapeHtml(formatCurrency(product.price))
                                        : '-'}
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Catatan Item
                                </label>

                                <input
                                    type="text"
                                    name="items[${index}][note]"
                                    value="${escapeHtml(item.note)}"
                                    maxlength="1000"
                                    class="form-control item-note-input"
                                    data-action="note"
                                    placeholder="Contoh: Tidak pedas, tanpa es, tingkat kematangan medium..."
                                >
                            </div>
                        </div>
                    `;

                    itemsContainer.appendChild(row);
                });

                if (window.feather) {
                    feather.replace();
                }

                calculateSummary();
            }

            function calculateSummary() {
                let subtotal = 0;
                const summaryRows = [];

                orderItems.forEach(function(item) {
                    const product = getProduct(item.item_type, item.item_id);

                    if (!product) {
                        return;
                    }

                    const quantity = Math.max(1, Number(item.quantity) || 1);
                    const lineTotal = Number(product.price) * quantity;

                    subtotal += lineTotal;

                    summaryRows.push({
                        name: product.name,
                        type: item.item_type === 'package' ?
                            'Paket' :
                            'Menu',
                        quantity: quantity,
                        total: lineTotal,
                    });
                });

                let discount = Math.max(
                    0,
                    Number(discountInput.value) || 0
                );

                const maximumDiscount = subtotal *
                    (maximumDiscountPercentage / 100);

                if (maximumDiscountPercentage >= 0 && discount > maximumDiscount) {
                    discount = maximumDiscount;
                    discountInput.value = Math.floor(maximumDiscount);

                    Swal.fire({
                        icon: 'warning',
                        title: 'Diskon Melebihi Batas',
                        text: 'Diskon telah disesuaikan dengan batas maksimal ' +
                            maximumDiscountPercentage + '% dari subtotal.',
                        confirmButtonColor: '#0061f2',
                    });
                }

                const subtotalAfterDiscount = Math.max(
                    0,
                    subtotal - discount
                );

                const serviceCharge = serviceChargeActive ?
                    subtotalAfterDiscount * (serviceChargePercentage / 100) :
                    0;

                const taxableAmount = subtotalAfterDiscount + serviceCharge;

                const tax = taxActive ?
                    taxableAmount * (taxPercentage / 100) :
                    0;

                const grandTotal = taxableAmount + tax;

                if (summaryRows.length === 0) {
                    summaryItems.innerHTML = `
                        <div class="text-center text-muted py-3">
                            Belum ada menu dipilih.
                        </div>
                    `;
                } else {
                    summaryItems.innerHTML = summaryRows.map(function(row) {
                        return `
                            <div class="order-summary-item">
                                <div>
                                    <div class="order-summary-name">
                                        ${escapeHtml(row.name)}
                                    </div>

                                    <div class="order-summary-meta">
                                        ${escapeHtml(row.type)}
                                        &middot;
                                        ${escapeHtml(row.quantity)} item
                                    </div>
                                </div>

                                <div class="order-summary-price">
                                    ${escapeHtml(formatCurrency(row.total))}
                                </div>
                            </div>
                        `;
                    }).join('');
                }

                summarySubtotal.textContent = formatCurrency(subtotal);
                summaryDiscount.textContent = '- ' + formatCurrency(discount);
                summaryService.textContent = formatCurrency(serviceCharge);
                summaryTax.textContent = formatCurrency(tax);
                summaryGrandTotal.textContent = formatCurrency(grandTotal);
            }

            function updateDestinationFields() {
                const orderType = orderTypeInput.value;

                tableField.classList.toggle(
                    'd-none',
                    orderType !== 'dine_in'
                );

                roomField.classList.toggle(
                    'd-none',
                    orderType !== 'room_service'
                );

                deliveryField.classList.toggle(
                    'd-none',
                    orderType !== 'delivery'
                );

                tableInput.required = orderType === 'dine_in';
                roomInput.required = orderType === 'room_service';
                deliveryInput.required = orderType === 'delivery';

                if (orderType !== 'dine_in') {
                    tableInput.value = '';
                }

                if (orderType !== 'room_service') {
                    roomInput.value = '';
                }

                if (orderType !== 'delivery') {
                    deliveryInput.value = '';
                }

                updatePaymentTiming();
            }

            function updatePaymentTiming() {
                const orderType = orderTypeInput.value;
                const payLaterOption = paymentTimingInput.querySelector(
                    'option[value="pay_later"]'
                );

                const payLaterAllowed = Boolean(
                    payLaterAvailability[orderType]
                );

                payLaterOption.disabled = !payLaterAllowed;

                if (!payLaterAllowed && paymentTimingInput.value === 'pay_later') {
                    paymentTimingInput.value = 'pay_now';
                }

                if (payLaterAllowed) {
                    paymentTimingHelp.textContent =
                        'Pelanggan dapat melakukan pembayaran sekarang atau nanti.';
                    paymentTimingHelp.classList.remove('text-danger');
                } else {
                    paymentTimingHelp.textContent =
                        'Bayar nanti tidak tersedia untuk jenis pesanan ini.';
                    paymentTimingHelp.classList.add('text-danger');
                }
            }

            addItemButton.addEventListener('click', function() {
                addOrderItem();
            });

            itemsContainer.addEventListener('click', function(event) {
                const removeButton = event.target.closest(
                    '[data-action="remove"]'
                );

                if (!removeButton) {
                    return;
                }

                const row = removeButton.closest('.order-item-row');
                const index = Number(row.dataset.index);

                removeOrderItem(index);
            });

            itemsContainer.addEventListener('change', function(event) {
                const row = event.target.closest('.order-item-row');

                if (!row) {
                    return;
                }

                const index = Number(row.dataset.index);

                if (event.target.matches('[data-action="select-product"]')) {
                    const selectedValue = event.target.value;

                    if (!selectedValue) {
                        orderItems[index].item_type = 'menu_item';
                        orderItems[index].item_id = '';
                        renderOrderItems();
                        return;
                    }

                    const parts = selectedValue.split(':');
                    const type = parts[0];
                    const id = parts[1];
                    const product = getProduct(type, id);

                    orderItems[index].item_type = type;
                    orderItems[index].item_id = id;

                    if (product) {
                        const minimumOrder = Math.max(
                            1,
                            Number(product.minimum_order) || 1
                        );

                        if (Number(orderItems[index].quantity) < minimumOrder) {
                            orderItems[index].quantity = minimumOrder;
                        }
                    }

                    renderOrderItems();
                }

                if (event.target.matches('[data-action="quantity"]')) {
                    const product = getProduct(
                        orderItems[index].item_type,
                        orderItems[index].item_id
                    );

                    const minimumOrder = product ?
                        Math.max(1, Number(product.minimum_order) || 1) :
                        1;

                    orderItems[index].quantity = Math.max(
                        minimumOrder,
                        Number(event.target.value) || minimumOrder
                    );

                    event.target.value = orderItems[index].quantity;
                    calculateSummary();
                }

                if (event.target.matches('[data-action="note"]')) {
                    orderItems[index].note = event.target.value;
                }
            });

            itemsContainer.addEventListener('input', function(event) {
                const row = event.target.closest('.order-item-row');

                if (!row) {
                    return;
                }

                const index = Number(row.dataset.index);

                if (event.target.matches('[data-action="quantity"]')) {
                    orderItems[index].quantity = Math.max(
                        1,
                        Number(event.target.value) || 1
                    );

                    calculateSummary();
                }

                if (event.target.matches('[data-action="note"]')) {
                    orderItems[index].note = event.target.value;
                }
            });

            discountInput.addEventListener('input', calculateSummary);
            discountInput.addEventListener('change', calculateSummary);
            orderTypeInput.addEventListener('change', updateDestinationFields);

            orderForm.addEventListener('submit', function(event) {
                const validItems = orderItems.filter(function(item) {
                    return item.item_id &&
                        Number(item.quantity) > 0 &&
                        getProduct(item.item_type, item.item_id);
                });

                if (validItems.length === 0) {
                    event.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Menu Belum Dipilih',
                        text: 'Tambahkan minimal satu menu atau paket ke dalam pesanan.',
                        confirmButtonColor: '#0061f2',
                    });

                    return;
                }

                if (!orderForm.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    orderForm.classList.add('was-validated');

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Periksa kembali seluruh data pesanan yang wajib diisi.',
                        confirmButtonColor: '#0061f2',
                    });

                    return;
                }

                submitButton.disabled = true;
                submitIcon.classList.add('d-none');
                submitSpinner.classList.remove('d-none');
                submitText.textContent = 'Menyimpan Pesanan...';
            });

            normalizeInitialItems();

            if (orderItems.length === 0) {
                addOrderItem();
            } else {
                renderOrderItems();
            }

            updateDestinationFields();
            calculateSummary();

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Pesanan Belum Tersimpan',
                    text: 'Terdapat data yang belum benar. Silakan periksa kembali formulir pesanan.',
                    confirmButtonColor: '#0061f2',
                });
            @endif
        });
    </script>
@endpush
