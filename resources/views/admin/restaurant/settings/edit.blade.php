@extends('admin.layouts.main')

@section('page_title', 'Pengaturan Restoran')
@section('meta_description', 'Kelola biaya, pajak, pembayaran, dan penomoran restoran.')
@section('header_title', 'Pengaturan Restoran')
@section('header_subtitle', 'Kelola konfigurasi operasional dan perhitungan transaksi restoran.')
@section('header_icon', 'settings')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item active">
        Pengaturan Restoran
    </li>
@endsection

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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <strong>Pengaturan belum dapat disimpan.</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="restaurantSettingForm" action="{{ route('management.restaurant-settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="setting-icon me-3">
                                <i data-feather="home"></i>
                            </div>

                            <div>
                                <div class="fw-bold">Informasi Restoran</div>
                                <small class="text-muted">
                                    Informasi utama yang digunakan pada transaksi.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row gx-3">
                            <div class="col-12 mb-3">
                                <label for="restaurant_name" class="form-label">
                                    Nama Restoran
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="restaurant_name" id="restaurant_name"
                                    class="form-control @error('restaurant_name') is-invalid @enderror"
                                    value="{{ old('restaurant_name', $settings->restaurant_name) }}" maxlength="150"
                                    required>

                                @error('restaurant_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="currency_code" class="form-label">
                                    Kode Mata Uang
                                </label>

                                <input type="text" name="currency_code" id="currency_code"
                                    class="form-control text-uppercase @error('currency_code') is-invalid @enderror"
                                    value="{{ old('currency_code', $settings->currency_code) }}" maxlength="10"
                                    placeholder="IDR" required>

                                @error('currency_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="currency_symbol" class="form-label">
                                    Simbol Mata Uang
                                </label>

                                <input type="text" name="currency_symbol" id="currency_symbol"
                                    class="form-control @error('currency_symbol') is-invalid @enderror"
                                    value="{{ old('currency_symbol', $settings->currency_symbol) }}" maxlength="10"
                                    placeholder="Rp" required>

                                @error('currency_symbol')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="setting-icon me-3">
                                <i data-feather="percent"></i>
                            </div>

                            <div>
                                <div class="fw-bold">Biaya dan Pajak</div>
                                <small class="text-muted">
                                    Digunakan untuk menghitung total pesanan.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="setting-switch">
                            <div>
                                <label for="is_service_charge_active" class="fw-semibold">
                                    Aktifkan Service Charge
                                </label>

                                <div class="small text-muted">
                                    Tambahkan biaya pelayanan pada pesanan.
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_service_charge_active" id="is_service_charge_active"
                                    class="form-check-input" value="1" @checked(old('is_service_charge_active', $settings->is_service_charge_active))>
                            </div>
                        </div>

                        <div id="serviceChargeField" class="setting-percentage-field">
                            <label for="service_charge_percentage" class="form-label">
                                Persentase Service Charge
                            </label>

                            <div class="input-group">
                                <input type="number" name="service_charge_percentage" id="service_charge_percentage"
                                    class="form-control @error('service_charge_percentage') is-invalid @enderror"
                                    value="{{ old('service_charge_percentage', $settings->service_charge_percentage) }}"
                                    min="0" max="100" step="0.01" required>

                                <span class="input-group-text">%</span>
                            </div>

                            @error('service_charge_percentage')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr>

                        <div class="setting-switch">
                            <div>
                                <label for="is_tax_active" class="fw-semibold">
                                    Aktifkan Pajak
                                </label>

                                <div class="small text-muted">
                                    Tambahkan pajak setelah service charge.
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_tax_active" id="is_tax_active" class="form-check-input"
                                    value="1" @checked(old('is_tax_active', $settings->is_tax_active))>
                            </div>
                        </div>

                        <div id="taxField" class="setting-percentage-field">
                            <label for="tax_percentage" class="form-label">
                                Persentase Pajak
                            </label>

                            <div class="input-group">
                                <input type="number" name="tax_percentage" id="tax_percentage"
                                    class="form-control @error('tax_percentage') is-invalid @enderror"
                                    value="{{ old('tax_percentage', $settings->tax_percentage) }}"
                                    min="0" max="100" step="0.01" required>

                                <span class="input-group-text">%</span>
                            </div>

                            @error('tax_percentage')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr>

                        <div>
                            <label for="maximum_discount_percentage" class="form-label">
                                Maksimal Diskon Pesanan
                            </label>

                            <div class="input-group">
                                <input type="number" name="maximum_discount_percentage" id="maximum_discount_percentage"
                                    class="form-control @error('maximum_discount_percentage') is-invalid @enderror"
                                    value="{{ old('maximum_discount_percentage', $settings->maximum_discount_percentage) }}"
                                    min="0" max="100" step="0.01" required>

                                <span class="input-group-text">%</span>
                            </div>

                            <div class="form-text">
                                Membatasi diskon manual yang dapat diberikan kasir.
                            </div>

                            @error('maximum_discount_percentage')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="setting-icon me-3">
                                <i data-feather="credit-card"></i>
                            </div>

                            <div>
                                <div class="fw-bold">Pembayaran Nanti</div>
                                <small class="text-muted">
                                    Tentukan jenis pesanan yang dapat dibayar setelah pelayanan.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="setting-switch">
                            <div>
                                <div class="fw-semibold">Dine In</div>
                                <div class="small text-muted">
                                    Pelanggan makan langsung di restoran.
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input type="checkbox" name="allow_pay_later_dine_in" id="allow_pay_later_dine_in"
                                    class="form-check-input" value="1" @checked(old('allow_pay_later_dine_in', $settings->allow_pay_later_dine_in))>
                            </div>
                        </div>

                        <hr>

                        <div class="setting-switch">
                            <div>
                                <div class="fw-semibold">Delivery</div>
                                <div class="small text-muted">
                                    Pesanan dikirim ke alamat pelanggan.
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input type="checkbox" name="allow_pay_later_delivery" id="allow_pay_later_delivery"
                                    class="form-check-input" value="1" @checked(old('allow_pay_later_delivery', $settings->allow_pay_later_delivery))>
                            </div>
                        </div>

                        <hr>

                        <div class="setting-switch">
                            <div>
                                <div class="fw-semibold">Room Service</div>
                                <div class="small text-muted">
                                    Pesanan dikirim ke kamar hotel.
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input type="checkbox" name="allow_pay_later_room_service"
                                    id="allow_pay_later_room_service" class="form-check-input" value="1"
                                    @checked(old('allow_pay_later_room_service', $settings->allow_pay_later_room_service))>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i data-feather="hash" class="me-2"></i>
                        Prefix Nomor Transaksi
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="order_number_prefix" class="form-label">
                                Prefix Pesanan
                            </label>

                            <input type="text" name="order_number_prefix" id="order_number_prefix"
                                class="form-control text-uppercase"
                                value="{{ old('order_number_prefix', $settings->order_number_prefix) }}"
                                maxlength="20" required>
                        </div>

                        <div class="mb-3">
                            <label for="payment_number_prefix" class="form-label">
                                Prefix Pembayaran
                            </label>

                            <input type="text" name="payment_number_prefix" id="payment_number_prefix"
                                class="form-control text-uppercase"
                                value="{{ old('payment_number_prefix', $settings->payment_number_prefix) }}"
                                maxlength="20" required>
                        </div>

                        <div class="mb-0">
                            <label for="reservation_number_prefix" class="form-label">
                                Prefix Reservasi
                            </label>

                            <input type="text" name="reservation_number_prefix" id="reservation_number_prefix"
                                class="form-control text-uppercase"
                                value="{{ old('reservation_number_prefix', $settings->reservation_number_prefix) }}"
                                maxlength="20" required>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i data-feather="clock" class="me-2"></i>
                        Durasi Default
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="default_reservation_duration" class="form-label">
                                Durasi Reservasi
                            </label>

                            <div class="input-group">
                                <input type="number" name="default_reservation_duration"
                                    id="default_reservation_duration" class="form-control"
                                    value="{{ old('default_reservation_duration', $settings->default_reservation_duration) }}"
                                    min="15" max="1440" required>

                                <span class="input-group-text">menit</span>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="default_preparation_time" class="form-label">
                                Waktu Persiapan Pesanan
                            </label>

                            <div class="input-group">
                                <input type="number" name="default_preparation_time" id="default_preparation_time"
                                    class="form-control"
                                    value="{{ old('default_preparation_time', $settings->default_preparation_time) }}"
                                    min="1" max="1440" required>

                                <span class="input-group-text">menit</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4 setting-save-card">
                    <div class="card-body">
                        <div class="alert alert-info small">
                            Perubahan biaya dan pajak hanya digunakan untuk
                            pesanan baru. Pesanan lama tetap menggunakan nilai
                            snapshot ketika pesanan dibuat.
                        </div>

                        <button type="submit" id="saveSettingButton" class="btn btn-primary w-100">
                            <i data-feather="save" class="me-2"></i>
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style')
    <style>
        .setting-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 0.65rem;
            color: #0061f2;
            background: rgba(0, 97, 242, 0.1);
        }

        .setting-icon svg {
            width: 1.15rem;
            height: 1.15rem;
        }

        .setting-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .setting-percentage-field {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 0.65rem;
            background: #f8f9fa;
        }

        .form-switch .form-check-input {
            width: 2.75rem;
            height: 1.4rem;
            cursor: pointer;
        }

        .setting-save-card {
            position: sticky;
            top: 6rem;
        }

        @media (max-width: 1199.98px) {
            .setting-save-card {
                position: static;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById(
                'restaurantSettingForm'
            );

            const serviceSwitch = document.getElementById(
                'is_service_charge_active'
            );

            const serviceField = document.getElementById(
                'serviceChargeField'
            );

            const serviceInput = document.getElementById(
                'service_charge_percentage'
            );

            const taxSwitch = document.getElementById(
                'is_tax_active'
            );

            const taxField = document.getElementById('taxField');
            const taxInput = document.getElementById('tax_percentage');

            function updateConditionalFields() {
                serviceField.classList.toggle(
                    'setting-disabled',
                    !serviceSwitch.checked
                );

                taxField.classList.toggle(
                    'setting-disabled',
                    !taxSwitch.checked
                );

                serviceInput.readOnly = !serviceSwitch.checked;
                taxInput.readOnly = !taxSwitch.checked;
            }

            serviceSwitch.addEventListener(
                'change',
                updateConditionalFields
            );

            taxSwitch.addEventListener(
                'change',
                updateConditionalFields
            );

            form.addEventListener('submit', function() {
                const button = document.getElementById(
                    'saveSettingButton'
                );

                button.disabled = true;
                button.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Menyimpan...
                `;
            });

            updateConditionalFields();
        });
    </script>

    <style>
        .setting-disabled {
            opacity: 0.55;
        }
    </style>
@endpush
