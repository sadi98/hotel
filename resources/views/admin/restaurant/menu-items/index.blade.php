@extends('admin.layouts.main')

@section('page_title', 'Daftar Menu || ' . config('app.name'))

@section('meta_description', 'Kelola daftar makanan dan minuman restoran.')

@section('header_title', 'Daftar Menu')

@section('header_subtitle', 'Kelola makanan, minuman, harga, kategori, dan ketersediaan menu.')

@section('header_icon', 'book-open')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.menu-items.create') }}">
        <i data-feather="plus" class="me-1"></i>
        Tambah Menu
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Daftar Menu
    </li>
@endsection

@push('style')
    <style>
        .menu-list-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .menu-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .menu-list-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .menu-list-title svg {
            width: 18px;
            height: 18px;
        }

        .menu-filter-area {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .menu-filter-label {
            margin-bottom: 0.4rem;
            color: #69707a;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .menu-list-table {
            margin-bottom: 0;
        }

        .menu-list-table thead th {
            padding: 0.85rem 1rem;
            color: #69707a;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e5ec;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .menu-list-table tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #edf0f4;
            vertical-align: middle;
        }

        .menu-list-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .menu-list-table tbody tr:hover {
            background-color: rgba(0, 97, 242, 0.025);
        }

        .menu-list-image {
            width: 62px;
            height: 62px;
            object-fit: cover;
            background-color: #eef2f6;
            border: 1px solid #e0e5ec;
            border-radius: 0.6rem;
        }

        .menu-list-image-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 62px;
            height: 62px;
            color: #69707a;
            background-color: #eef2f6;
            border: 1px solid #e0e5ec;
            border-radius: 0.6rem;
        }

        .menu-list-image-empty svg {
            width: 22px;
            height: 22px;
        }

        .menu-list-name {
            margin-bottom: 0.15rem;
            color: #1f2d3d;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .menu-list-sku {
            color: #69707a;
            font-size: 0.72rem;
        }

        .menu-list-price {
            color: #0061f2;
            font-size: 0.86rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .menu-list-description {
            display: -webkit-box;
            max-width: 250px;
            overflow: hidden;
            color: #69707a;
            font-size: 0.76rem;
            line-height: 1.45;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .menu-list-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            border-radius: 2rem;
            font-size: 0.68rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .menu-list-badge-category {
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
        }

        .menu-list-badge-food {
            color: #008a56;
            background-color: rgba(0, 172, 105, 0.12);
        }

        .menu-list-badge-beverage {
            color: #6900c7;
            background-color: rgba(105, 0, 199, 0.1);
        }

        .menu-list-badge-available {
            color: #008a56;
            background-color: rgba(0, 172, 105, 0.12);
        }

        .menu-list-badge-unavailable {
            color: #69707a;
            background-color: rgba(105, 112, 122, 0.12);
        }

        .menu-list-featured {
            color: #b27400;
            background-color: rgba(244, 161, 0, 0.15);
        }

        .menu-list-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.4rem;
        }

        .menu-list-action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 0.45rem;
        }

        .menu-list-action-button svg {
            width: 15px;
            height: 15px;
        }

        .menu-list-empty {
            padding: 4rem 1.5rem;
            text-align: center;
        }

        .menu-list-empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 66px;
            height: 66px;
            margin: 0 auto 1rem;
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
            border-radius: 50%;
        }

        .menu-list-empty-icon svg {
            width: 28px;
            height: 28px;
        }

        .menu-list-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-top: 1px solid #e0e5ec;
        }

        .menu-list-pagination-text {
            color: #69707a;
            font-size: 0.78rem;
        }

        .menu-list-pagination .pagination {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {
            .menu-list-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .menu-list-pagination {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .menu-list-pagination nav {
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

    <div class="card menu-list-card mb-4">
        <div class="menu-list-header">
            <h2 class="menu-list-title">
                <i data-feather="filter" class="me-2"></i>
                Pencarian dan Filter
            </h2>

            @if (request()->filled('search') || request()->filled('category_id') || request()->filled('availability'))
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('management.menu-items.index') }}">
                    <i data-feather="x" class="me-1"></i>
                    Hapus Filter
                </a>
            @endif
        </div>

        <div class="card-body">
            <form action="{{ route('management.menu-items.index') }}" method="GET">

                <div class="menu-filter-area">
                    <div class="row gx-3 align-items-end">
                        <div class="col-xl-5 col-md-6 mb-3 mb-xl-0">
                            <label class="menu-filter-label" for="search">
                                Cari Menu
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search"></i>
                                </span>

                                <input class="form-control" id="search" name="search" type="text"
                                    value="{{ $search }}" placeholder="Nama atau SKU menu...">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <label class="menu-filter-label" for="category_id">
                                Kategori
                            </label>

                            <select class="form-select" id="category_id" name="category_id">

                                <option value="">
                                    Semua kategori
                                </option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>
                                        {{ $category->name }}
                                        ({{ $category->menu_type === 'food' ? 'Makanan' : 'Minuman' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-6 mb-3 mb-md-0">
                            <label class="menu-filter-label" for="availability">
                                Ketersediaan
                            </label>

                            <select class="form-select" id="availability" name="availability">

                                <option value="">
                                    Semua status
                                </option>

                                <option value="available" @selected($availability === 'available')>
                                    Tersedia
                                </option>

                                <option value="unavailable" @selected($availability === 'unavailable')>
                                    Tidak tersedia
                                </option>
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-6">
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

    <div class="card menu-list-card mb-4">
        <div class="menu-list-header">
            <h2 class="menu-list-title">
                <i data-feather="book-open" class="me-2"></i>
                Data Menu
            </h2>

            <span class="badge bg-primary-soft text-primary">
                {{ $menuItems->total() }} menu
            </span>
        </div>

        @if ($menuItems->count() > 0)
            <div class="table-responsive">
                <table class="table menu-list-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">
                                Gambar
                            </th>

                            <th>
                                Menu
                            </th>

                            <th>
                                Kategori
                            </th>

                            <th>
                                Harga
                            </th>

                            <th>
                                Informasi
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
                        @foreach ($menuItems as $menuItem)
                            <tr>
                                <td>
                                    @if ($menuItem->image)
                                        <img class="menu-list-image" src="{{ asset('storage/' . $menuItem->image) }}"
                                            alt="{{ $menuItem->name }}">
                                    @else
                                        <div class="menu-list-image-empty">
                                            <i data-feather="image"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="menu-list-name">
                                        {{ $menuItem->name }}
                                    </div>

                                    <div class="menu-list-sku">
                                        SKU: {{ $menuItem->sku }}
                                    </div>

                                    @if ($menuItem->is_featured)
                                        <span class="menu-list-badge menu-list-featured mt-1">
                                            <i data-feather="star" class="me-1"></i>
                                            Unggulan
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="mb-1">
                                        <span class="menu-list-badge menu-list-badge-category">
                                            {{ $menuItem->category?->name ?? 'Tanpa kategori' }}
                                        </span>
                                    </div>

                                    @if ($menuItem->category?->menu_type === 'food')
                                        <span class="menu-list-badge menu-list-badge-food">
                                            Makanan
                                        </span>
                                    @else
                                        <span class="menu-list-badge menu-list-badge-beverage">
                                            {{ $menuItem->beverage_type === 'coffee' ? 'Coffee' : 'Non-Coffee' }}
                                            ·
                                            {{ $menuItem->is_alcoholic ? 'Alkohol' : 'Non-Alkohol' }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="menu-list-price">
                                        Rp{{ number_format($menuItem->price, 0, ',', '.') }}
                                    </div>

                                    <div class="text-muted small mt-1">
                                        {{ $menuItem->preparation_time }} menit
                                    </div>
                                </td>

                                <td>
                                    <div class="menu-list-description">
                                        {{ $menuItem->description ?: 'Tidak ada deskripsi.' }}
                                    </div>

                                    <div class="text-muted small mt-1">
                                        Urutan: {{ $menuItem->sort_order }}
                                    </div>
                                </td>

                                <td>
                                    @if ($menuItem->is_available)
                                        <span class="menu-list-badge menu-list-badge-available">
                                            <i data-feather="check-circle" class="me-1"></i>
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="menu-list-badge menu-list-badge-unavailable">
                                            <i data-feather="slash" class="me-1"></i>
                                            Tidak Tersedia
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="menu-list-actions">
                                        <form action="{{ route('management.menu-items.availability', $menuItem) }}"
                                            method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                class="btn {{ $menuItem->is_available ? 'btn-outline-warning' : 'btn-outline-success' }} menu-list-action-button"
                                                type="submit" data-bs-toggle="tooltip"
                                                title="{{ $menuItem->is_available ? 'Nonaktifkan menu' : 'Aktifkan menu' }}">
                                                <i data-feather="{{ $menuItem->is_available ? 'eye-off' : 'eye' }}"></i>
                                            </button>
                                        </form>

                                        <a class="btn btn-outline-primary menu-list-action-button"
                                            href="{{ route('management.menu-items.edit', $menuItem) }}"
                                            data-bs-toggle="tooltip" title="Edit menu">
                                            <i data-feather="edit-2"></i>
                                        </a>

                                        <form id="deleteMenuForm{{ $menuItem->id }}"
                                            action="{{ route('management.menu-items.destroy', $menuItem) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-outline-danger menu-list-action-button" type="button"
                                                onclick="confirmDeleteMenu(
                                                    {{ $menuItem->id }},
                                                    @js($menuItem->name)
                                                )"
                                                data-bs-toggle="tooltip" title="Hapus menu">
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

            <div class="menu-list-pagination">
                <div class="menu-list-pagination-text">
                    Menampilkan
                    <strong>{{ $menuItems->firstItem() }}</strong>
                    sampai
                    <strong>{{ $menuItems->lastItem() }}</strong>
                    dari
                    <strong>{{ $menuItems->total() }}</strong>
                    menu
                </div>

                <div>
                    {{ $menuItems->links() }}
                </div>
            </div>
        @else
            <div class="menu-list-empty">
                <div class="menu-list-empty-icon">
                    <i data-feather="book-open"></i>
                </div>

                <h3 class="h6 fw-bold">
                    Menu belum tersedia
                </h3>

                <p class="text-muted small">
                    Tidak ada menu yang sesuai atau belum ada menu yang ditambahkan.
                </p>

                <a class="btn btn-primary" href="{{ route('management.menu-items.create') }}">
                    <i data-feather="plus" class="me-1"></i>
                    Tambah Menu
                </a>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document
                .querySelectorAll('[data-bs-toggle="tooltip"]')
                .forEach(function(element) {
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

        function confirmDeleteMenu(menuId, menuName) {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus menu?',
                html: 'Menu <strong>' + escapeMenuHtml(menuName) +
                    '</strong> akan dihapus.',
                text: 'Menu yang masih digunakan pada paket tidak dapat dihapus.',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    document
                        .getElementById('deleteMenuForm' + menuId)
                        .submit();
                }
            });
        }

        function escapeMenuHtml(value) {
            const element = document.createElement('div');
            element.textContent = value;

            return element.innerHTML;
        }
    </script>
@endpush
