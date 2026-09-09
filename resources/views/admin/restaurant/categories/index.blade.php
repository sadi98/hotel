@extends('admin.layouts.main')

@section('page_title', 'Kategori Menu || ' . config('app.name'))

@section('meta_description', 'Kelola kategori makanan dan minuman restoran.')

@section('header_title', 'Kategori Menu')

@section('header_subtitle', 'Kelola kategori makanan dan minuman yang ditampilkan kepada customer.')

@section('header_icon', 'grid')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.categories.create') }}">
        <i data-feather="plus" class="me-1"></i>
        Tambah Kategori
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Kategori Menu
    </li>
@endsection

@push('style')
    <style>
        .category-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .category-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .category-card-header-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .category-card-header-title svg {
            width: 18px;
            height: 18px;
        }

        .category-filter {
            padding: 1rem;
            margin-bottom: 1.25rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .category-filter-label {
            margin-bottom: 0.4rem;
            color: #69707a;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .category-table {
            margin-bottom: 0;
        }

        .category-table thead th {
            padding: 0.85rem 1rem;
            color: #69707a;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e5ec;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .category-table tbody td {
            padding: 0.9rem 1rem;
            color: #363d47;
            border-bottom: 1px solid #edf0f4;
            vertical-align: middle;
        }

        .category-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .category-table tbody tr:hover {
            background-color: rgba(0, 97, 242, 0.025);
        }

        .category-image {
            width: 54px;
            height: 54px;
            object-fit: cover;
            background-color: #eef2f6;
            border: 1px solid #e0e5ec;
            border-radius: 0.6rem;
        }

        .category-image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            color: #69707a;
            background-color: #eef2f6;
            border: 1px solid #e0e5ec;
            border-radius: 0.6rem;
        }

        .category-image-placeholder svg {
            width: 20px;
            height: 20px;
        }

        .category-name {
            margin-bottom: 0.15rem;
            color: #1f2d3d;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .category-slug {
            color: #69707a;
            font-size: 0.75rem;
        }

        .category-description {
            display: -webkit-box;
            max-width: 320px;
            overflow: hidden;
            color: #69707a;
            font-size: 0.8rem;
            line-height: 1.5;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .category-type-badge,
        .category-status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.38rem 0.7rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .category-type-food {
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
        }

        .category-type-beverage {
            color: #6900c7;
            background-color: rgba(105, 0, 199, 0.1);
        }

        .category-status-active {
            color: #008a56;
            background-color: rgba(0, 172, 105, 0.12);
        }

        .category-status-inactive {
            color: #69707a;
            background-color: rgba(105, 112, 122, 0.12);
        }

        .category-action-group {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.4rem;
        }

        .category-action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 0.45rem;
        }

        .category-action-button svg {
            width: 15px;
            height: 15px;
        }

        .category-empty {
            padding: 4rem 1.5rem;
            text-align: center;
        }

        .category-empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
            border-radius: 50%;
        }

        .category-empty-icon svg {
            width: 28px;
            height: 28px;
        }

        .category-empty-title {
            margin-bottom: 0.4rem;
            color: #363d47;
            font-size: 1rem;
            font-weight: 700;
        }

        .category-empty-text {
            max-width: 420px;
            margin: 0 auto 1.25rem;
            color: #69707a;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .category-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-top: 1px solid #e0e5ec;
        }

        .category-pagination-information {
            color: #69707a;
            font-size: 0.78rem;
        }

        .category-pagination .pagination {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {
            .category-card-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .category-filter .btn {
                width: 100%;
            }

            .category-pagination {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .category-pagination nav {
                width: 100%;
                overflow-x: auto;
            }
        }
    </style>
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i data-feather="check-circle" class="me-2"></i>

                <div>
                    <strong>Berhasil!</strong>
                    {{ session('success') }}
                </div>
            </div>

            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Tutup">
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i data-feather="alert-circle" class="me-2"></i>

                <div>
                    <strong>Gagal!</strong>
                    {{ session('error') }}
                </div>
            </div>

            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Tutup">
            </button>
        </div>
    @endif

    <div class="card category-card mb-4">
        <div class="category-card-header">
            <h2 class="category-card-header-title">
                <i data-feather="filter" class="me-2"></i>
                Pencarian dan Filter
            </h2>

            @if (request()->filled('search') || request()->filled('menu_type'))
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('management.categories.index') }}">
                    <i data-feather="x" class="me-1"></i>
                    Hapus Filter
                </a>
            @endif
        </div>

        <div class="card-body">
            <form action="{{ route('management.categories.index') }}" method="GET">

                <div class="category-filter">
                    <div class="row gx-3 align-items-end">
                        <div class="col-lg-7 mb-3 mb-lg-0">
                            <label class="category-filter-label" for="search">
                                Cari Kategori
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search"></i>
                                </span>

                                <input class="form-control" id="search" name="search" type="text"
                                    value="{{ $search }}" placeholder="Cari nama atau deskripsi kategori...">
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3 mb-lg-0">
                            <label class="category-filter-label" for="menu_type">
                                Jenis Menu
                            </label>

                            <select class="form-select" id="menu_type" name="menu_type">

                                <option value="">
                                    Semua jenis
                                </option>

                                <option value="food" @selected($menuType === 'food')>
                                    Makanan
                                </option>

                                <option value="beverage" @selected($menuType === 'beverage')>
                                    Minuman
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <button class="btn btn-primary w-100" type="submit">
                                <i data-feather="search" class="me-1"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card category-card mb-4">
        <div class="category-card-header">
            <h2 class="category-card-header-title">
                <i data-feather="list" class="me-2"></i>
                Daftar Kategori
            </h2>

            <span class="badge bg-primary-soft text-primary">
                {{ $categories->total() }} kategori
            </span>
        </div>

        @if ($categories->count() > 0)
            <div class="table-responsive">
                <table class="table category-table">
                    <thead>
                        <tr>
                            <th style="width: 75px;">
                                Gambar
                            </th>

                            <th>
                                Kategori
                            </th>

                            <th>
                                Jenis
                            </th>

                            <th>
                                Deskripsi
                            </th>

                            <th class="text-center">
                                Urutan
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    @if ($category->image)
                                        <img class="category-image" src="{{ asset('storage/' . $category->image) }}"
                                            alt="Gambar {{ $category->name }}">
                                    @else
                                        <div class="category-image-placeholder">
                                            <i data-feather="image"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="category-name">
                                        {{ $category->name }}
                                    </div>

                                    <div class="category-slug">
                                        /{{ $category->slug }}
                                    </div>
                                </td>

                                <td>
                                    @if ($category->menu_type === 'food')
                                        <span class="category-type-badge category-type-food">
                                            <i data-feather="coffee" class="me-1"></i>
                                            Makanan
                                        </span>
                                    @else
                                        <span class="category-type-badge category-type-beverage">
                                            <i data-feather="droplet" class="me-1"></i>
                                            Minuman
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="category-description">
                                        {{ $category->description ?: 'Tidak ada deskripsi.' }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-light text-dark">
                                        {{ $category->sort_order }}
                                    </span>
                                </td>

                                <td>
                                    @if ($category->is_active)
                                        <span class="category-status-badge category-status-active">
                                            <i data-feather="check-circle" class="me-1"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="category-status-badge category-status-inactive">
                                            <i data-feather="slash" class="me-1"></i>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="category-action-group">
                                        <a class="btn btn-outline-primary category-action-button"
                                            href="{{ route('management.categories.edit', $category) }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit kategori">
                                            <i data-feather="edit-2"></i>
                                        </a>

                                        <form id="deleteCategoryForm{{ $category->id }}"
                                            action="{{ route('management.categories.destroy', $category) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-outline-danger category-action-button" type="button"
                                                onclick="confirmDeleteCategory(
                                                    {{ $category->id }},
                                                    @js($category->name)
                                                )"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus kategori">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="category-pagination">
                <div class="category-pagination-information">
                    Menampilkan
                    <strong>{{ $categories->firstItem() }}</strong>
                    sampai
                    <strong>{{ $categories->lastItem() }}</strong>
                    dari
                    <strong>{{ $categories->total() }}</strong>
                    kategori
                </div>

                <div>
                    {{ $categories->links() }}
                </div>
            </div>
        @else
            <div class="category-empty">
                <div class="category-empty-icon">
                    <i data-feather="grid"></i>
                </div>

                <h3 class="category-empty-title">
                    Kategori belum tersedia
                </h3>

                <p class="category-empty-text">
                    @if (request()->filled('search') || request()->filled('menu_type'))
                        Tidak ada kategori yang sesuai dengan pencarian atau
                        filter yang digunakan.
                    @else
                        Tambahkan kategori makanan atau minuman untuk mulai
                        mengelompokkan daftar menu restoran.
                    @endif
                </p>

                @if (request()->filled('search') || request()->filled('menu_type'))
                    <a class="btn btn-outline-primary" href="{{ route('management.categories.index') }}">
                        <i data-feather="rotate-ccw" class="me-1"></i>
                        Tampilkan Semua
                    </a>
                @else
                    <a class="btn btn-primary" href="{{ route('management.categories.create') }}">
                        <i data-feather="plus" class="me-1"></i>
                        Tambah Kategori
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipElements = document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'
            );

            tooltipElements.forEach(function(element) {
                new bootstrap.Tooltip(element);
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0061f2'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });

        function confirmDeleteCategory(categoryId, categoryName) {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus kategori?',
                html: 'Kategori <strong>' + escapeCategoryHtml(categoryName) +
                    '</strong> akan dihapus.',
                text: 'Kategori yang masih digunakan oleh menu tidak dapat dihapus.',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    document
                        .getElementById('deleteCategoryForm' + categoryId)
                        .submit();
                }
            });
        }

        function escapeCategoryHtml(value) {
            const temporaryElement = document.createElement('div');
            temporaryElement.textContent = value;

            return temporaryElement.innerHTML;
        }
    </script>
@endpush
