@extends('admin.layouts.main')

@section('page_title', 'Edit Kategori || ' . config('app.name'))

@section('meta_description', 'Perbarui informasi kategori makanan atau minuman restoran.')

@section('header_title', 'Edit Kategori')

@section('header_subtitle', 'Perbarui informasi, jenis, gambar, urutan, dan status kategori.')

@section('header_icon', 'edit-3')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.categories.index') }}">
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
        <a href="{{ route('management.categories.index') }}">
            Kategori Menu
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Edit {{ $category->name }}
    </li>
@endsection

@push('style')
    <style>
        .category-edit-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .category-edit-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .category-edit-card-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .category-edit-card-title svg {
            width: 18px;
            height: 18px;
        }

        .category-edit-section {
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .category-edit-section:last-child {
            padding-bottom: 0;
            margin-bottom: 0;
            border-bottom: 0;
        }

        .category-edit-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .category-edit-number {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            margin-right: 0.7rem;
            color: #ffffff;
            background: linear-gradient(135deg, #0061f2, #6900c7);
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .category-edit-title {
            margin: 0;
            color: #363d47;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .category-edit-subtitle {
            margin: 0.15rem 0 0;
            color: #69707a;
            font-size: 0.76rem;
        }

        .category-edit-required {
            color: #e81500;
        }

        .category-edit-type-option {
            position: relative;
            height: 100%;
        }

        .category-edit-type-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .category-edit-type-label {
            display: flex;
            align-items: center;
            height: 100%;
            min-height: 90px;
            padding: 1rem;
            cursor: pointer;
            background-color: #ffffff;
            border: 1px solid #d4dae3;
            border-radius: 0.65rem;
            transition: 0.2s ease;
        }

        .category-edit-type-label:hover {
            border-color: #0061f2;
            box-shadow: 0 0.15rem 0.75rem rgba(0, 97, 242, 0.08);
        }

        .category-edit-type-option input:checked+.category-edit-type-label {
            background-color: rgba(0, 97, 242, 0.05);
            border-color: #0061f2;
            box-shadow: 0 0 0 0.15rem rgba(0, 97, 242, 0.12);
        }

        .category-edit-type-icon {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            margin-right: 0.85rem;
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
            border-radius: 0.6rem;
        }

        .category-edit-type-icon svg {
            width: 21px;
            height: 21px;
        }

        .category-edit-type-beverage .category-edit-type-icon {
            color: #6900c7;
            background-color: rgba(105, 0, 199, 0.1);
        }

        .category-edit-type-name {
            margin-bottom: 0.15rem;
            color: #363d47;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .category-edit-type-description {
            margin: 0;
            color: #69707a;
            font-size: 0.74rem;
            line-height: 1.45;
        }

        .category-edit-image-upload {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px dashed #b8c2cc;
            border-radius: 0.65rem;
            transition: 0.2s ease;
        }

        .category-edit-image-upload:hover {
            background-color: #f1f5f9;
            border-color: #0061f2;
        }

        .category-edit-image-preview-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 210px;
            margin-bottom: 1rem;
            overflow: hidden;
            background-color: #eef2f6;
            border-radius: 0.6rem;
        }

        .category-edit-image-preview {
            width: 100%;
            height: 210px;
            object-fit: cover;
        }

        .category-edit-image-placeholder {
            padding: 1.5rem;
            color: #69707a;
            text-align: center;
        }

        .category-edit-image-placeholder-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            margin: 0 auto 0.75rem;
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
            border-radius: 50%;
        }

        .category-edit-image-placeholder-icon svg {
            width: 24px;
            height: 24px;
        }

        .category-edit-image-placeholder-title {
            margin-bottom: 0.2rem;
            color: #363d47;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .category-edit-image-placeholder-text {
            margin: 0;
            font-size: 0.72rem;
        }

        .category-edit-status-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .category-edit-status-title {
            margin-bottom: 0.15rem;
            color: #363d47;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .category-edit-status-description {
            margin: 0;
            color: #69707a;
            font-size: 0.74rem;
        }

        .category-edit-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
            margin-top: 1.5rem;
            border-top: 1px solid #e0e5ec;
        }

        .category-edit-information {
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .category-edit-information-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 0;
            border-bottom: 1px solid #e0e5ec;
        }

        .category-edit-information-item:first-child {
            padding-top: 0;
        }

        .category-edit-information-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .category-edit-information-label {
            color: #69707a;
            font-size: 0.75rem;
        }

        .category-edit-information-value {
            color: #363d47;
            font-size: 0.78rem;
            font-weight: 700;
            text-align: right;
            word-break: break-word;
        }

        @media (max-width: 767.98px) {
            .category-edit-card-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .category-edit-status-box {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .category-edit-footer {
                align-items: stretch;
                flex-direction: column-reverse;
            }

            .category-edit-footer .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $categoryImageUrl = $category->image ? asset('storage/' . $category->image) : null;
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i data-feather="alert-triangle" class="me-2 mt-1"></i>

                <div>
                    <strong>Data kategori belum valid.</strong>

                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Tutup">
            </button>
        </div>
    @endif

    <form id="categoryEditForm" action="{{ route('management.categories.update', $category) }}" method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <div class="card category-edit-card mb-4">
                    <div class="category-edit-card-header">
                        <h2 class="category-edit-card-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Kategori
                        </h2>

                        @if ($category->is_active)
                            <span class="badge bg-success-soft text-success">
                                Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary-soft text-secondary">
                                Tidak Aktif
                            </span>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="category-edit-section">
                            <div class="category-edit-heading">
                                <div class="category-edit-number">
                                    1
                                </div>

                                <div>
                                    <h3 class="category-edit-title">
                                        Informasi Utama
                                    </h3>

                                    <p class="category-edit-subtitle">
                                        Perbarui nama dan deskripsi kategori.
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="name">
                                    Nama Kategori
                                    <span class="category-edit-required">*</span>
                                </label>

                                <input class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" type="text" value="{{ old('name', $category->name) }}" maxlength="100"
                                    placeholder="Contoh: Main Course" autofocus>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Slug URL akan diperbarui otomatis jika nama berubah.
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="small mb-1" for="description">
                                    Deskripsi
                                </label>

                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" maxlength="2000" placeholder="Jelaskan isi atau kegunaan kategori ini...">{{ old('description', $category->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Maksimal 2.000 karakter.
                                    <span id="descriptionCounter">
                                        {{ strlen(old('description', $category->description ?? '')) }}
                                    </span>/2000
                                </div>
                            </div>
                        </div>

                        <div class="category-edit-section">
                            <div class="category-edit-heading">
                                <div class="category-edit-number">
                                    2
                                </div>

                                <div>
                                    <h3 class="category-edit-title">
                                        Jenis Menu
                                    </h3>

                                    <p class="category-edit-subtitle">
                                        Tentukan kategori makanan atau minuman.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="category-edit-type-option">
                                        <input id="menuTypeFood" name="menu_type" type="radio" value="food"
                                            @checked(old('menu_type', $category->menu_type) === 'food')>

                                        <label class="category-edit-type-label" for="menuTypeFood">

                                            <span class="category-edit-type-icon">
                                                <i data-feather="coffee"></i>
                                            </span>

                                            <span>
                                                <span class="category-edit-type-name d-block">
                                                    Makanan
                                                </span>

                                                <span class="category-edit-type-description d-block">
                                                    Appetizer, main course, dessert,
                                                    dan menu makanan lainnya.
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="category-edit-type-option category-edit-type-beverage">
                                        <input id="menuTypeBeverage" name="menu_type" type="radio" value="beverage"
                                            @checked(old('menu_type', $category->menu_type) === 'beverage')>

                                        <label class="category-edit-type-label" for="menuTypeBeverage">

                                            <span class="category-edit-type-icon">
                                                <i data-feather="droplet"></i>
                                            </span>

                                            <span>
                                                <span class="category-edit-type-name d-block">
                                                    Minuman
                                                </span>

                                                <span class="category-edit-type-description d-block">
                                                    Kopi, non-kopi, minuman beralkohol,
                                                    dan non-alkohol.
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            @error('menu_type')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="category-edit-section">
                            <div class="category-edit-heading">
                                <div class="category-edit-number">
                                    3
                                </div>

                                <div>
                                    <h3 class="category-edit-title">
                                        Pengaturan Tampilan
                                    </h3>

                                    <p class="category-edit-subtitle">
                                        Atur urutan dan status kategori.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <label class="small mb-1" for="sort_order">
                                        Urutan Tampilan
                                        <span class="category-edit-required">*</span>
                                    </label>

                                    <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order"
                                        name="sort_order" type="number" min="0" step="1"
                                        value="{{ old('sort_order', $category->sort_order) }}">

                                    @error('sort_order')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="form-text">
                                        Nilai lebih kecil ditampilkan lebih dahulu.
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <label class="small mb-1">
                                        Status Kategori
                                    </label>

                                    <div class="category-edit-status-box">
                                        <div>
                                            <div class="category-edit-status-title">
                                                Aktifkan kategori
                                            </div>

                                            <p class="category-edit-status-description">
                                                Kategori aktif dapat ditampilkan kepada customer.
                                            </p>
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="is_active" name="is_active"
                                                type="checkbox" value="1" @checked(old('is_active', $category->is_active))>

                                            <label class="form-check-label" for="is_active">
                                                Aktif
                                            </label>
                                        </div>
                                    </div>

                                    @error('is_active')
                                        <div class="text-danger small mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="category-edit-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.categories.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="updateCategoryButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card category-edit-card mb-4">
                    <div class="category-edit-card-header">
                        <h2 class="category-edit-card-title">
                            <i data-feather="image" class="me-2"></i>
                            Gambar Kategori
                        </h2>
                    </div>

                    <div class="card-body">
                        <div class="category-edit-image-upload">
                            <div class="category-edit-image-preview-wrapper">
                                <img class="category-edit-image-preview" id="categoryImagePreview"
                                    src="{{ $categoryImageUrl ?: '' }}" alt="Preview gambar {{ $category->name }}"
                                    style="{{ $categoryImageUrl ? '' : 'display: none;' }}">

                                <div class="category-edit-image-placeholder" id="categoryImagePlaceholder"
                                    style="{{ $categoryImageUrl ? 'display: none;' : '' }}">

                                    <div class="category-edit-image-placeholder-icon">
                                        <i data-feather="image"></i>
                                    </div>

                                    <div class="category-edit-image-placeholder-title">
                                        Belum ada gambar
                                    </div>

                                    <p class="category-edit-image-placeholder-text">
                                        Preview gambar akan ditampilkan di sini.
                                    </p>
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
                                Kosongkan jika tidak ingin mengganti gambar.
                                Maksimal 3 MB.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card category-edit-card mb-4">
                    <div class="category-edit-card-header">
                        <h2 class="category-edit-card-title">
                            <i data-feather="info" class="me-2"></i>
                            Informasi Data
                        </h2>
                    </div>

                    <div class="card-body">
                        <div class="category-edit-information">
                            <div class="category-edit-information-item">
                                <span class="category-edit-information-label">
                                    ID Kategori
                                </span>

                                <span class="category-edit-information-value">
                                    #{{ $category->id }}
                                </span>
                            </div>

                            <div class="category-edit-information-item">
                                <span class="category-edit-information-label">
                                    Slug
                                </span>

                                <span class="category-edit-information-value">
                                    {{ $category->slug }}
                                </span>
                            </div>

                            <div class="category-edit-information-item">
                                <span class="category-edit-information-label">
                                    Dibuat
                                </span>

                                <span class="category-edit-information-value">
                                    {{ $category->created_at?->translatedFormat('d M Y H:i') ?? '-' }}
                                </span>
                            </div>

                            <div class="category-edit-information-item">
                                <span class="category-edit-information-label">
                                    Terakhir diperbarui
                                </span>

                                <span class="category-edit-information-value">
                                    {{ $category->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}
                                </span>
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
            const form = document.getElementById('categoryEditForm');
            const updateButton = document.getElementById(
                'updateCategoryButton'
            );
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById(
                'categoryImagePreview'
            );
            const imagePlaceholder = document.getElementById(
                'categoryImagePlaceholder'
            );
            const descriptionInput = document.getElementById('description');
            const descriptionCounter = document.getElementById(
                'descriptionCounter'
            );
            const originalImageUrl = @json($categoryImageUrl);

            descriptionInput?.addEventListener('input', function() {
                descriptionCounter.textContent =
                    descriptionInput.value.length;
            });

            imageInput?.addEventListener('change', function(event) {
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

                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format gambar tidak valid',
                        text: 'Gunakan gambar JPG, JPEG, PNG, atau WEBP.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });

                    imageInput.value = '';
                    restoreOriginalImage();

                    return;
                }

                const maximumFileSize = 3 * 1024 * 1024;

                if (file.size > maximumFileSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran gambar terlalu besar',
                        text: 'Ukuran gambar maksimal adalah 3 MB.',
                        confirmButtonText: 'OK',
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

            form?.addEventListener('submit', function() {
                updateButton.disabled = true;
                updateButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            function restoreOriginalImage() {
                if (originalImageUrl) {
                    imagePreview.src = originalImageUrl;
                    imagePreview.style.display = 'block';
                    imagePlaceholder.style.display = 'none';

                    return;
                }

                imagePreview.src = '';
                imagePreview.style.display = 'none';
                imagePlaceholder.style.display = 'block';
            }

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Data kategori belum valid',
                    text: @json($errors->first()),
                    confirmButtonText: 'Periksa Kembali',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>
@endpush
