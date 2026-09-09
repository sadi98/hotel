@extends('admin.layouts.main')

@section('page_title', 'Tambah Paket Menu || ' . config('app.name'))

@section('meta_description', 'Tambahkan paket atau bundling menu restoran.')

@section('header_title', 'Tambah Paket Menu')

@section('header_subtitle', 'Buat paket baru dan tentukan menu yang termasuk di dalamnya.')

@section('header_icon', 'plus-circle')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.menu-packages.index') }}">
        <i data-feather="arrow-left" class="me-1"></i>
        Kembali
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('management.menu-packages.index') }}">
            Paket Menu
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Tambah Paket
    </li>
@endsection

@push('style')
    <style>
        .package-form-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .package-form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .package-form-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .package-form-title svg {
            width: 18px;
            height: 18px;
        }

        .package-form-section {
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .package-form-section:last-of-type {
            padding-bottom: 0;
            margin-bottom: 0;
            border-bottom: 0;
        }

        .package-form-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.1rem;
        }

        .package-form-number {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 31px;
            height: 31px;
            margin-right: 0.7rem;
            color: #ffffff;
            background: linear-gradient(135deg, #0061f2, #6900c7);
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .package-form-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .package-form-section-text {
            margin: 0.1rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .package-required {
            color: #e81500;
        }

        .package-component {
            position: relative;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .package-component-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            color: #ffffff;
            background-color: #6900c7;
            border-radius: 50%;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .package-component-remove {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
        }

        .package-component-empty {
            padding: 2rem 1rem;
            margin-bottom: 1rem;
            color: #69707a;
            text-align: center;
            background-color: #f8f9fa;
            border: 1px dashed #b8c2cc;
            border-radius: 0.65rem;
        }

        .package-summary {
            padding: 1rem;
            background:
                linear-gradient(135deg,
                    rgba(0, 97, 242, 0.08),
                    rgba(105, 0, 199, 0.08));
            border: 1px solid rgba(0, 97, 242, 0.12);
            border-radius: 0.65rem;
        }

        .package-summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.55rem 0;
            border-bottom: 1px solid rgba(0, 97, 242, 0.1);
            font-size: 0.78rem;
        }

        .package-summary-item:last-child {
            border-bottom: 0;
        }

        .package-image-area {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px dashed #b8c2cc;
            border-radius: 0.65rem;
        }

        .package-image-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 220px;
            margin-bottom: 1rem;
            overflow: hidden;
            background-color: #eef2f6;
            border-radius: 0.6rem;
        }

        .package-image-preview {
            display: none;
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .package-image-placeholder {
            color: #69707a;
            text-align: center;
        }

        .package-form-status {
            height: 100%;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .package-form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e0e5ec;
        }

        @media (max-width: 767.98px) {
            .package-form-footer {
                flex-direction: column-reverse;
            }

            .package-form-footer .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $oldItems = old('items', []);
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <div class="d-flex align-items-start">
                <i data-feather="alert-triangle" class="me-2 mt-1"></i>

                <div>
                    <strong>Data paket belum valid.</strong>

                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button class="btn-close" type="button" data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <form id="packageCreateForm" action="{{ route('management.menu-packages.store') }}" method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="row">
            <div class="col-xl-8">
                <div class="card package-form-card mb-4">
                    <div class="package-form-header">
                        <h2 class="package-form-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Paket
                        </h2>

                        <span class="badge bg-primary-soft text-primary">
                            Paket baru
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="package-form-section">
                            <div class="package-form-heading">
                                <div class="package-form-number">1</div>

                                <div>
                                    <h3 class="package-form-section-title">
                                        Informasi Utama
                                    </h3>

                                    <p class="package-form-section-text">
                                        Masukkan SKU, nama, dan deskripsi paket.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-4 mb-3">
                                    <label class="small mb-1" for="sku">
                                        SKU Paket
                                        <span class="package-required">*</span>
                                    </label>

                                    <input class="form-control @error('sku') is-invalid @enderror" id="sku"
                                        name="sku" type="text" value="{{ old('sku') }}" maxlength="50"
                                        placeholder="PKT-001" required>

                                    @error('sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="small mb-1" for="name">
                                        Nama Paket
                                        <span class="package-required">*</span>
                                    </label>

                                    <input class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" type="text" value="{{ old('name') }}" maxlength="150"
                                        placeholder="Contoh: Romantic Dinner Package" required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <label class="small mb-1" for="description">
                                Deskripsi
                            </label>

                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" maxlength="3000" placeholder="Jelaskan isi dan keunggulan paket...">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="package-form-section">
                            <div class="package-form-heading">
                                <div class="package-form-number">2</div>

                                <div>
                                    <h3 class="package-form-section-title">
                                        Komponen Paket
                                    </h3>

                                    <p class="package-form-section-text">
                                        Pilih menu dan jumlahnya dalam satu paket.
                                    </p>
                                </div>
                            </div>

                            <div id="packageItemsContainer"></div>

                            <div class="package-component-empty" id="packageItemsEmpty">
                                <i data-feather="layers" class="mb-2"></i>

                                <div class="fw-bold small">
                                    Belum ada komponen
                                </div>

                                <div class="small">
                                    Klik tombol di bawah untuk menambahkan menu.
                                </div>
                            </div>

                            <button class="btn btn-outline-primary" id="addPackageItemButton" type="button">
                                <i data-feather="plus" class="me-1"></i>
                                Tambah Komponen
                            </button>

                            @error('items')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="package-form-section">
                            <div class="package-form-heading">
                                <div class="package-form-number">3</div>

                                <div>
                                    <h3 class="package-form-section-title">
                                        Harga dan Kapasitas
                                    </h3>

                                    <p class="package-form-section-text">
                                        Tentukan harga normal, harga paket, dan kapasitas.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="normal_price">
                                        Harga Normal
                                        <span class="package-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>

                                        <input class="form-control @error('normal_price') is-invalid @enderror"
                                            id="normal_price" name="normal_price" type="number"
                                            value="{{ old('normal_price', 0) }}" min="0" step="0.01" required>

                                        @error('normal_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-text">
                                        Dihitung otomatis dari komponen, tetapi tetap dapat diubah.
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="package_price">
                                        Harga Paket
                                        <span class="package-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>

                                        <input class="form-control @error('package_price') is-invalid @enderror"
                                            id="package_price" name="package_price" type="number"
                                            value="{{ old('package_price') }}" min="0" step="0.01" required>

                                        @error('package_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="small mb-1" for="serving_count">
                                        Jumlah Orang
                                        <span class="package-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control @error('serving_count') is-invalid @enderror"
                                            id="serving_count" name="serving_count" type="number"
                                            value="{{ old('serving_count', 1) }}" min="1" required>

                                        <span class="input-group-text">orang</span>
                                    </div>

                                    @error('serving_count')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1" for="minimum_order">
                                        Minimum Pemesanan
                                        <span class="package-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control @error('minimum_order') is-invalid @enderror"
                                            id="minimum_order" name="minimum_order" type="number"
                                            value="{{ old('minimum_order', 1) }}" min="1" required>

                                        <span class="input-group-text">paket</span>
                                    </div>

                                    @error('minimum_order')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="package-form-section">
                            <div class="package-form-heading">
                                <div class="package-form-number">4</div>

                                <div>
                                    <h3 class="package-form-section-title">
                                        Periode dan Status
                                    </h3>

                                    <p class="package-form-section-text">
                                        Tentukan masa berlaku dan status paket.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="available_from">
                                        Tersedia Mulai
                                    </label>

                                    <input class="form-control @error('available_from') is-invalid @enderror"
                                        id="available_from" name="available_from" type="datetime-local"
                                        value="{{ old('available_from') }}">

                                    @error('available_from')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="available_until">
                                        Tersedia Sampai
                                    </label>

                                    <input class="form-control @error('available_until') is-invalid @enderror"
                                        id="available_until" name="available_until" type="datetime-local"
                                        value="{{ old('available_until') }}">

                                    @error('available_until')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="small mb-1" for="sort_order">
                                        Urutan
                                    </label>

                                    <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order"
                                        name="sort_order" type="number" value="{{ old('sort_order', 0) }}"
                                        min="0" required>

                                    @error('sort_order')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="package-form-status">
                                        <input name="is_available" type="hidden" value="0">

                                        <div class="fw-bold small mb-2">
                                            Ketersediaan
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="is_available" name="is_available"
                                                type="checkbox" value="1" @checked(old('is_available', 1))>

                                            <label class="form-check-label" for="is_available">
                                                Tersedia
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="package-form-status">
                                        <input name="is_featured" type="hidden" value="0">

                                        <div class="fw-bold small mb-2">
                                            Paket Unggulan
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="is_featured" name="is_featured"
                                                type="checkbox" value="1" @checked(old('is_featured', 0))>

                                            <label class="form-check-label" for="is_featured">
                                                Unggulan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="package-form-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.menu-packages.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="submitPackageButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Paket
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card package-form-card mb-4">
                    <div class="package-form-header">
                        <h2 class="package-form-title">
                            <i data-feather="image" class="me-2"></i>
                            Gambar Paket
                        </h2>
                    </div>

                    <div class="card-body">
                        <div class="package-image-area">
                            <div class="package-image-wrapper">
                                <img class="package-image-preview" id="packageImagePreview" src=""
                                    alt="Preview gambar paket">

                                <div class="package-image-placeholder" id="packageImagePlaceholder">
                                    <i data-feather="image" class="mb-2"></i>

                                    <div class="fw-bold small">
                                        Belum ada gambar
                                    </div>
                                </div>
                            </div>

                            <input class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                            @error('image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Maksimal 3 MB.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="package-summary">
                    <div class="fw-bold mb-2">
                        Ringkasan Harga
                    </div>

                    <div class="package-summary-item">
                        <span>Harga normal</span>
                        <strong id="normalPricePreview">Rp0</strong>
                    </div>

                    <div class="package-summary-item">
                        <span>Harga paket</span>
                        <strong id="packagePricePreview">Rp0</strong>
                    </div>

                    <div class="package-summary-item">
                        <span>Hemat</span>
                        <strong class="text-success" id="savingPreview">Rp0</strong>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="packageItemTemplate">
        <div class="package-component" data-package-item>
            <div class="row gx-3 align-items-end">
                <div class="col-auto mb-3 mb-md-0">
                    <div class="package-component-number" data-component-number>
                        1
                    </div>
                </div>

                <div class="col-md-5 mb-3 mb-md-0">
                    <label class="small mb-1">
                        Menu
                        <span class="package-required">*</span>
                    </label>

                    <select class="form-select" data-field="menu_item_id" data-menu-select required>

                        <option value="">
                            Pilih menu
                        </option>

                        @foreach ($menuItems as $menuItem)
                            <option value="{{ $menuItem->id }}" data-price="{{ $menuItem->price }}">
                                {{ $menuItem->name }}
                                — Rp{{ number_format($menuItem->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="small mb-1">Jumlah</label>

                    <input class="form-control" data-field="quantity" data-quantity type="number" value="1"
                        min="1" max="999" required>
                </div>

                <div class="col-md mb-3 mb-md-0">
                    <label class="small mb-1">Catatan</label>

                    <input class="form-control" data-field="note" type="text" maxlength="1000"
                        placeholder="Opsional">
                </div>

                <div class="col-auto">
                    <button class="btn btn-outline-danger package-component-remove" data-remove-item type="button"
                        title="Hapus komponen">
                        <i data-feather="trash-2"></i>
                    </button>
                </div>

                <input data-field="sort_order" type="hidden" value="0">
            </div>
        </div>
    </template>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('packageCreateForm');
            const container = document.getElementById(
                'packageItemsContainer'
            );
            const emptyState = document.getElementById('packageItemsEmpty');
            const template = document.getElementById('packageItemTemplate');
            const addButton = document.getElementById(
                'addPackageItemButton'
            );
            const submitButton = document.getElementById(
                'submitPackageButton'
            );
            const normalPriceInput = document.getElementById('normal_price');
            const packagePriceInput = document.getElementById(
                'package_price'
            );
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById(
                'packageImagePreview'
            );
            const imagePlaceholder = document.getElementById(
                'packageImagePlaceholder'
            );
            const oldItems = @json($oldItems);

            let itemIndex = 0;

            function addPackageItem(item = {}) {
                const fragment = template.content.cloneNode(true);
                const itemElement = fragment.querySelector(
                    '[data-package-item]'
                );

                itemElement.querySelectorAll('[data-field]').forEach(
                    function(field) {
                        const fieldName = field.dataset.field;

                        field.name =
                            'items[' + itemIndex + '][' + fieldName + ']';

                        if (
                            Object.prototype.hasOwnProperty.call(
                                item,
                                fieldName
                            )
                        ) {
                            field.value = item[fieldName] ?? '';
                        }
                    }
                );

                itemElement
                    .querySelector('[data-remove-item]')
                    .addEventListener('click', function() {
                        itemElement.remove();
                        refreshComponents();
                    });

                itemElement
                    .querySelector('[data-menu-select]')
                    .addEventListener('change', calculateNormalPrice);

                itemElement
                    .querySelector('[data-quantity]')
                    .addEventListener('input', calculateNormalPrice);

                container.appendChild(itemElement);
                itemIndex++;

                refreshComponents();

                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }

            function refreshComponents() {
                const items = container.querySelectorAll(
                    '[data-package-item]'
                );

                emptyState.style.display =
                    items.length === 0 ? 'block' : 'none';

                items.forEach(function(item, index) {
                    item.querySelector(
                        '[data-component-number]'
                    ).textContent = index + 1;

                    item.querySelector(
                        '[data-field="sort_order"]'
                    ).value = index;
                });

                calculateNormalPrice();
            }

            function calculateNormalPrice() {
                let total = 0;

                container
                    .querySelectorAll('[data-package-item]')
                    .forEach(function(item) {
                        const select = item.querySelector(
                            '[data-menu-select]'
                        );
                        const quantity = Number(
                            item.querySelector('[data-quantity]').value
                        ) || 0;
                        const selectedOption =
                            select.options[select.selectedIndex];
                        const price = Number(
                            selectedOption?.dataset.price
                        ) || 0;

                        total += price * quantity;
                    });

                normalPriceInput.value = total;
                updatePriceSummary();
            }

            function updatePriceSummary() {
                const normalPrice =
                    Number(normalPriceInput.value) || 0;
                const packagePrice =
                    Number(packagePriceInput.value) || 0;
                const saving = Math.max(
                    0,
                    normalPrice - packagePrice
                );

                document.getElementById(
                    'normalPricePreview'
                ).textContent = formatRupiah(normalPrice);

                document.getElementById(
                    'packagePricePreview'
                ).textContent = formatRupiah(packagePrice);

                document.getElementById(
                    'savingPreview'
                ).textContent = formatRupiah(saving);
            }

            function formatRupiah(value) {
                return 'Rp' + new Intl.NumberFormat('id-ID').format(value);
            }

            addButton.addEventListener('click', function() {
                addPackageItem();
            });

            normalPriceInput.addEventListener(
                'input',
                updatePriceSummary
            );

            packagePriceInput.addEventListener(
                'input',
                updatePriceSummary
            );

            imageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (!file) {
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                    imagePlaceholder.style.display = 'block';

                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (
                    !allowedTypes.includes(file.type) ||
                    file.size > 3 * 1024 * 1024
                ) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gambar tidak valid',
                        text: 'Gunakan JPG, PNG, atau WEBP maksimal 3 MB.',
                        confirmButtonColor: '#dc3545'
                    });

                    imageInput.value = '';

                    return;
                }

                const reader = new FileReader();

                reader.onload = function(readerEvent) {
                    imagePreview.src = readerEvent.target.result;
                    imagePreview.style.display = 'block';
                    imagePlaceholder.style.display = 'none';
                };

                reader.readAsDataURL(file);
            });

            form.addEventListener('submit', function(event) {
                const itemCount = container.querySelectorAll(
                    '[data-package-item]'
                ).length;

                if (itemCount === 0) {
                    event.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Komponen belum dipilih',
                        text: 'Tambahkan minimal satu menu ke dalam paket.',
                        confirmButtonColor: '#0061f2'
                    });

                    return;
                }

                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            if (oldItems.length > 0) {
                oldItems.forEach(function(item) {
                    addPackageItem(item);
                });
            } else {
                addPackageItem();
            }

            updatePriceSummary();

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Data paket belum valid',
                    text: @json($errors->first()),
                    confirmButtonText: 'Periksa Kembali',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>
@endpush
