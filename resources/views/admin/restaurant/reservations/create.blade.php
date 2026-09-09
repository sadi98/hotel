@extends('admin.layouts.main')

@section('page_title', 'Tambah Reservasi || ' . config('app.name'))

@section('meta_description', 'Tambahkan reservasi meja restoran.')

@section('header_title', 'Tambah Reservasi')

@section('header_subtitle', 'Buat reservasi manual untuk customer atau tamu restoran.')

@section('header_icon', 'calendar')

@section('header_action')
    <a class="btn btn-light" href="{{ route('management.reservations.index') }}">
        <i data-feather="arrow-left" class="me-1"></i>
        Kembali
    </a>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('management.reservations.index') }}">
            Reservasi
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Tambah Reservasi
    </li>
@endsection

@push('style')
    <style>
        .reservation-form-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .reservation-form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .reservation-form-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .reservation-form-section {
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .reservation-form-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.1rem;
        }

        .reservation-form-number {
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

        .reservation-form-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .reservation-form-section-text {
            margin: 0.1rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .reservation-required {
            color: #e81500;
        }

        .reservation-information {
            padding: 1rem;
            color: #69707a;
            background:
                linear-gradient(135deg,
                    rgba(0, 97, 242, 0.08),
                    rgba(105, 0, 199, 0.08));
            border: 1px solid rgba(0, 97, 242, 0.12);
            border-radius: 0.65rem;
            font-size: 0.77rem;
            line-height: 1.6;
        }

        .reservation-summary {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .reservation-summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.65rem 0;
            border-bottom: 1px solid #e0e5ec;
            font-size: 0.78rem;
        }

        .reservation-summary-item:last-child {
            border-bottom: 0;
        }

        .reservation-form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
        }

        @media (max-width: 767.98px) {
            .reservation-form-footer {
                flex-direction: column-reverse;
            }

            .reservation-form-footer .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'seated' => 'Sudah Duduk',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'no_show' => 'Tidak Hadir',
        ];
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <div class="d-flex align-items-start">
                <i data-feather="alert-triangle" class="me-2 mt-1"></i>

                <div>
                    <strong>Data reservasi belum valid.</strong>

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

    <form id="reservationCreateForm" action="{{ route('management.reservations.store') }}" method="POST">

        @csrf

        <div class="row">
            <div class="col-xl-8">
                <div class="card reservation-form-card mb-4">
                    <div class="reservation-form-header">
                        <h2 class="reservation-form-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Reservasi
                        </h2>

                        <span class="badge bg-primary-soft text-primary">
                            Reservasi manual
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="reservation-form-section">
                            <div class="reservation-form-heading">
                                <div class="reservation-form-number">1</div>

                                <div>
                                    <h3 class="reservation-form-section-title">
                                        Informasi Customer
                                    </h3>

                                    <p class="reservation-form-section-text">
                                        Masukkan identitas customer yang melakukan reservasi.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="customer_name">
                                        Nama Customer
                                        <span class="reservation-required">*</span>
                                    </label>

                                    <input class="form-control @error('customer_name') is-invalid @enderror"
                                        id="customer_name" name="customer_name" type="text"
                                        value="{{ old('customer_name') }}" maxlength="150"
                                        placeholder="Nama lengkap customer" required>

                                    @error('customer_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="customer_phone">
                                        Nomor Telepon
                                        <span class="reservation-required">*</span>
                                    </label>

                                    <input class="form-control @error('customer_phone') is-invalid @enderror"
                                        id="customer_phone" name="customer_phone" type="text" inputmode="tel"
                                        value="{{ old('customer_phone') }}" maxlength="30" placeholder="+6281234567890"
                                        required>

                                    @error('customer_phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="small mb-1" for="customer_email">
                                        Email
                                    </label>

                                    <input class="form-control @error('customer_email') is-invalid @enderror"
                                        id="customer_email" name="customer_email" type="email"
                                        value="{{ old('customer_email') }}" placeholder="customer@example.com">

                                    @error('customer_email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="reservation-form-section">
                            <div class="reservation-form-heading">
                                <div class="reservation-form-number">2</div>

                                <div>
                                    <h3 class="reservation-form-section-title">
                                        Meja dan Jumlah Tamu
                                    </h3>

                                    <p class="reservation-form-section-text">
                                        Pilih meja sesuai kapasitas jumlah tamu.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-7 mb-3">
                                    <label class="small mb-1" for="restaurant_table_id">
                                        Meja Restoran
                                        <span class="reservation-required">*</span>
                                    </label>

                                    <select class="form-select @error('restaurant_table_id') is-invalid @enderror"
                                        id="restaurant_table_id" name="restaurant_table_id" required>

                                        <option value="">Pilih meja</option>

                                        @foreach ($tables as $table)
                                            <option value="{{ $table->id }}" data-number="{{ $table->table_number }}"
                                                data-area="{{ $table->area }}" data-capacity="{{ $table->capacity }}"
                                                @selected((string) old('restaurant_table_id') === (string) $table->id)>
                                                {{ $table->table_number }}
                                                — {{ $table->area }}
                                                — {{ $table->capacity }} orang
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('restaurant_table_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-5 mb-3">
                                    <label class="small mb-1" for="guest_count">
                                        Jumlah Tamu
                                        <span class="reservation-required">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control @error('guest_count') is-invalid @enderror"
                                            id="guest_count" name="guest_count" type="number"
                                            value="{{ old('guest_count', 1) }}" min="1" max="100" required>

                                        <span class="input-group-text">orang</span>

                                        @error('guest_count')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning d-none mb-0" id="capacityWarning">
                                Jumlah tamu melebihi kapasitas meja.
                            </div>
                        </div>

                        <div class="reservation-form-section">
                            <div class="reservation-form-heading">
                                <div class="reservation-form-number">3</div>

                                <div>
                                    <h3 class="reservation-form-section-title">
                                        Jadwal Reservasi
                                    </h3>

                                    <p class="reservation-form-section-text">
                                        Tentukan waktu mulai dan selesai reservasi.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="reservation_start">
                                        Waktu Mulai
                                        <span class="reservation-required">*</span>
                                    </label>

                                    <input class="form-control @error('reservation_start') is-invalid @enderror"
                                        id="reservation_start" name="reservation_start" type="datetime-local"
                                        value="{{ old('reservation_start') }}" required>

                                    @error('reservation_start')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="reservation_end">
                                        Waktu Selesai
                                        <span class="reservation-required">*</span>
                                    </label>

                                    <input class="form-control @error('reservation_end') is-invalid @enderror"
                                        id="reservation_end" name="reservation_end" type="datetime-local"
                                        value="{{ old('reservation_end') }}" required>

                                    @error('reservation_end')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="reservation-form-section">
                            <div class="reservation-form-heading">
                                <div class="reservation-form-number">4</div>

                                <div>
                                    <h3 class="reservation-form-section-title">
                                        Status dan Permintaan
                                    </h3>

                                    <p class="reservation-form-section-text">
                                        Tentukan status awal dan permintaan customer.
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="status">
                                    Status
                                    <span class="reservation-required">*</span>
                                </label>

                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>

                                    @foreach ($statuses as $statusOption)
                                        <option value="{{ $statusOption }}" @selected(old('status', 'pending') === $statusOption)>
                                            {{ $statusLabels[$statusOption] ?? ucfirst($statusOption) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="special_request">
                                    Permintaan Khusus
                                </label>

                                <textarea class="form-control @error('special_request') is-invalid @enderror" id="special_request"
                                    name="special_request" rows="4" maxlength="3000"
                                    placeholder="Contoh: Meja dekat jendela atau dekorasi ulang tahun.">{{ old('special_request') }}</textarea>

                                @error('special_request')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-none" id="cancellationReasonContainer">

                                <label class="small mb-1" for="cancellation_reason">
                                    Alasan Pembatalan
                                </label>

                                <textarea class="form-control @error('cancellation_reason') is-invalid @enderror" id="cancellation_reason"
                                    name="cancellation_reason" rows="3" maxlength="2000">{{ old('cancellation_reason') }}</textarea>

                                @error('cancellation_reason')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="reservation-form-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.reservations.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="submitReservationButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Reservasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="reservation-information mb-4">
                    <div class="fw-bold text-dark mb-2">
                        <i data-feather="info" class="me-1"></i>
                        Pemeriksaan Otomatis
                    </div>

                    Sistem akan memeriksa kapasitas meja dan bentrok jadwal
                    dengan reservasi lain sebelum data disimpan.
                </div>

                <div class="reservation-summary">
                    <h3 class="h6 fw-bold mb-3">Ringkasan</h3>

                    <div class="reservation-summary-item">
                        <span class="text-muted">Customer</span>
                        <strong id="summaryCustomer">-</strong>
                    </div>

                    <div class="reservation-summary-item">
                        <span class="text-muted">Meja</span>
                        <strong id="summaryTable">-</strong>
                    </div>

                    <div class="reservation-summary-item">
                        <span class="text-muted">Tamu</span>
                        <strong id="summaryGuest">1 orang</strong>
                    </div>

                    <div class="reservation-summary-item">
                        <span class="text-muted">Jadwal</span>
                        <strong id="summarySchedule">-</strong>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reservationCreateForm');
            const submitButton = document.getElementById(
                'submitReservationButton'
            );
            const customerInput = document.getElementById('customer_name');
            const tableInput = document.getElementById(
                'restaurant_table_id'
            );
            const guestInput = document.getElementById('guest_count');
            const startInput = document.getElementById('reservation_start');
            const endInput = document.getElementById('reservation_end');
            const statusInput = document.getElementById('status');
            const cancellationContainer = document.getElementById(
                'cancellationReasonContainer'
            );
            const cancellationInput = document.getElementById(
                'cancellation_reason'
            );
            const capacityWarning = document.getElementById(
                'capacityWarning'
            );

            function updateSummary() {
                const selectedTable =
                    tableInput.options[tableInput.selectedIndex];

                document.getElementById('summaryCustomer').textContent =
                    customerInput.value.trim() || '-';

                document.getElementById('summaryTable').textContent =
                    selectedTable?.dataset.number || '-';

                document.getElementById('summaryGuest').textContent =
                    (guestInput.value || 0) + ' orang';

                document.getElementById('summarySchedule').textContent =
                    startInput.value && endInput.value ?
                    formatDate(startInput.value) +
                    ' – ' +
                    formatTime(endInput.value) :
                    '-';

                const capacity = Number(
                    selectedTable?.dataset.capacity
                ) || 0;

                const guestCount = Number(guestInput.value) || 0;

                capacityWarning.classList.toggle(
                    'd-none',
                    !capacity || guestCount <= capacity
                );
            }

            function updateCancellationField() {
                const cancelled = statusInput.value === 'cancelled';

                cancellationContainer.classList.toggle(
                    'd-none',
                    !cancelled
                );

                cancellationInput.required = cancelled;
            }

            function formatDate(value) {
                return new Intl.DateTimeFormat('id-ID', {
                    dateStyle: 'medium',
                    timeStyle: 'short'
                }).format(new Date(value));
            }

            function formatTime(value) {
                return new Intl.DateTimeFormat('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                }).format(new Date(value));
            }

            [
                customerInput,
                tableInput,
                guestInput,
                startInput,
                endInput
            ].forEach(function(input) {
                input.addEventListener('input', updateSummary);
                input.addEventListener('change', updateSummary);
            });

            statusInput.addEventListener(
                'change',
                updateCancellationField
            );

            form.addEventListener('submit', function(event) {
                if (!capacityWarning.classList.contains('d-none')) {
                    event.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Kapasitas meja tidak cukup',
                        text: 'Pilih meja dengan kapasitas yang lebih besar.',
                        confirmButtonColor: '#0061f2'
                    });

                    return;
                }

                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            updateSummary();
            updateCancellationField();

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Data reservasi belum valid',
                    text: @json($errors->first()),
                    confirmButtonText: 'Periksa Kembali',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>
@endpush
