@extends('admin.layouts.main')

@section('page_title', 'Edit Menu || ' . config('app.name'))

@section('meta_description', 'Perbarui informasi makanan atau minuman restoran.')

@section('header_title', 'Edit Menu')

@section('header_subtitle', 'Perbarui informasi, harga, kategori, gambar, dan ketersediaan menu.')

@section('header_icon', 'edit-3')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.menu-items.index') }}">
        <i data-feather="arrow-left" class="me-1"></i>
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
        <a href="{{ route('management.menu-items.index') }}">
            Daftar Menu
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Edit {{ $menuItem->name }}
    </li>
@endsection

@push('style')
    <style>
        .menu-edit-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .menu-edit-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .menu-edit-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .menu-edit-section {
            padding-bottom: 1.4rem;
            margin-bottom: 1.4rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .menu-edit-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.1rem;
        }

        .menu-edit-number {
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

        .menu-edit-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .menu-edit-section-text {
            margin: 0.1rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .menu-edit-required {
            color: #e81500;
        }

        .menu-edit-status {
            height: 100%;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.6rem;
        }

        .menu-edit-image-area {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px dashed #b8c2cc;
            border-radius: 0.65rem;
        }

        .menu-edit-image-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 230px;
            margin-bottom: 1rem;
            overflow: hidden;
            background-color: #eef2f6;
            border-radius: 0.6rem;
        }

        .menu-edit-image {
            width: 100%;
            height: 230px;
            object-fit: cover;
        }

        .menu-edit-image-placeholder {
            color: #69707a;
            text-align: center;
        }

        .menu-edit-image-placeholder svg {
            width: 42px;
            height: 42px;
            color: #0061f2;
        }

        .menu-edit-beverage {
            display: none;
            padding: 1rem;
            margin-top: 1rem;
            background-color: rgba(0, 97, 242, 0.05);
            border: 1px solid rgba(0, 97, 242, 0.12);
            border-radius: 0.65rem;
        }

        .menu-edit-beverage.is-visible {
            display: block;
        }

        .menu-edit-information {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .menu-edit-information-item {
            display: flex;
            justify-content: space-between;
            padding: 0.65rem 0;
            border-bottom: 1px solid #e0e5ec;
            font-size: 0.76rem;
        }

        .menu-edit-information-item:last-child {
            border-bottom: 0;
        }

        .menu-edit-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e0e5ec;
        }

        @media (max-width: 767.98px) {
            .menu-edit-footer {
                flex-direction: column-reverse;
            }

            .menu-edit-footer .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $menuImageUrl = $menuItem->image ? asset('storage/' . $menuItem->image) : null;
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <div class="d-flex align-items-start">
                <i data-feather="alert-triangle" class="me-2 mt-1"></i>

                <div>
                    <strong>Data menu belum valid.</strong>

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

    <form id="menuEditForm" action="{{ route('management.menu-items.update', $menuItem) }}" method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <div class="card menu-edit-card mb-4">
                    <div class="menu-edit-header">
                        <h2 class="menu-edit-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Menu
                        </h2>

                        <span class="badge bg-primary-soft text-primary">
                            {{ $menuItem->sku }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="menu-edit-section">
                            <div class="menu-edit-heading">
                                <div class="menu-edit-number">1</div>

                                <div>
                                    <h3 class="menu-edit-section-title">
                                        Informasi Utama
                                    </h3>

                                    <p class="menu-edit-section-text">
                                        Perbarui kategori, SKU, nama, dan deskripsi.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-5 mb-3">
                                    <label class="small mb-1" for="category_id">
                                        Kategori
                                        <span class="menu-edit-required">*</span>
                                    </label>

                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                        name="category_id" required>

                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" data-menu-type="{{ $category->menu_type }}"
                                                @selected((string) old('category_id', $menuItem->category_id) === (string) $category->id)>
                                                {{ $category->name }}
                                                ({{ $category->menu_type === 'food' ? 'Makanan' : 'Minuman' }})
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="small mb-1" for="sku">
                                        SKU
                                        <span class="menu-edit-required">*</span>
                                    </label>

                                    <input class="form-control @error('sku') is-invalid @enderror" id="sku"
                                        name="sku" type="text" value="{{ old('sku', $menuItem->sku) }}"
                                        maxlength="50" required>

                                    @error('sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="small mb-1" for="name">
                                        Nama Menu
                                        <span class="menu-edit-required">*</span>
                                    </label>

                                    <input class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" type="text" value="{{ old('name', $menuItem->name) }}"
                                        maxlength="150" required>

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
                                rows="5" maxlength="3000">{{ old('description', $menuItem->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="menu-edit-section">
                            <div class="menu-edit-heading">
                                <div class="menu-edit-number">2</div>

                                <div>
                                    <h3 class="menu-edit-section-title">
                                        Harga dan Persiapan
                                    </h3>

                                    <p class="menu-edit-section-text">
                                        Perbarui harga dan waktu persiapan.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="small mb-1" for="price">
                                        Harga
                                        <span class="menu-edit-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>

                                        <input class="form-control @error('price') is-invalid @enderror" id="price"
                                            name="price" type="number" value="{{ old('price', $menuItem->price) }}"
                                            min="0" step="0.01" required>

                                        @error('price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1" for="preparation_time">
                                        Waktu Persiapan
                                        <span class="menu-edit-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control @error('preparation_time') is-invalid @enderror"
                                            id="preparation_time" name="preparation_time" type="number"
                                            value="{{ old('preparation_time', $menuItem->preparation_time) }}"
                                            min="1" max="1440" required>

                                        <span class="input-group-text">menit</span>

                                        @error('preparation_time')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="menu-edit-beverage" id="beverageSettings">

                                <div class="row gx-3">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="small mb-1" for="beverage_type">
                                            Jenis Minuman
                                            <span class="menu-edit-required">*</span>
                                        </label>

                                        <select class="form-select @error('beverage_type') is-invalid @enderror"
                                            id="beverage_type" name="beverage_type">

                                            <option value="">
                                                Pilih jenis
                                            </option>

                                            <option value="coffee" @selected(old('beverage_type', $menuItem->beverage_type) === 'coffee')>
                                                Coffee
                                            </option>

                                            <option value="non_coffee" @selected(old('beverage_type', $menuItem->beverage_type) === 'non_coffee')>
                                                Non-Coffee
                                            </option>
                                        </select>

                                        @error('beverage_type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="small mb-1">
                                            Kandungan Alkohol
                                        </label>

                                        <div class="menu-edit-status">
                                            <input name="is_alcoholic" type="hidden" value="0">

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" id="is_alcoholic" name="is_alcoholic"
                                                    type="checkbox" value="1" @checked(old('is_alcoholic', $menuItem->is_alcoholic))>

                                                <label class="form-check-label" for="is_alcoholic">
                                                    Mengandung alkohol
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="menu-edit-section">
                            <div class="menu-edit-heading">
                                <div class="menu-edit-number">3</div>

                                <div>
                                    <h3 class="menu-edit-section-title">
                                        Status dan Tampilan
                                    </h3>

                                    <p class="menu-edit-section-text">
                                        Perbarui urutan dan ketersediaan menu.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-lg-4 mb-3">
                                    <label class="small mb-1" for="sort_order">
                                        Urutan
                                        <span class="menu-edit-required">*</span>
                                    </label>

                                    <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order"
                                        name="sort_order" type="number"
                                        value="{{ old('sort_order', $menuItem->sort_order) }}" min="0" required>

                                    @error('sort_order')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <div class="menu-edit-status">
                                        <input name="is_available" type="hidden" value="0">

                                        <div class="fw-bold small mb-2">
                                            Ketersediaan
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="is_available" name="is_available"
                                                type="checkbox" value="1" @checked(old('is_available', $menuItem->is_available))>

                                            <label class="form-check-label" for="is_available">
                                                Menu tersedia
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <div class="menu-edit-status">
                                        <input name="is_featured" type="hidden" value="0">

                                        <div class="fw-bold small mb-2">
                                            Menu Unggulan
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="is_featured" name="is_featured"
                                                type="checkbox" value="1" @checked(old('is_featured', $menuItem->is_featured))>

                                            <label class="form-check-label" for="is_featured">
                                                Tampilkan sebagai unggulan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="menu-edit-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.menu-items.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="updateMenuButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card menu-edit-card mb-4">
                    <div class="menu-edit-header">
                        <h2 class="menu-edit-title">
                            <i data-feather="image" class="me-2"></i>
                            Gambar Menu
                        </h2>
                    </div>

                    <div class="card-body">
                        <div class="menu-edit-image-area">
                            <div class="menu-edit-image-wrapper">
                                <img class="menu-edit-image" id="menuImagePreview" src="{{ $menuImageUrl ?: '' }}"
                                    alt="{{ $menuItem->name }}" style="{{ $menuImageUrl ? '' : 'display: none;' }}">

                                <div class="menu-edit-image-placeholder" id="menuImagePlaceholder"
                                    style="{{ $menuImageUrl ? 'display: none;' : '' }}">
                                    <i data-feather="image"></i>

                                    <div class="fw-bold small mt-2">
                                        Belum ada gambar
                                    </div>
                                </div>
                            </div>

                            <label class="small mb-1" for="image">
                                Ganti Gambar
                            </label>

                            <input class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

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
                </div>

                <div class="card menu-edit-card mb-4">
                    <div class="menu-edit-header">
                        <h2 class="menu-edit-title">
                            <i data-feather="info" class="me-2"></i>
                            Informasi Data
                        </h2>
                    </div>

                    <div class="card-body">
                        <div class="menu-edit-information">
                            <div class="menu-edit-information-item">
                                <span class="text-muted">ID Menu</span>
                                <strong>#{{ $menuItem->id }}</strong>
                            </div>

                            <div class="menu-edit-information-item">
                                <span class="text-muted">Slug</span>
                                <strong>{{ $menuItem->slug }}</strong>
                            </div>

                            <div class="menu-edit-information-item">
                                <span class="text-muted">Dibuat</span>

                                <strong>
                                    {{ $menuItem->created_at?->translatedFormat('d M Y H:i') ?? '-' }}
                                </strong>
                            </div>

                            <div class="menu-edit-information-item">
                                <span class="text-muted">Diperbarui</span>

                                <strong>
                                    {{ $menuItem->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('menuEditForm');
            const updateButton = document.getElementById('updateMenuButton');
            const categoryInput = document.getElementById('category_id');
            const beverageSettings = document.getElementById(
                'beverageSettings'
            );
            const beverageTypeInput = document.getElementById(
                'beverage_type'
            );
            const alcoholicInput = document.getElementById('is_alcoholic');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('menuImagePreview');
            const imagePlaceholder = document.getElementById(
                'menuImagePlaceholder'
            );
            const originalImage = @json($menuImageUrl);

            function updateCategorySettings() {
                const selectedOption =
                    categoryInput.options[categoryInput.selectedIndex];

                const isBeverage =
                    selectedOption?.dataset.menuType === 'beverage';

                beverageSettings.classList.toggle(
                    'is-visible',
                    isBeverage
                );

                beverageTypeInput.required = isBeverage;

                if (!isBeverage) {
                    beverageTypeInput.value = '';
                    alcoholicInput.checked = false;
                }
            }

            function restoreImage() {
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

            categoryInput?.addEventListener(
                'change',
                updateCategorySettings
            );

            imageInput?.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (!file) {
                    restoreImage();
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format gambar tidak valid',
                        text: 'Gunakan JPG, JPEG, PNG, atau WEBP.',
                        confirmButtonColor: '#dc3545'
                    });

                    imageInput.value = '';
                    restoreImage();

                    return;
                }

                if (file.size > 3 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran gambar terlalu besar',
                        text: 'Ukuran maksimal adalah 3 MB.',
                        confirmButtonColor: '#dc3545'
                    });

                    imageInput.value = '';
                    restoreImage();

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

            form?.addEventListener('submit', function() {
                updateButton.disabled = true;
                updateButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            updateCategorySettings();

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Data menu belum valid',
                    text: @json($errors->first()),
                    confirmButtonText: 'Periksa Kembali',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>
@endpush
