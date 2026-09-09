@extends('admin.layouts.main')

@section('page_title', 'Tambah Menu || ' . config('app.name'))

@section('meta_description', 'Tambahkan makanan atau minuman baru.')

@section('header_title', 'Tambah Menu')

@section('header_subtitle', 'Tambahkan makanan atau minuman baru ke daftar menu restoran.')

@section('header_icon', 'plus-circle')

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
        Tambah Menu
    </li>
@endsection

@push('style')
    <style>
        .menu-form-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .menu-form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .menu-form-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .menu-form-title svg {
            width: 18px;
            height: 18px;
        }

        .menu-form-section {
            padding-bottom: 1.4rem;
            margin-bottom: 1.4rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .menu-form-section:last-of-type {
            padding-bottom: 0;
            margin-bottom: 0;
            border-bottom: 0;
        }

        .menu-form-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.1rem;
        }

        .menu-form-number {
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

        .menu-form-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .menu-form-section-subtitle {
            margin: 0.1rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .menu-form-required {
            color: #e81500;
        }

        .menu-form-status {
            height: 100%;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.6rem;
        }

        .menu-form-status-title {
            margin-bottom: 0.15rem;
            color: #363d47;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .menu-form-status-text {
            margin-bottom: 0.75rem;
            color: #69707a;
            font-size: 0.72rem;
        }

        .menu-form-image-area {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px dashed #b8c2cc;
            border-radius: 0.65rem;
        }

        .menu-form-image-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 230px;
            margin-bottom: 1rem;
            overflow: hidden;
            background-color: #eef2f6;
            border-radius: 0.6rem;
        }

        .menu-form-image {
            display: none;
            width: 100%;
            height: 230px;
            object-fit: cover;
        }

        .menu-form-image-placeholder {
            color: #69707a;
            text-align: center;
        }

        .menu-form-image-placeholder svg {
            width: 42px;
            height: 42px;
            margin-bottom: 0.7rem;
            color: #0061f2;
        }

        .menu-form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e0e5ec;
        }

        .menu-beverage-settings {
            display: none;
            padding: 1rem;
            margin-top: 1rem;
            background:
                linear-gradient(135deg,
                    rgba(0, 97, 242, 0.06),
                    rgba(105, 0, 199, 0.06));
            border: 1px solid rgba(0, 97, 242, 0.12);
            border-radius: 0.65rem;
        }

        .menu-beverage-settings.is-visible {
            display: block;
        }

        @media (max-width: 767.98px) {
            .menu-form-footer {
                flex-direction: column-reverse;
            }

            .menu-form-footer .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Tutup">
            </button>
        </div>
    @endif

    <form id="menuCreateForm" action="{{ route('management.menu-items.store') }}" method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="row">
            <div class="col-xl-8">
                <div class="card menu-form-card mb-4">
                    <div class="menu-form-header">
                        <h2 class="menu-form-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Menu
                        </h2>

                        <span class="badge bg-primary-soft text-primary">
                            Menu baru
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="menu-form-section">
                            <div class="menu-form-heading">
                                <div class="menu-form-number">1</div>

                                <div>
                                    <h3 class="menu-form-section-title">
                                        Informasi Utama
                                    </h3>

                                    <p class="menu-form-section-subtitle">
                                        Tentukan kategori, SKU, nama, dan deskripsi menu.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-5 mb-3">
                                    <label class="small mb-1" for="category_id">
                                        Kategori
                                        <span class="menu-form-required">*</span>
                                    </label>

                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                        name="category_id" required>

                                        <option value="">
                                            Pilih kategori
                                        </option>

                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" data-menu-type="{{ $category->menu_type }}"
                                                @selected((string) old('category_id') === (string) $category->id)>
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
                                        <span class="menu-form-required">*</span>
                                    </label>

                                    <input class="form-control @error('sku') is-invalid @enderror" id="sku"
                                        name="sku" type="text" value="{{ old('sku') }}" maxlength="50"
                                        placeholder="MN-001" required>

                                    @error('sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="small mb-1" for="name">
                                        Nama Menu
                                        <span class="menu-form-required">*</span>
                                    </label>

                                    <input class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" type="text" value="{{ old('name') }}" maxlength="150"
                                        placeholder="Nama makanan/minuman" required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="small mb-1" for="description">
                                    Deskripsi
                                </label>

                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" maxlength="3000" placeholder="Jelaskan rasa, bahan, atau informasi menu...">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    <span id="descriptionCounter">
                                        {{ strlen(old('description', '')) }}
                                    </span>/3000 karakter
                                </div>
                            </div>
                        </div>

                        <div class="menu-form-section">
                            <div class="menu-form-heading">
                                <div class="menu-form-number">2</div>

                                <div>
                                    <h3 class="menu-form-section-title">
                                        Harga dan Persiapan
                                    </h3>

                                    <p class="menu-form-section-subtitle">
                                        Tentukan harga jual dan estimasi pembuatan.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="small mb-1" for="price">
                                        Harga Jual
                                        <span class="menu-form-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            Rp
                                        </span>

                                        <input class="form-control @error('price') is-invalid @enderror" id="price"
                                            name="price" type="number" value="{{ old('price') }}" min="0"
                                            step="0.01" placeholder="0" required>

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
                                        <span class="menu-form-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control @error('preparation_time') is-invalid @enderror"
                                            id="preparation_time" name="preparation_time" type="number"
                                            value="{{ old('preparation_time', 15) }}" min="1" max="1440"
                                            required>

                                        <span class="input-group-text">
                                            menit
                                        </span>

                                        @error('preparation_time')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="menu-beverage-settings" id="beverageSettings">

                                <div class="row gx-3">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="small mb-1" for="beverage_type">
                                            Jenis Minuman
                                            <span class="menu-form-required">*</span>
                                        </label>

                                        <select class="form-select @error('beverage_type') is-invalid @enderror"
                                            id="beverage_type" name="beverage_type">

                                            <option value="">
                                                Pilih jenis minuman
                                            </option>

                                            <option value="coffee" @selected(old('beverage_type') === 'coffee')>
                                                Coffee
                                            </option>

                                            <option value="non_coffee" @selected(old('beverage_type') === 'non_coffee')>
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

                                        <div class="menu-form-status">
                                            <input name="is_alcoholic" type="hidden" value="0">

                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" id="is_alcoholic" name="is_alcoholic"
                                                    type="checkbox" value="1" @checked(old('is_alcoholic', 0))>

                                                <label class="form-check-label" for="is_alcoholic">
                                                    Mengandung alkohol
                                                </label>
                                            </div>
                                        </div>

                                        @error('is_alcoholic')
                                            <div class="text-danger small mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="menu-form-section">
                            <div class="menu-form-heading">
                                <div class="menu-form-number">3</div>

                                <div>
                                    <h3 class="menu-form-section-title">
                                        Status dan Tampilan
                                    </h3>

                                    <p class="menu-form-section-subtitle">
                                        Tentukan ketersediaan, rekomendasi, dan urutan menu.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-lg-4 mb-3 mb-lg-0">
                                    <label class="small mb-1" for="sort_order">
                                        Urutan
                                        <span class="menu-form-required">*</span>
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

                                <div class="col-lg-4 mb-3 mb-lg-0">
                                    <div class="menu-form-status">
                                        <div class="menu-form-status-title">
                                            Ketersediaan
                                        </div>

                                        <p class="menu-form-status-text">
                                            Menu dapat dipesan customer.
                                        </p>

                                        <input name="is_available" type="hidden" value="0">

                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" id="is_available" name="is_available"
                                                type="checkbox" value="1" @checked(old('is_available', 1))>

                                            <label class="form-check-label" for="is_available">
                                                Tersedia
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="menu-form-status">
                                        <div class="menu-form-status-title">
                                            Menu Unggulan
                                        </div>

                                        <p class="menu-form-status-text">
                                            Tampilkan sebagai rekomendasi.
                                        </p>

                                        <input name="is_featured" type="hidden" value="0">

                                        <div class="form-check form-switch mb-0">
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

                        <div class="menu-form-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.menu-items.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="submitMenuButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Menu
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card menu-form-card mb-4">
                    <div class="menu-form-header">
                        <h2 class="menu-form-title">
                            <i data-feather="image" class="me-2"></i>
                            Gambar Menu
                        </h2>
                    </div>

                    <div class="card-body">
                        <div class="menu-form-image-area">
                            <div class="menu-form-image-wrapper">
                                <img class="menu-form-image" id="menuImagePreview" src=""
                                    alt="Preview gambar menu">

                                <div class="menu-form-image-placeholder" id="menuImagePlaceholder">
                                    <i data-feather="image"></i>

                                    <div class="fw-bold small">
                                        Belum ada gambar
                                    </div>

                                    <div class="small">
                                        Preview akan tampil di sini.
                                    </div>
                                </div>
                            </div>

                            <label class="small mb-1" for="image">
                                Pilih Gambar
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
                                JPG, JPEG, PNG, atau WEBP. Maksimal 3 MB.
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
            const form = document.getElementById('menuCreateForm');
            const submitButton = document.getElementById('submitMenuButton');
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
            const descriptionInput = document.getElementById('description');
            const descriptionCounter = document.getElementById(
                'descriptionCounter'
            );

            function updateCategorySettings() {
                const selectedOption =
                    categoryInput.options[categoryInput.selectedIndex];

                const menuType = selectedOption?.dataset.menuType;
                const isBeverage = menuType === 'beverage';

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

            function resetImagePreview() {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                imagePlaceholder.style.display = 'block';
            }

            categoryInput?.addEventListener(
                'change',
                updateCategorySettings
            );

            descriptionInput?.addEventListener('input', function() {
                descriptionCounter.textContent =
                    descriptionInput.value.length;
            });

            imageInput?.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (!file) {
                    resetImagePreview();
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
                    resetImagePreview();

                    return;
                }

                if (file.size > 3 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran gambar terlalu besar',
                        text: 'Ukuran gambar maksimal adalah 3 MB.',
                        confirmButtonColor: '#dc3545'
                    });

                    imageInput.value = '';
                    resetImagePreview();

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
                submitButton.disabled = true;
                submitButton.innerHTML =
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
