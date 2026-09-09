@extends('admin.layouts.main')

@section('page_title', 'Edit Reservasi || ' . config('app.name'))

@section('meta_description', 'Perbarui reservasi meja restoran.')

@section('header_title', 'Edit Reservasi')

@section('header_subtitle', 'Perbarui data customer, meja, jadwal, dan status reservasi.')

@section('header_icon', 'edit-3')

@section('header_action')
    <div class="d-flex gap-2">
        <a class="btn btn-light" href="{{ route('management.reservations.show', $reservation) }}">
            <i data-feather="eye" class="me-1"></i>
            Detail
        </a>

        <a class="btn btn-light" href="{{ route('management.reservations.index') }}">
            <i data-feather="arrow-left" class="me-1"></i>
            Kembali
        </a>
    </div>
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
        Edit {{ $reservation->reservation_number }}
    </li>
@endsection

@push('style')
    <style>
        .reservation-edit-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .reservation-edit-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .reservation-edit-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .reservation-edit-section {
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e5ec;
        }

        .reservation-edit-heading {
            display: flex;
            align-items: center;
            margin-bottom: 1.1rem;
        }

        .reservation-edit-number {
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

        .reservation-edit-section-title {
            margin: 0;
            color: #363d47;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .reservation-edit-section-text {
            margin: 0.1rem 0 0;
            color: #69707a;
            font-size: 0.75rem;
        }

        .reservation-edit-summary {
            padding: 1rem;
            background:
                linear-gradient(135deg,
                    rgba(0, 97, 242, 0.08),
                    rgba(105, 0, 199, 0.08));
            border: 1px solid rgba(0, 97, 242, 0.12);
            border-radius: 0.65rem;
        }

        .reservation-edit-summary-item {
            padding: 0.65rem 0;
            border-bottom: 1px solid rgba(0, 97, 242, 0.1);
        }

        .reservation-edit-summary-item:last-child {
            border-bottom: 0;
        }

        .reservation-edit-summary-label {
            color: #69707a;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .reservation-edit-summary-value {
            color: #363d47;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .reservation-edit-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.65rem;
            padding-top: 1.25rem;
        }

        @media (max-width: 767.98px) {
            .reservation-edit-footer {
                flex-direction: column-reverse;
            }

            .reservation-edit-footer .btn {
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

        $reservationStart = old(
            'reservation_start',
            \Illuminate\Support\Carbon::parse($reservation->reservation_start)->format('Y-m-d\TH:i'),
        );

        $reservationEnd = old(
            'reservation_end',
            \Illuminate\Support\Carbon::parse($reservation->reservation_end)->format('Y-m-d\TH:i'),
        );
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

    <form id="reservationEditForm" action="{{ route('management.reservations.update', $reservation) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <div class="card reservation-edit-card mb-4">
                    <div class="reservation-edit-header">
                        <h2 class="reservation-edit-title">
                            <i data-feather="edit-3" class="me-2"></i>
                            Informasi Reservasi
                        </h2>

                        <span class="badge bg-primary-soft text-primary">
                            {{ $reservation->reservation_number }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="reservation-edit-section">
                            <div class="reservation-edit-heading">
                                <div class="reservation-edit-number">1</div>

                                <div>
                                    <h3 class="reservation-edit-section-title">
                                        Informasi Customer
                                    </h3>

                                    <p class="reservation-edit-section-text">
                                        Perbarui identitas dan kontak customer.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="customer_name">
                                        Nama Customer
                                    </label>

                                    <input class="form-control @error('customer_name') is-invalid @enderror"
                                        id="customer_name" name="customer_name" type="text"
                                        value="{{ old('customer_name', $reservation->customer_name) }}" maxlength="150"
                                        required>

                                    @error('customer_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="customer_phone">
                                        Nomor Telepon
                                    </label>

                                    <input class="form-control @error('customer_phone') is-invalid @enderror"
                                        id="customer_phone" name="customer_phone" type="text"
                                        value="{{ old('customer_phone', $reservation->customer_phone) }}" maxlength="30"
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
                                        value="{{ old('customer_email', $reservation->customer_email) }}">

                                    @error('customer_email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="reservation-edit-section">
                            <div class="reservation-edit-heading">
                                <div class="reservation-edit-number">2</div>

                                <div>
                                    <h3 class="reservation-edit-section-title">
                                        Meja dan Jadwal
                                    </h3>

                                    <p class="reservation-edit-section-text">
                                        Perbarui meja, jumlah tamu, dan jadwal.
                                    </p>
                                </div>
                            </div>

                            <div class="row gx-3">
                                <div class="col-md-7 mb-3">
                                    <label class="small mb-1" for="restaurant_table_id">
                                        Meja Restoran
                                    </label>

                                    <select class="form-select @error('restaurant_table_id') is-invalid @enderror"
                                        id="restaurant_table_id" name="restaurant_table_id" required>

                                        @foreach ($tables as $table)
                                            <option value="{{ $table->id }}" data-capacity="{{ $table->capacity }}"
                                                @selected((string) old('restaurant_table_id', $reservation->restaurant_table_id) === (string) $table->id)>
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
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control @error('guest_count') is-invalid @enderror"
                                            id="guest_count" name="guest_count" type="number"
                                            value="{{ old('guest_count', $reservation->guest_count) }}" min="1"
                                            max="100" required>

                                        <span class="input-group-text">orang</span>

                                        @error('guest_count')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="reservation_start">
                                        Waktu Mulai
                                    </label>

                                    <input class="form-control @error('reservation_start') is-invalid @enderror"
                                        id="reservation_start" name="reservation_start" type="datetime-local"
                                        value="{{ $reservationStart }}" required>

                                    @error('reservation_start')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small mb-1" for="reservation_end">
                                        Waktu Selesai
                                    </label>

                                    <input class="form-control @error('reservation_end') is-invalid @enderror"
                                        id="reservation_end" name="reservation_end" type="datetime-local"
                                        value="{{ $reservationEnd }}" required>

                                    @error('reservation_end')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="alert alert-warning d-none" id="editCapacityWarning">
                                Jumlah tamu melebihi kapasitas meja.
                            </div>
                        </div>

                        <div class="reservation-edit-section">
                            <div class="reservation-edit-heading">
                                <div class="reservation-edit-number">3</div>

                                <div>
                                    <h3 class="reservation-edit-section-title">
                                        Status dan Permintaan
                                    </h3>

                                    <p class="reservation-edit-section-text">
                                        Perbarui status dan catatan reservasi.
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="status">Status</label>

                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>

                                    @foreach ($statuses as $statusOption)
                                        <option value="{{ $statusOption }}" @selected(old('status', $reservation->status) === $statusOption)>
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
                                    name="special_request" rows="4" maxlength="3000">{{ old('special_request', $reservation->special_request) }}</textarea>

                                @error('special_request')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="{{ old('status', $reservation->status) === 'cancelled' ? '' : 'd-none' }}"
                                id="editCancellationContainer">

                                <label class="small mb-1" for="cancellation_reason">
                                    Alasan Pembatalan
                                </label>

                                <textarea class="form-control @error('cancellation_reason') is-invalid @enderror" id="cancellation_reason"
                                    name="cancellation_reason" rows="3" maxlength="2000">{{ old('cancellation_reason', $reservation->cancellation_reason) }}</textarea>

                                @error('cancellation_reason')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="reservation-edit-footer">
                            <a class="btn btn-outline-secondary" href="{{ route('management.reservations.index') }}">
                                <i data-feather="x" class="me-1"></i>
                                Batal
                            </a>

                            <button class="btn btn-primary" id="updateReservationButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="reservation-edit-summary">
                    <h2 class="h6 fw-bold mb-3">
                        Informasi Reservasi
                    </h2>

                    <div class="reservation-edit-summary-item">
                        <div class="reservation-edit-summary-label">
                            Nomor Reservasi
                        </div>

                        <div class="reservation-edit-summary-value">
                            {{ $reservation->reservation_number }}
                        </div>
                    </div>

                    <div class="reservation-edit-summary-item">
                        <div class="reservation-edit-summary-label">
                            Jenis Customer
                        </div>

                        <div class="reservation-edit-summary-value">
                            {{ $reservation->user_id ? 'User terdaftar' : 'Guest' }}
                        </div>
                    </div>

                    <div class="reservation-edit-summary-item">
                        <div class="reservation-edit-summary-label">
                            Dibuat
                        </div>

                        <div class="reservation-edit-summary-value">
                            {{ $reservation->created_at?->translatedFormat('d F Y H:i') ?? '-' }}
                        </div>
                    </div>

                    <div class="reservation-edit-summary-item">
                        <div class="reservation-edit-summary-label">
                            Diperbarui
                        </div>

                        <div class="reservation-edit-summary-value">
                            {{ $reservation->updated_at?->translatedFormat('d F Y H:i') ?? '-' }}
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
            const form = document.getElementById('reservationEditForm');
            const updateButton = document.getElementById(
                'updateReservationButton'
            );
            const tableInput = document.getElementById(
                'restaurant_table_id'
            );
            const guestInput = document.getElementById('guest_count');
            const capacityWarning = document.getElementById(
                'editCapacityWarning'
            );
            const statusInput = document.getElementById('status');
            const cancellationContainer = document.getElementById(
                'editCancellationContainer'
            );
            const cancellationInput = document.getElementById(
                'cancellation_reason'
            );

            function checkCapacity() {
                const selectedTable =
                    tableInput.options[tableInput.selectedIndex];

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

            tableInput.addEventListener('change', checkCapacity);
            guestInput.addEventListener('input', checkCapacity);

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
                        text: 'Jumlah tamu melebihi kapasitas meja.',
                        confirmButtonColor: '#0061f2'
                    });

                    return;
                }

                updateButton.disabled = true;
                updateButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            checkCapacity();
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
