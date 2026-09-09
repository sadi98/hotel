@extends('admin.layouts.main')

@section('page_title', 'Edit Paket Menu || ' . config('app.name'))

@section('meta_description', 'Perbarui paket atau bundling menu restoran.')

@section('header_title', 'Edit Paket Menu')

@section('header_subtitle', 'Perbarui informasi, komponen, harga, dan ketersediaan paket.')

@section('header_icon', 'edit-3')

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
        Edit {{ $menuPackage->name }}
    </li>
@endsection

@push('style')
    <style>
        .package-edit-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .package-edit-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .package-edit-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .package-edit-section {
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .package-edit-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.1rem;
        }

        .package-edit-number {
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

        .package-edit-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .package-edit-section-text {
            margin: 0.1rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .package-edit-component {
            padding: 1rem;
            margin-bottom: 0.75rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .package-edit-component-number {
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

        .package-edit-remove {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
        }

        .package-edit-image-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 220px;
            margin-bottom: 1rem;
            overflow: hidden;
            background-color: #eef2f6;
            border-radius: 0.6rem;
        }

        .package-edit-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .package-edit-status {
            height: 100%;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .package-edit-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e0e5ec;
        }

        @media (max-width: 767.98px) {
            .package-edit-footer {
                flex-direction: column-reverse;
            }

            .package-edit-footer .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $storedItems = $selectedItems
            ->values()
            ->map(function ($item) {
                return [
                    'menu_item_id' => $item->menu_item_id,
                    'quantity' => $item->quantity,
                    'note' => $item->note,
                    'sort_order' => $item->sort_order,
                ];
            })
            ->all();

        $initialItems = old('items', $storedItems);

        $packageImageUrl = $menuPackage->image ? asset('storage/' . $menuPackage->image) : null;

        $availableFrom = old(
            'available_from',
            $menuPackage->available_from
                ? \Illuminate\Support\Carbon::parse($menuPackage->available_from)->format('Y-m-d\TH:i')
                : '',
        );

        $availableUntil = old(
            'available_until',
            $menuPackage->available_until
                ? \Illuminate\Support\Carbon::parse($menuPackage->available_until)->format('Y-m-d\TH:i')
                : '',
        );
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

    <form id="packageEditForm" action="{{ route('management.menu-packages.update', $menuPackage) }}" method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <div class="card package-edit-card mb-4">
                    <div class="package-edit-header">
                        <h2 class="package-edit-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Paket
                        </h2>

                        <span class="badge bg-primary-soft text-primary">
                            {{ $menuPackage->sku }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="package-edit-section">
                            <div class="package-edit-heading">
                                <div class="package-edit-number">1</div>

                                <div>
                                    <h3 class="package-edit-section-title">
                                        Informasi Utama
                                    </h3>

                                    <p class="package-edit-section-text">
                                        Perbarui SKU, nama, dan deskripsi paket.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-4 mb-3">
                                    <label class="small mb-1" for="sku">
                                        SKU
                                    </label>

                                    <input class="form-control @error('sku') is-invalid @enderror" id="sku"
                                        name="sku" type="text" value="{{ old('sku', $menuPackage->sku) }}" required>

                                    @error('sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="small mb-1" for="name">
                                        Nama Paket
                                    </label>

                                    <input class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" type="text" value="{{ old('name', $menuPackage->name) }}"
                                        required>

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
                                rows="4">{{ old('description', $menuPackage->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="package-edit-section">
                            <div class="package-edit-heading">
                                <div class="package-edit-number">2</div>

                                <div>
                                    <h3 class="package-edit-section-title">
                                        Komponen Paket
                                    </h3>

                                    <p class="package-edit-section-text">
                                        Perbarui menu yang terdapat dalam paket.
                                    </p>
                                </div>
                            </div>

                            <div id="editPackageItemsContainer"></div>

                            <button class="btn btn-outline-primary" id="addEditPackageItem" type="button">
                                <i data-feather="plus" class="me-1"></i>
                                Tambah Komponen
                            </button>
                        </div>

                        <div class="package-edit-section">
                            <div class="package-edit-heading">
                                <div class="package-edit-number">3</div>

                                <div>
                                    <h3 class="package-edit-section-title">
                                        Harga dan Kapasitas
                                    </h3>

                                    <p class="package-edit-section-text">
                                        Perbarui harga dan kapasitas paket.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="normal_price">
                                        Harga Normal
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>

                                        <input class="form-control @error('normal_price') is-invalid @enderror"
                                            id="normal_price" name="normal_price" type="number"
                                            value="{{ old('normal_price', $menuPackage->normal_price) }}" min="0"
                                            step="0.01" required>

                                        @error('normal_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="package_price">
                                        Harga Paket
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>

                                        <input class="form-control @error('package_price') is-invalid @enderror"
                                            id="package_price" name="package_price" type="number"
                                            value="{{ old('package_price', $menuPackage->package_price) }}"
                                            min="0" step="0.01" required>

                                        @error('package_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="serving_count">
                                        Jumlah Orang
                                    </label>

                                    <input class="form-control @error('serving_count') is-invalid @enderror"
                                        id="serving_count" name="serving_count" type="number"
                                        value="{{ old('serving_count', $menuPackage->serving_count) }}" min="1"
                                        required>

                                    @error('serving_count')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="minimum_order">
                                        Minimum Pemesanan
                                    </label>

                                    <input class="form-control @error('minimum_order') is-invalid @enderror"
                                        id="minimum_order" name="minimum_order" type="number"
                                        value="{{ old('minimum_order', $menuPackage->minimum_order) }}" min="1"
                                        required>

                                    @error('minimum_order')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="package-edit-section">
                            <div class="package-edit-heading">
                                <div class="package-edit-number">4</div>

                                <div>
                                    <h3 class="package-edit-section-title">
                                        Periode dan Status
                                    </h3>

                                    <p class="package-edit-section-text">
                                        Perbarui masa berlaku dan status paket.
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
                                        value="{{ $availableFrom }}">

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
                                        value="{{ $availableUntil }}">

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
                                        name="sort_order" type="number"
                                        value="{{ old('sort_order', $menuPackage->sort_order) }}" min="0"
                                        required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="package-edit-status">
                                        <input name="is_available" type="hidden" value="0">

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="is_available" name="is_available"
                                                type="checkbox" value="1" @checked(old('is_available', $menuPackage->is_available))>

                                            <label class="form-check-label" for="is_available">
                                                Paket tersedia
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="package-edit-status">
                                        <input name="is_featured" type="hidden" value="0">

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="is_featured" name="is_featured"
                                                type="checkbox" value="1" @checked(old('is_featured', $menuPackage->is_featured))>

                                            <label class="form-check-label" for="is_featured">
                                                Paket unggulan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="package-edit-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.menu-packages.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="updatePackageButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card package-edit-card mb-4">
                    <div class="package-edit-header">
                        <h2 class="package-edit-title">
                            <i data-feather="image" class="me-2"></i>
                            Gambar Paket
                        </h2>
                    </div>

                    <div class="card-body">
                        <div class="package-edit-image-wrapper">
                            <img class="package-edit-image" id="editPackageImagePreview"
                                src="{{ $packageImageUrl ?: '' }}" alt="{{ $menuPackage->name }}"
                                style="{{ $packageImageUrl ? '' : 'display: none;' }}">

                            <div id="editPackageImagePlaceholder" class="text-center text-muted"
                                style="{{ $packageImageUrl ? 'display: none;' : '' }}">
                                <i data-feather="image"></i>

                                <div class="small fw-bold mt-2">
                                    Belum ada gambar
                                </div>
                            </div>
                        </div>

                        <input class="form-control @error('image') is-invalid @enderror" id="image" name="image"
                            type="file" accept=".jpg,.jpeg,.png,.webp">

                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Kosongkan jika gambar tidak diubah. Maksimal 3 MB.
                        </div>
                    </div>
                </div>

                <div class="card package-edit-card mb-4">
                    <div class="package-edit-header">
                        <h2 class="package-edit-title">
                            <i data-feather="info" class="me-2"></i>
                            Informasi Data
                        </h2>
                    </div>

                    <div class="card-body small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">ID Paket</span>
                            <strong>#{{ $menuPackage->id }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Slug</span>
                            <strong>{{ $menuPackage->slug }}</strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Diperbarui</span>

                            <strong>
                                {{ $menuPackage->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="editPackageItemTemplate">
        <div class="package-edit-component" data-package-item>
            <div class="row gx-3 align-items-end">
                <div class="col-auto mb-3 mb-md-0">
                    <div class="package-edit-component-number" data-component-number>
                        1
                    </div>
                </div>

                <div class="col-md-5 mb-3 mb-md-0">
                    <label class="small mb-1">Menu</label>

                    <select class="form-select" data-field="menu_item_id" required>

                        <option value="">Pilih menu</option>

                        @foreach ($menuItems as $menuItem)
                            <option value="{{ $menuItem->id }}">
                                {{ $menuItem->name }}
                                — Rp{{ number_format($menuItem->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="small mb-1">Jumlah</label>

                    <input class="form-control" data-field="quantity" type="number" min="1" max="999"
                        value="1" required>
                </div>

                <div class="col-md mb-3 mb-md-0">
                    <label class="small mb-1">Catatan</label>

                    <input class="form-control" data-field="note" type="text">
                </div>

                <div class="col-auto">
                    <button class="btn btn-outline-danger package-edit-remove" data-remove-item type="button">
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
            const form = document.getElementById('packageEditForm');
            const container = document.getElementById(
                'editPackageItemsContainer'
            );
            const template = document.getElementById(
                'editPackageItemTemplate'
            );
            const addButton = document.getElementById(
                'addEditPackageItem'
            );
            const updateButton = document.getElementById(
                'updatePackageButton'
            );
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById(
                'editPackageImagePreview'
            );
            const imagePlaceholder = document.getElementById(
                'editPackageImagePlaceholder'
            );
            const originalImage = @json($packageImageUrl);
            const initialItems = @json($initialItems);

            let itemIndex = 0;

            function addItem(item = {}) {
                const fragment = template.content.cloneNode(true);
                const element = fragment.querySelector(
                    '[data-package-item]'
                );

                element.querySelectorAll('[data-field]').forEach(
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

                element
                    .querySelector('[data-remove-item]')
                    .addEventListener('click', function() {
                        element.remove();
                        refreshNumbers();
                    });

                container.appendChild(element);
                itemIndex++;

                refreshNumbers();

                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }

            function refreshNumbers() {
                container
                    .querySelectorAll('[data-package-item]')
                    .forEach(function(item, index) {
                        item.querySelector(
                            '[data-component-number]'
                        ).textContent = index + 1;

                        item.querySelector(
                            '[data-field="sort_order"]'
                        ).value = index;
                    });
            }

            function restoreOriginalImage() {
                if (originalImage) {
                    imagePreview.src = originalImage;
                    imagePreview.style.display = 'block';
                    imagePlaceholder.style.display = 'none';

                    return;
                }

                imagePreview.src = '';
                imagePreview.style.display = 'none';
                imagePlaceholder.style.display = 'block';
            }

            addButton.addEventListener('click', function() {
                addItem();
            });

            imageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (!file) {
                    restoreOriginalImage();
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
                    restoreOriginalImage();

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
                const totalItems = container.querySelectorAll(
                    '[data-package-item]'
                ).length;

                if (totalItems === 0) {
                    event.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Komponen belum tersedia',
                        text: 'Paket harus memiliki minimal satu menu.',
                        confirmButtonColor: '#0061f2'
                    });

                    return;
                }

                updateButton.disabled = true;
                updateButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            if (initialItems.length > 0) {
                initialItems.forEach(function(item) {
                    addItem(item);
                });
            } else {
                addItem();
            }

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
