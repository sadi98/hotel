@extends('admin.layouts.main')

@section('page_title', 'Tambah Meja || ' . config('app.name'))

@section('meta_description', 'Tambahkan meja restoran baru.')

@section('header_title', 'Tambah Meja')

@section('header_subtitle', 'Tambahkan meja baru beserta area dan kapasitasnya.')

@section('header_icon', 'plus-circle')

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
        Tambah Meja
    </li>
@endsection

@push('style')
    <style>
        .table-form-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .table-form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .table-form-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .table-form-section {
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .table-form-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.1rem;
        }

        .table-form-number {
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

        .table-form-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .table-form-section-text {
            margin: 0.1rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .table-form-required {
            color: #e81500;
        }

        .table-form-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .table-form-preview {
            padding: 1.5rem;
            text-align: center;
            background:
                radial-gradient(circle at top right,
                    rgba(255, 255, 255, 0.3),
                    transparent 35%),
                linear-gradient(135deg, #0061f2, #6900c7);
            border-radius: 0.75rem;
        }

        .table-form-preview-icon {
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

        .table-form-preview-number {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .table-form-preview-detail {
            margin-top: 0.35rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
        }

        .table-form-information {
            padding: 1rem;
            margin-top: 1rem;
            color: #69707a;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
            font-size: 0.76rem;
            line-height: 1.6;
        }

        .table-form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
        }

        @media (max-width: 767.98px) {
            .table-form-status {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .table-form-footer {
                flex-direction: column-reverse;
            }

            .table-form-footer .btn {
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

    <form id="tableCreateForm" action="{{ route('management.tables.store') }}" method="POST">

        @csrf

        <div class="row">
            <div class="col-xl-8">
                <div class="card table-form-card mb-4">
                    <div class="table-form-header">
                        <h2 class="table-form-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Meja
                        </h2>

                        <span class="badge bg-primary-soft text-primary">
                            Meja baru
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="table-form-section">
                            <div class="table-form-heading">
                                <div class="table-form-number">1</div>

                                <div>
                                    <h3 class="table-form-section-title">
                                        Identitas Meja
                                    </h3>

                                    <p class="table-form-section-text">
                                        Tentukan nomor dan nama meja.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-5 mb-3">
                                    <label class="small mb-1" for="table_number">
                                        Nomor Meja
                                        <span class="table-form-required">*</span>
                                    </label>

                                    <input class="form-control @error('table_number') is-invalid @enderror"
                                        id="table_number" name="table_number" type="text"
                                        value="{{ old('table_number') }}" maxlength="30" placeholder="Contoh: T-01" required
                                        autofocus>

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
                                        name="name" type="text" value="{{ old('name') }}" maxlength="100"
                                        placeholder="Contoh: Window Table">

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-form-section">
                            <div class="table-form-heading">
                                <div class="table-form-number">2</div>

                                <div>
                                    <h3 class="table-form-section-title">
                                        Area dan Kapasitas
                                    </h3>

                                    <p class="table-form-section-text">
                                        Tentukan lokasi dan kapasitas meja.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-7 mb-3">
                                    <label class="small mb-1" for="area">
                                        Area
                                        <span class="table-form-required">*</span>
                                    </label>

                                    <input class="form-control @error('area') is-invalid @enderror" id="area"
                                        name="area" type="text" value="{{ old('area') }}" maxlength="50"
                                        placeholder="Contoh: Indoor, Outdoor, VIP" required>

                                    @error('area')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-5 mb-3">
                                    <label class="small mb-1" for="capacity">
                                        Kapasitas
                                        <span class="table-form-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control @error('capacity') is-invalid @enderror" id="capacity"
                                            name="capacity" type="number" value="{{ old('capacity', 2) }}" min="1"
                                            max="100" required>

                                        <span class="input-group-text">
                                            orang
                                        </span>

                                        @error('capacity')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <label class="small mb-1" for="description">
                                Keterangan Meja
                            </label>

                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="5" maxlength="2000" placeholder="Contoh: Berada di dekat jendela dengan pemandangan kota.">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="table-form-section">
                            <div class="table-form-heading">
                                <div class="table-form-number">3</div>

                                <div>
                                    <h3 class="table-form-section-title">
                                        Status Meja
                                    </h3>

                                    <p class="table-form-section-text">
                                        Tentukan apakah meja dapat digunakan.
                                    </p>
                                </div>
                            </div>

                            <div class="table-form-status">
                                <div>
                                    <div class="fw-bold small mb-1">
                                        Aktifkan meja
                                    </div>

                                    <div class="text-muted small">
                                        Meja aktif dapat digunakan untuk reservasi
                                        dan pemesanan dine-in.
                                    </div>
                                </div>

                                <div>
                                    <input name="is_active" type="hidden" value="0">

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" id="is_active" name="is_active" type="checkbox"
                                            value="1" @checked(old('is_active', 1))>

                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-form-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.tables.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="submitTableButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Meja
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="table-form-preview">
                    <div class="table-form-preview-icon">
                        <i data-feather="layout"></i>
                    </div>

                    <div class="table-form-preview-number" id="tableNumberPreview">
                        Nomor Meja
                    </div>

                    <div class="table-form-preview-detail" id="tableDetailPreview">
                        Area · 2 orang
                    </div>
                </div>

                <div class="table-form-information">
                    <div class="fw-bold text-dark mb-2">
                        <i data-feather="info" class="me-1"></i>
                        Token QR
                    </div>

                    Token QR unik akan dibuat otomatis setelah meja disimpan.
                    Token tersebut digunakan untuk mengenali pesanan yang
                    berasal dari QR meja.
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('tableCreateForm');
            const submitButton = document.getElementById(
                'submitTableButton'
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
                const number = numberInput.value.trim();
                const area = areaInput.value.trim();
                const capacity = capacityInput.value || 0;

                numberPreview.textContent = number || 'Nomor Meja';

                detailPreview.textContent =
                    (area || 'Area') +
                    ' · ' +
                    capacity +
                    ' orang';
            }

            numberInput.addEventListener('input', updatePreview);
            areaInput.addEventListener('input', updatePreview);
            capacityInput.addEventListener('input', updatePreview);

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            updatePreview();

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
    </script>
@endpush
