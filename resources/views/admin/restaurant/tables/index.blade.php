@extends('admin.layouts.main')

@section('page_title', 'Meja Restoran || ' . config('app.name'))

@section('meta_description', 'Kelola meja, kapasitas, area, dan token QR restoran.')

@section('header_title', 'Meja Restoran')

@section('header_subtitle', 'Kelola nomor meja, area, kapasitas, status, dan token QR setiap meja.')

@section('header_icon', 'layout')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.tables.create') }}">
        <i data-feather="plus" class="me-1"></i>
        Tambah Meja
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Meja Restoran
    </li>
@endsection

@push('style')
    <style>
        .restaurant-table-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .restaurant-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .restaurant-table-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .restaurant-table-title svg {
            width: 18px;
            height: 18px;
        }

        .restaurant-table-filter {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .restaurant-table-filter-label {
            margin-bottom: 0.4rem;
            color: #69707a;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .restaurant-table-list {
            margin-bottom: 0;
        }

        .restaurant-table-list thead th {
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

        .restaurant-table-list tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #edf0f4;
            vertical-align: middle;
        }

        .restaurant-table-list tbody tr:last-child td {
            border-bottom: 0;
        }

        .restaurant-table-number {
            display: flex;
            align-items: center;
        }

        .restaurant-table-number-icon {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            margin-right: 0.75rem;
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
            border-radius: 0.6rem;
        }

        .restaurant-table-number-icon svg {
            width: 20px;
            height: 20px;
        }

        .restaurant-table-number-value {
            margin-bottom: 0.1rem;
            color: #1f2d3d;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .restaurant-table-name {
            color: #69707a;
            font-size: 0.72rem;
        }

        .restaurant-table-area {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            color: #6900c7;
            background-color: rgba(105, 0, 199, 0.1);
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .restaurant-table-capacity {
            display: inline-flex;
            align-items: center;
            color: #363d47;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .restaurant-table-capacity svg {
            width: 15px;
            height: 15px;
        }

        .restaurant-table-token {
            display: flex;
            align-items: center;
            max-width: 190px;
            padding: 0.45rem 0.6rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.45rem;
        }

        .restaurant-table-token-code {
            overflow: hidden;
            color: #69707a;
            font-family: monospace;
            font-size: 0.7rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .restaurant-table-copy {
            flex: 0 0 auto;
            margin-left: 0.4rem;
            color: #0061f2;
            cursor: pointer;
            background: transparent;
            border: 0;
        }

        .restaurant-table-copy svg {
            width: 14px;
            height: 14px;
        }

        .restaurant-table-status {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            border-radius: 2rem;
            font-size: 0.68rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .restaurant-table-status-active {
            color: #008a56;
            background-color: rgba(0, 172, 105, 0.12);
        }

        .restaurant-table-status-inactive {
            color: #69707a;
            background-color: rgba(105, 112, 122, 0.12);
        }

        .restaurant-table-description {
            display: -webkit-box;
            max-width: 240px;
            overflow: hidden;
            color: #69707a;
            font-size: 0.75rem;
            line-height: 1.45;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .restaurant-table-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.4rem;
        }

        .restaurant-table-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 0.45rem;
        }

        .restaurant-table-action svg {
            width: 15px;
            height: 15px;
        }

        .restaurant-table-empty {
            padding: 4rem 1.5rem;
            text-align: center;
        }

        .restaurant-table-empty-icon {
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

        .restaurant-table-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-top: 1px solid #e0e5ec;
        }

        .restaurant-table-pagination-text {
            color: #69707a;
            font-size: 0.78rem;
        }

        .restaurant-table-pagination .pagination {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {

            .restaurant-table-header,
            .restaurant-table-pagination {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .restaurant-table-pagination nav {
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

    <div class="card restaurant-table-card mb-4">
        <div class="restaurant-table-header">
            <h2 class="restaurant-table-title">
                <i data-feather="filter" class="me-2"></i>
                Pencarian dan Filter
            </h2>

            @if (request()->filled('search') || request()->filled('area'))
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('management.tables.index') }}">
                    <i data-feather="x" class="me-1"></i>
                    Hapus Filter
                </a>
            @endif
        </div>

        <div class="card-body">
            <form action="{{ route('management.tables.index') }}" method="GET">

                <div class="restaurant-table-filter">
                    <div class="row gx-3 align-items-end">
                        <div class="col-lg-7 mb-3 mb-lg-0">
                            <label class="restaurant-table-filter-label" for="search">
                                Cari Meja
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search"></i>
                                </span>

                                <input class="form-control" id="search" name="search" type="text"
                                    value="{{ $search }}" placeholder="Cari nomor atau nama meja...">
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3 mb-lg-0">
                            <label class="restaurant-table-filter-label" for="area">
                                Area
                            </label>

                            <select class="form-select" id="area" name="area">

                                <option value="">
                                    Semua area
                                </option>

                                @foreach ($areas as $areaOption)
                                    <option value="{{ $areaOption }}" @selected($area === $areaOption)>
                                        {{ $areaOption }}
                                    </option>
                                @endforeach
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

    <div class="card restaurant-table-card mb-4">
        <div class="restaurant-table-header">
            <h2 class="restaurant-table-title">
                <i data-feather="layout" class="me-2"></i>
                Daftar Meja
            </h2>

            <span class="badge bg-primary-soft text-primary">
                {{ $tables->total() }} meja
            </span>
        </div>

        @if ($tables->count() > 0)
            <div class="table-responsive">
                <table class="table restaurant-table-list">
                    <thead>
                        <tr>
                            <th>Meja</th>
                            <th>Area</th>
                            <th>Kapasitas</th>
                            <th>Token QR</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tables as $restaurantTable)
                            <tr>
                                <td>
                                    <div class="restaurant-table-number">
                                        <div class="restaurant-table-number-icon">
                                            <i data-feather="hash"></i>
                                        </div>

                                        <div>
                                            <div class="restaurant-table-number-value">
                                                {{ $restaurantTable->table_number }}
                                            </div>

                                            <div class="restaurant-table-name">
                                                {{ $restaurantTable->name ?: 'Tanpa nama tambahan' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="restaurant-table-area">
                                        <i data-feather="map-pin" class="me-1"></i>
                                        {{ $restaurantTable->area }}
                                    </span>
                                </td>

                                <td>
                                    <span class="restaurant-table-capacity">
                                        <i data-feather="users" class="me-1"></i>
                                        {{ $restaurantTable->capacity }} orang
                                    </span>
                                </td>

                                <td>
                                    <div class="restaurant-table-token">
                                        <span class="restaurant-table-token-code" id="qrToken{{ $restaurantTable->id }}">
                                            {{ $restaurantTable->qr_token }}
                                        </span>

                                        <button class="restaurant-table-copy" type="button"
                                            onclick="copyQrToken(
                                                {{ $restaurantTable->id }}
                                            )"
                                            title="Salin token QR">
                                            <i data-feather="copy"></i>
                                        </button>
                                    </div>
                                </td>

                                <td>
                                    <div class="restaurant-table-description">
                                        {{ $restaurantTable->description ?: 'Tidak ada keterangan.' }}
                                    </div>
                                </td>

                                <td>
                                    @if ($restaurantTable->is_active)
                                        <span class="restaurant-table-status restaurant-table-status-active">
                                            <i data-feather="check-circle" class="me-1"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="restaurant-table-status restaurant-table-status-inactive">
                                            <i data-feather="slash" class="me-1"></i>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="restaurant-table-actions">
                                        <form action="{{ route('management.tables.status', $restaurantTable) }}"
                                            method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                class="btn {{ $restaurantTable->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} restaurant-table-action"
                                                type="submit" data-bs-toggle="tooltip"
                                                title="{{ $restaurantTable->is_active ? 'Nonaktifkan meja' : 'Aktifkan meja' }}">
                                                <i
                                                    data-feather="{{ $restaurantTable->is_active ? 'eye-off' : 'eye' }}"></i>
                                            </button>
                                        </form>

                                        <button class="btn btn-outline-secondary restaurant-table-action" type="button"
                                            onclick="confirmRegenerateQr(
                                                {{ $restaurantTable->id }},
                                                @js($restaurantTable->table_number)
                                            )"
                                            data-bs-toggle="tooltip" title="Buat ulang token QR">
                                            <i data-feather="refresh-cw"></i>
                                        </button>

                                        <form id="regenerateQrForm{{ $restaurantTable->id }}"
                                            action="{{ route('management.tables.regenerate-qr', $restaurantTable) }}"
                                            method="POST" class="d-none">
                                            @csrf
                                        </form>

                                        <a class="btn btn-outline-primary restaurant-table-action"
                                            href="{{ route('management.tables.edit', $restaurantTable) }}"
                                            data-bs-toggle="tooltip" title="Edit meja">
                                            <i data-feather="edit-2"></i>
                                        </a>

                                        <button class="btn btn-outline-danger restaurant-table-action" type="button"
                                            onclick="confirmDeleteTable(
                                                {{ $restaurantTable->id }},
                                                @js($restaurantTable->table_number)
                                            )"
                                            data-bs-toggle="tooltip" title="Hapus meja">
                                            <i data-feather="trash-2"></i>
                                        </button>

                                        <form id="deleteTableForm{{ $restaurantTable->id }}"
                                            action="{{ route('management.tables.destroy', $restaurantTable) }}"
                                            method="POST" class="d-none">

                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="restaurant-table-pagination">
                <div class="restaurant-table-pagination-text">
                    Menampilkan
                    <strong>{{ $tables->firstItem() }}</strong>
                    sampai
                    <strong>{{ $tables->lastItem() }}</strong>
                    dari
                    <strong>{{ $tables->total() }}</strong>
                    meja
                </div>

                <div>
                    {{ $tables->links() }}
                </div>
            </div>
        @else
            <div class="restaurant-table-empty">
                <div class="restaurant-table-empty-icon">
                    <i data-feather="layout"></i>
                </div>

                <h3 class="h6 fw-bold">
                    Meja belum tersedia
                </h3>

                <p class="text-muted small">
                    Tambahkan meja restoran untuk reservasi dan pemesanan dine-in.
                </p>

                <a class="btn btn-primary" href="{{ route('management.tables.create') }}">
                    <i data-feather="plus" class="me-1"></i>
                    Tambah Meja
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
                    confirmButtonColor: '#0061f2'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });

        function copyQrToken(tableId) {
            const tokenElement = document.getElementById(
                'qrToken' + tableId
            );

            navigator.clipboard.writeText(
                tokenElement.textContent.trim()
            ).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Token disalin',
                    text: 'Token QR meja berhasil disalin.',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        function confirmRegenerateQr(tableId, tableNumber) {
            Swal.fire({
                icon: 'warning',
                title: 'Buat ulang token QR?',
                text: 'QR lama untuk meja ' + tableNumber +
                    ' tidak akan dapat digunakan lagi.',
                showCancelButton: true,
                confirmButtonColor: '#f4a100',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Buat Ulang',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    document
                        .getElementById('regenerateQrForm' + tableId)
                        .submit();
                }
            });
        }

        function confirmDeleteTable(tableId, tableNumber) {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus meja?',
                text: 'Meja ' + tableNumber +
                    ' akan dihapus. Meja yang memiliki transaksi tidak dapat dihapus.',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    document
                        .getElementById('deleteTableForm' + tableId)
                        .submit();
                }
            });
        }
    </script>
@endpush
