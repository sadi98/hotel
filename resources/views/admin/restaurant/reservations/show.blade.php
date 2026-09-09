@extends('admin.layouts.main')

@section('page_title', 'Detail Reservasi || ' . config('app.name'))

@section('meta_description', 'Detail reservasi meja restoran.')

@section('header_title', 'Detail Reservasi')

@section('header_subtitle', 'Informasi lengkap reservasi ' . $reservation->reservation_number . '.')

@section('header_icon', 'file-text')

@section('header_action')
    <div class="d-flex gap-2">
        <a class="btn btn-light" href="{{ route('management.reservations.index') }}">
            <i data-feather="arrow-left" class="me-1"></i>
            Kembali
        </a>

        <a class="btn btn-warning" href="{{ route('management.reservations.edit', $reservation) }}">
            <i data-feather="edit-2" class="me-1"></i>
            Edit
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
        {{ $reservation->reservation_number }}
    </li>
@endsection

@push('style')
    <style>
        .reservation-detail-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .reservation-detail-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .reservation-detail-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .reservation-detail-banner {
            padding: 1.5rem;
            color: #ffffff;
            background:
                radial-gradient(circle at top right,
                    rgba(255, 255, 255, 0.25),
                    transparent 35%),
                linear-gradient(135deg, #0061f2, #6900c7);
        }

        .reservation-detail-number {
            margin-bottom: 0.3rem;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .reservation-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .reservation-detail-item {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .reservation-detail-label {
            margin-bottom: 0.3rem;
            color: #69707a;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .reservation-detail-value {
            color: #363d47;
            font-size: 0.84rem;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .reservation-detail-note {
            min-height: 100px;
            padding: 1rem;
            color: #69707a;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
            font-size: 0.8rem;
            line-height: 1.65;
            white-space: pre-line;
        }

        .reservation-status-form {
            padding: 1rem;
            background:
                linear-gradient(135deg,
                    rgba(0, 97, 242, 0.08),
                    rgba(105, 0, 199, 0.08));
            border: 1px solid rgba(0, 97, 242, 0.12);
            border-radius: 0.65rem;
        }

        .reservation-token {
            padding: 0.75rem;
            overflow-wrap: anywhere;
            color: #69707a;
            background-color: #f8f9fa;
            border-radius: 0.45rem;
            font-family: monospace;
            font-size: 0.72rem;
        }

        @media (max-width: 767.98px) {
            .reservation-detail-grid {
                grid-template-columns: 1fr;
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

        $statusColors = [
            'pending' => 'warning',
            'confirmed' => 'primary',
            'seated' => 'purple',
            'completed' => 'success',
            'cancelled' => 'danger',
            'no_show' => 'danger',
        ];

        $statusColor = $statusColors[$reservation->status] ?? 'secondary';
    @endphp

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i data-feather="check-circle" class="me-2"></i>
            {{ session('success') }}

            <button class="btn-close" type="button" data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i data-feather="alert-circle" class="me-2"></i>
            {{ session('error') }}

            <button class="btn-close" type="button" data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="card reservation-detail-card mb-4">
                <div class="reservation-detail-banner">
                    <div class="reservation-detail-number">
                        {{ $reservation->reservation_number }}
                    </div>

                    <div class="small opacity-75">
                        Dibuat
                        {{ $reservation->created_at?->translatedFormat('d F Y H:i') ?? '-' }}
                    </div>

                    <span class="badge bg-{{ $statusColor }} mt-3">
                        {{ $statusLabels[$reservation->status] ?? ucfirst($reservation->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">
                        Informasi Customer
                    </h2>

                    <div class="reservation-detail-grid mb-4">
                        <div class="reservation-detail-item">
                            <div class="reservation-detail-label">Nama</div>

                            <div class="reservation-detail-value">
                                {{ $reservation->customer_name }}
                            </div>
                        </div>

                        <div class="reservation-detail-item">
                            <div class="reservation-detail-label">Telepon</div>

                            <div class="reservation-detail-value">
                                {{ $reservation->customer_phone }}
                            </div>
                        </div>

                        <div class="reservation-detail-item">
                            <div class="reservation-detail-label">Email</div>

                            <div class="reservation-detail-value">
                                {{ $reservation->customer_email ?: '-' }}
                            </div>
                        </div>

                        <div class="reservation-detail-item">
                            <div class="reservation-detail-label">Jenis Customer</div>

                            <div class="reservation-detail-value">
                                {{ $reservation->user_id ? 'User terdaftar' : 'Guest' }}
                            </div>
                        </div>
                    </div>

                    <h2 class="h6 fw-bold mb-3">
                        Informasi Reservasi
                    </h2>

                    <div class="reservation-detail-grid mb-4">
                        <div class="reservation-detail-item">
                            <div class="reservation-detail-label">Meja</div>

                            <div class="reservation-detail-value">
                                {{ $reservation->restaurantTable?->table_number ?? '-' }}
                                ·
                                {{ $reservation->restaurantTable?->area ?? '-' }}
                            </div>
                        </div>

                        <div class="reservation-detail-item">
                            <div class="reservation-detail-label">Jumlah Tamu</div>

                            <div class="reservation-detail-value">
                                {{ $reservation->guest_count }} orang
                            </div>
                        </div>

                        <div class="reservation-detail-item">
                            <div class="reservation-detail-label">Mulai</div>

                            <div class="reservation-detail-value">
                                {{ \Illuminate\Support\Carbon::parse($reservation->reservation_start)->translatedFormat('d F Y H:i') }}
                            </div>
                        </div>

                        <div class="reservation-detail-item">
                            <div class="reservation-detail-label">Selesai</div>

                            <div class="reservation-detail-value">
                                {{ \Illuminate\Support\Carbon::parse($reservation->reservation_end)->translatedFormat('d F Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <h2 class="h6 fw-bold mb-2">
                        Permintaan Khusus
                    </h2>

                    <div class="reservation-detail-note mb-4">
                        {{ $reservation->special_request ?: 'Tidak ada permintaan khusus.' }}
                    </div>

                    @if ($reservation->status === 'cancelled')
                        <h2 class="h6 fw-bold mb-2 text-danger">
                            Alasan Pembatalan
                        </h2>

                        <div class="reservation-detail-note border-danger">
                            {{ $reservation->cancellation_reason ?: '-' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card reservation-detail-card mb-4">
                <div class="reservation-detail-header">
                    <h2 class="reservation-detail-title">
                        <i data-feather="refresh-cw" class="me-2"></i>
                        Perbarui Status
                    </h2>
                </div>

                <div class="card-body">
                    <form id="reservationStatusForm" action="{{ route('management.reservations.update', $reservation) }}"
                        method="POST">

                        @csrf
                        @method('PATCH')

                        <div class="reservation-status-form">
                            <label class="small mb-1" for="status">
                                Status Reservasi
                            </label>

                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>

                                @foreach ($statusLabels as $statusValue => $statusLabel)
                                    <option value="{{ $statusValue }}" @selected(old('status', $reservation->status) === $statusValue)>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="mt-3 {{ old('status', $reservation->status) === 'cancelled' ? '' : 'd-none' }}"
                                id="statusCancellationContainer">

                                <label class="small mb-1" for="cancellation_reason">
                                    Alasan Pembatalan
                                </label>

                                <textarea class="form-control @error('cancellation_reason') is-invalid @enderror" id="cancellation_reason"
                                    name="cancellation_reason" rows="3">{{ old('cancellation_reason', $reservation->cancellation_reason) }}</textarea>

                                @error('cancellation_reason')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button class="btn btn-primary w-100 mt-3" id="updateStatusButton" type="submit">
                                <i data-feather="save" class="me-1"></i>
                                Simpan Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card reservation-detail-card mb-4">
                <div class="reservation-detail-header">
                    <h2 class="reservation-detail-title">
                        <i data-feather="key" class="me-2"></i>
                        Token Akses
                    </h2>
                </div>

                <div class="card-body">
                    <div class="reservation-token" id="reservationAccessToken">
                        {{ $reservation->access_token }}
                    </div>

                    <button class="btn btn-outline-primary w-100 mt-3" type="button" onclick="copyReservationToken()">
                        <i data-feather="copy" class="me-1"></i>
                        Salin Token
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reservationStatusForm');
            const statusInput = document.getElementById('status');
            const cancellationContainer = document.getElementById(
                'statusCancellationContainer'
            );
            const cancellationInput = document.getElementById(
                'cancellation_reason'
            );
            const submitButton = document.getElementById(
                'updateStatusButton'
            );

            function updateCancellationField() {
                const cancelled = statusInput.value === 'cancelled';

                cancellationContainer.classList.toggle(
                    'd-none',
                    !cancelled
                );

                cancellationInput.required = cancelled;
            }

            statusInput.addEventListener(
                'change',
                updateCancellationField
            );

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan...';
            });

            updateCancellationField();

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Status belum diperbarui',
                    text: @json($errors->first()),
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });

        function copyReservationToken() {
            const token = document
                .getElementById('reservationAccessToken')
                .textContent
                .trim();

            navigator.clipboard.writeText(token).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Token disalin',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }
    </script>
@endpush
