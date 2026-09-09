@extends('admin.layouts.main')

@section('page_title', 'Paket Menu || ' . config('app.name'))

@section('meta_description', 'Kelola paket dan bundling menu restoran.')

@section('header_title', 'Paket Menu')

@section('header_subtitle', 'Kelola paket makanan, bundling menu, harga paket, dan masa ketersediaannya.')

@section('header_icon', 'package')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.menu-packages.create') }}">
        <i data-feather="plus" class="me-1"></i>
        Tambah Paket
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Paket Menu
    </li>
@endsection

@push('style')
    <style>
        .package-list-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .package-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .package-list-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .package-list-title svg {
            width: 18px;
            height: 18px;
        }

        .package-filter {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .package-filter-label {
            margin-bottom: 0.4rem;
            color: #69707a;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .package-table {
            margin-bottom: 0;
        }

        .package-table thead th {
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

        .package-table tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #edf0f4;
            vertical-align: middle;
        }

        .package-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .package-image {
            width: 68px;
            height: 68px;
            object-fit: cover;
            background-color: #eef2f6;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .package-image-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 68px;
            height: 68px;
            color: #69707a;
            background-color: #eef2f6;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .package-name {
            margin-bottom: 0.15rem;
            color: #1f2d3d;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .package-sku {
            color: #69707a;
            font-size: 0.72rem;
        }

        .package-price {
            color: #0061f2;
            font-size: 0.88rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .package-normal-price {
            color: #69707a;
            font-size: 0.72rem;
            text-decoration: line-through;
            white-space: nowrap;
        }

        .package-saving {
            margin-top: 0.2rem;
            color: #008a56;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .package-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            border-radius: 2rem;
            font-size: 0.68rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .package-badge-available {
            color: #008a56;
            background-color: rgba(0, 172, 105, 0.12);
        }

        .package-badge-unavailable {
            color: #69707a;
            background-color: rgba(105, 112, 122, 0.12);
        }

        .package-badge-featured {
            color: #b27400;
            background-color: rgba(244, 161, 0, 0.15);
        }

        .package-component-count {
            display: inline-flex;
            align-items: center;
            color: #6900c7;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .package-period {
            color: #69707a;
            font-size: 0.74rem;
            line-height: 1.55;
            white-space: nowrap;
        }

        .package-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.4rem;
        }

        .package-action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 0.45rem;
        }

        .package-action-button svg {
            width: 15px;
            height: 15px;
        }

        .package-empty {
            padding: 4rem 1.5rem;
            text-align: center;
        }

        .package-empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 66px;
            height: 66px;
            margin: 0 auto 1rem;
            color: #6900c7;
            background-color: rgba(105, 0, 199, 0.1);
            border-radius: 50%;
        }

        .package-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-top: 1px solid #e0e5ec;
        }

        .package-pagination-text {
            color: #69707a;
            font-size: 0.78rem;
        }

        .package-pagination .pagination {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {

            .package-list-header,
            .package-pagination {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .package-pagination nav {
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

    <div class="card package-list-card mb-4">
        <div class="package-list-header">
            <h2 class="package-list-title">
                <i data-feather="filter" class="me-2"></i>
                Pencarian dan Filter
            </h2>

            @if (request()->filled('search') || request()->filled('availability'))
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('management.menu-packages.index') }}">
                    <i data-feather="x" class="me-1"></i>
                    Hapus Filter
                </a>
            @endif
        </div>

        <div class="card-body">
            <form action="{{ route('management.menu-packages.index') }}" method="GET">

                <div class="package-filter">
                    <div class="row gx-3 align-items-end">
                        <div class="col-lg-7 mb-3 mb-lg-0">
                            <label class="package-filter-label" for="search">
                                Cari Paket
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search"></i>
                                </span>

                                <input class="form-control" id="search" name="search" type="text"
                                    value="{{ $search }}" placeholder="Cari nama atau SKU paket...">
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3 mb-lg-0">
                            <label class="package-filter-label" for="availability">
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

    <div class="card package-list-card mb-4">
        <div class="package-list-header">
            <h2 class="package-list-title">
                <i data-feather="package" class="me-2"></i>
                Daftar Paket
            </h2>

            <span class="badge bg-primary-soft text-primary">
                {{ $menuPackages->total() }} paket
            </span>
        </div>

        @if ($menuPackages->count() > 0)
            <div class="table-responsive">
                <table class="table package-table">
                    <thead>
                        <tr>
                            <th style="width: 85px;">Gambar</th>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Isi Paket</th>
                            <th>Masa Tersedia</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($menuPackages as $menuPackage)
                            @php
                                $saving = max(
                                    0,
                                    (float) $menuPackage->normal_price - (float) $menuPackage->package_price,
                                );
                            @endphp

                            <tr>
                                <td>
                                    @if ($menuPackage->image)
                                        <img class="package-image" src="{{ asset('storage/' . $menuPackage->image) }}"
                                            alt="{{ $menuPackage->name }}">
                                    @else
                                        <div class="package-image-empty">
                                            <i data-feather="image"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="package-name">
                                        {{ $menuPackage->name }}
                                    </div>

                                    <div class="package-sku">
                                        SKU: {{ $menuPackage->sku }}
                                    </div>

                                    <div class="text-muted small mt-1">
                                        {{ $menuPackage->serving_count }} orang
                                        · Minimal {{ $menuPackage->minimum_order }}
                                    </div>

                                    @if ($menuPackage->is_featured)
                                        <span class="package-badge package-badge-featured mt-1">
                                            <i data-feather="star" class="me-1"></i>
                                            Unggulan
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="package-normal-price">
                                        Rp{{ number_format($menuPackage->normal_price, 0, ',', '.') }}
                                    </div>

                                    <div class="package-price">
                                        Rp{{ number_format($menuPackage->package_price, 0, ',', '.') }}
                                    </div>

                                    @if ($saving > 0)
                                        <div class="package-saving">
                                            Hemat Rp{{ number_format($saving, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="package-component-count">
                                        <i data-feather="layers" class="me-1"></i>
                                        {{ $menuPackage->items_count }} komponen
                                    </span>
                                </td>

                                <td>
                                    <div class="package-period">
                                        <div>
                                            <strong>Mulai:</strong>
                                            {{ $menuPackage->available_from
                                                ? \Illuminate\Support\Carbon::parse($menuPackage->available_from)->format('d/m/Y H:i')
                                                : 'Tanpa batas' }}
                                        </div>

                                        <div>
                                            <strong>Selesai:</strong>
                                            {{ $menuPackage->available_until
                                                ? \Illuminate\Support\Carbon::parse($menuPackage->available_until)->format('d/m/Y H:i')
                                                : 'Tanpa batas' }}
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if ($menuPackage->is_available)
                                        <span class="package-badge package-badge-available">
                                            <i data-feather="check-circle" class="me-1"></i>
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="package-badge package-badge-unavailable">
                                            <i data-feather="slash" class="me-1"></i>
                                            Tidak Tersedia
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="package-actions">
                                        <form action="{{ route('management.menu-packages.availability', $menuPackage) }}"
                                            method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                class="btn {{ $menuPackage->is_available ? 'btn-outline-warning' : 'btn-outline-success' }} package-action-button"
                                                type="submit" data-bs-toggle="tooltip"
                                                title="{{ $menuPackage->is_available ? 'Nonaktifkan paket' : 'Aktifkan paket' }}">
                                                <i
                                                    data-feather="{{ $menuPackage->is_available ? 'eye-off' : 'eye' }}"></i>
                                            </button>
                                        </form>

                                        <a class="btn btn-outline-primary package-action-button"
                                            href="{{ route('management.menu-packages.edit', $menuPackage) }}"
                                            data-bs-toggle="tooltip" title="Edit paket">
                                            <i data-feather="edit-2"></i>
                                        </a>

                                        <form id="deletePackageForm{{ $menuPackage->id }}"
                                            action="{{ route('management.menu-packages.destroy', $menuPackage) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-outline-danger package-action-button" type="button"
                                                onclick="confirmDeletePackage(
                                                    {{ $menuPackage->id }},
                                                    @js($menuPackage->name)
                                                )"
                                                data-bs-toggle="tooltip" title="Hapus paket">
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

            <div class="package-pagination">
                <div class="package-pagination-text">
                    Menampilkan
                    <strong>{{ $menuPackages->firstItem() }}</strong>
                    sampai
                    <strong>{{ $menuPackages->lastItem() }}</strong>
                    dari
                    <strong>{{ $menuPackages->total() }}</strong>
                    paket
                </div>

                <div>
                    {{ $menuPackages->links() }}
                </div>
            </div>
        @else
            <div class="package-empty">
                <div class="package-empty-icon">
                    <i data-feather="package"></i>
                </div>

                <h3 class="h6 fw-bold">
                    Paket menu belum tersedia
                </h3>

                <p class="text-muted small">
                    Tambahkan paket atau bundling dari menu yang sudah tersedia.
                </p>

                <a class="btn btn-primary" href="{{ route('management.menu-packages.create') }}">
                    <i data-feather="plus" class="me-1"></i>
                    Tambah Paket
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

        function confirmDeletePackage(packageId, packageName) {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus paket menu?',
                html: 'Paket <strong>' + escapePackageHtml(packageName) +
                    '</strong> akan dihapus.',
                text: 'Komponen paket juga akan dihapus, tetapi riwayat order tetap tersimpan.',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    document
                        .getElementById('deletePackageForm' + packageId)
                        .submit();
                }
            });
        }

        function escapePackageHtml(value) {
            const element = document.createElement('div');
            element.textContent = value;

            return element.innerHTML;
        }
    </script>
@endpush
