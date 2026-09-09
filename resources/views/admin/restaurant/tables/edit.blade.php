@extends('admin.layouts.main')

@section('page_title', 'Edit Meja || ' . config('app.name'))

@section('meta_description', 'Perbarui informasi meja restoran.')

@section('header_title', 'Edit Meja')

@section('header_subtitle', 'Perbarui nomor, nama, area, kapasitas, dan status meja.')

@section('header_icon', 'edit-3')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.tables.index') }}">
        <i data-feather="arrow-left" class="me-1"></i>
        Kembali
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('management.tables.index') }}">
            Meja Restoran
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Edit {{ $restaurantTable->table_number }}
    </li>
@endsection

@push('style')
    <style>
        .table-edit-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .table-edit-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .table-edit-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .table-edit-section {
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .table-edit-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.1rem;
        }

        .table-edit-number {
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

        .table-edit-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .table-edit-section-text {
            margin: 0.1rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .table-edit-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .table-edit-preview {
            padding: 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, #0061f2, #6900c7);
            border-radius: 0.75rem;
        }

        .table-edit-preview-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            margin: 0 auto 1rem;
            color: #0061f2;
            background-color: #ffffff;
            border-radius: 50%;
        }

        .table-edit-preview-number {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .table-edit-preview-detail {
            margin-top: 0.35rem;
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.8rem;
        }

        .table-edit-token {
            padding: 1rem;
            margin-top: 1rem;
            background-color: #ffffff;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
            box-shadow: 0 0.15rem 1rem rgba(33, 40, 50, 0.06);
        }

        .table-edit-token-value {
            padding: 0.65rem;
            overflow-wrap: anywhere;
            color: #69707a;
            background-color: #f8f9fa;
            border-radius: 0.45rem;
            font-family: monospace;
            font-size: 0.72rem;
        }

        .table-edit-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
        }

        @media (max-width: 767.98px) {
            .table-edit-status {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .table-edit-footer {
                flex-direction: column-reverse;
            }

            .table-edit-footer .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <div class="d-flex align-items-start">
                <i data-feather="alert-triangle" class="me-2 mt-1"></i>

                <div>
                    <strong>Data meja belum valid.</strong>

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

    <form id="tableEditForm" action="{{ route('management.tables.update', $restaurantTable) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <div class="card table-edit-card mb-4">
                    <div class="table-edit-header">
                        <h2 class="table-edit-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Meja
                        </h2>

                        @if ($restaurantTable->is_active)
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
                        <div class="table-edit-section">
                            <div class="table-edit-heading">
                                <div class="table-edit-number">1</div>

                                <div>
                                    <h3 class="table-edit-section-title">
                                        Identitas Meja
                                    </h3>

                                    <p class="table-edit-section-text">
                                        Perbarui nomor dan nama meja.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-5 mb-3">
                                    <label class="small mb-1" for="table_number">
                                        Nomor Meja
                                    </label>

                                    <input class="form-control @error('table_number') is-invalid @enderror"
                                        id="table_number" name="table_number" type="text"
                                        value="{{ old('table_number', $restaurantTable->table_number) }}" maxlength="30"
                                        required>

                                    @error('table_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-7 mb-3">
                                    <label class="small mb-1" for="name">
                                        Nama Meja
                                    </label>

                                    <input class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" type="text" value="{{ old('name', $restaurantTable->name) }}"
                                        maxlength="100">

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-edit-section">
                            <div class="table-edit-heading">
                                <div class="table-edit-number">2</div>

                                <div>
                                    <h3 class="table-edit-section-title">
                                        Area dan Kapasitas
                                    </h3>

                                    <p class="table-edit-section-text">
                                        Perbarui lokasi dan kapasitas meja.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-7 mb-3">
                                    <label class="small mb-1" for="area">
                                        Area
                                    </label>

                                    <input class="form-control @error('area') is-invalid @enderror" id="area"
                                        name="area" type="text" value="{{ old('area', $restaurantTable->area) }}"
                                        maxlength="50" required>

                                    @error('area')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-5 mb-3">
                                    <label class="small mb-1" for="capacity">
                                        Kapasitas
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control @error('capacity') is-invalid @enderror" id="capacity"
                                            name="capacity" type="number"
                                            value="{{ old('capacity', $restaurantTable->capacity) }}" min="1"
                                            max="100" required>

                                        <span class="input-group-text">orang</span>

                                        @error('capacity')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <label class="small mb-1" for="description">
                                Keterangan
                            </label>

                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="5" maxlength="2000">{{ old('description', $restaurantTable->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="table-edit-section">
                            <div class="table-edit-heading">
                                <div class="table-edit-number">3</div>

                                <div>
                                    <h3 class="table-edit-section-title">
                                        Status Meja
                                    </h3>

                                    <p class="table-edit-section-text">
                                        Tentukan apakah meja dapat digunakan.
                                    </p>
                                </div>
                            </div>

                            <div class="table-edit-status">
                                <div>
                                    <div class="fw-bold small mb-1">
                                        Aktifkan meja
                                    </div>

                                    <div class="text-muted small">
                                        Meja aktif dapat digunakan untuk reservasi
                                        dan pemesanan.
                                    </div>
                                </div>

                                <div>
                                    <input name="is_active" type="hidden" value="0">

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" id="is_active" name="is_active" type="checkbox"
                                            value="1" @checked(old('is_active', $restaurantTable->is_active))>

                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-edit-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.tables.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="updateTableButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="table-edit-preview">
                    <div class="table-edit-preview-icon">
                        <i data-feather="layout"></i>
                    </div>

                    <div class="table-edit-preview-number" id="tableNumberPreview">
                        {{ $restaurantTable->table_number }}
                    </div>

                    <div class="table-edit-preview-detail" id="tableDetailPreview">
                        {{ $restaurantTable->area }}
                        ·
                        {{ $restaurantTable->capacity }} orang
                    </div>
                </div>

                <div class="table-edit-token">
                    <div class="fw-bold small mb-2">
                        <i data-feather="key" class="me-1"></i>
                        Token QR Meja
                    </div>

                    <div class="table-edit-token-value" id="tableQrToken">
                        {{ $restaurantTable->qr_token }}
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button class="btn btn-outline-primary" type="button" onclick="copyTableToken()">
                            <i data-feather="copy" class="me-1"></i>
                            Salin Token
                        </button>

                        <button class="btn btn-outline-warning" type="button" onclick="confirmRegenerateTableQr()">
                            <i data-feather="refresh-cw" class="me-1"></i>
                            Buat Ulang Token
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="regenerateTableQrForm" action="{{ route('management.tables.regenerate-qr', $restaurantTable) }}"
        method="POST" class="d-none">
        @csrf
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('tableEditForm');
            const updateButton = document.getElementById(
                'updateTableButton'
            );
            const numberInput = document.getElementById('table_number');
            const areaInput = document.getElementById('area');
            const capacityInput = document.getElementById('capacity');
            const numberPreview = document.getElementById(
                'tableNumberPreview'
            );
            const detailPreview = document.getElementById(
                'tableDetailPreview'
            );

            function updatePreview() {
                numberPreview.textContent =
                    numberInput.value.trim() || 'Nomor Meja';

                detailPreview.textContent =
                    (areaInput.value.trim() || 'Area') +
                    ' · ' +
                    (capacityInput.value || 0) +
                    ' orang';
            }

            numberInput.addEventListener('input', updatePreview);
            areaInput.addEventListener('input', updatePreview);
            capacityInput.addEventListener('input', updatePreview);

            form.addEventListener('submit', function() {
                updateButton.disabled = true;
                updateButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Data meja belum valid',
                    text: @json($errors->first()),
                    confirmButtonText: 'Periksa Kembali',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });

        function copyTableToken() {
            const token = document
                .getElementById('tableQrToken')
                .textContent
                .trim();

            navigator.clipboard.writeText(token).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Token disalin',
                    text: 'Token QR meja berhasil disalin.',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        function confirmRegenerateTableQr() {
            Swal.fire({
                icon: 'warning',
                title: 'Buat ulang token QR?',
                text: 'Token dan QR lama tidak akan dapat digunakan lagi.',
                showCancelButton: true,
                confirmButtonColor: '#f4a100',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Buat Ulang',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    document
                        .getElementById('regenerateTableQrForm')
                        .submit();
                }
            });
        }
    </script>
@endpush
