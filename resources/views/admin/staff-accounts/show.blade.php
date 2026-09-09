@extends('admin.layouts.main')

@section('page_title', 'Staff ' . $user->name)
@section('meta_description', 'Detail akun dan aktivitas staff.')
@section('header_title', 'Detail Akun Staff')
@section('header_subtitle', 'Informasi akun dan aktivitas ' . $user->name . '.')
@section('header_icon', 'user-check')

@section('header_action')
    <div class="d-flex gap-2">
        <a href="{{ route('management.staff-accounts.edit', $user) }}" class="btn btn-warning">
            <i data-feather="edit-2" class="me-2"></i>
            Edit
        </a>

        <a href="{{ route('management.staff-accounts.index') }}" class="btn btn-light">
            <i data-feather="arrow-left" class="me-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4 overflow-hidden">
        <div class="staff-detail-header">
            <div>
                <h4 class="text-white mb-1">
                    {{ $user->name }}
                </h4>

                <div class="text-white-50">
                    {{ '@' . $user->username }}
                </div>

                <span class="badge bg-light text-dark mt-2">
                    Staff
                </span>
            </div>

            <div class="text-white text-xl-end">
                <div class="text-white-50 small">
                    Bergabung
                </div>

                <div class="fw-semibold">
                    {{ $user->created_at->format('d F Y') }}
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="staff-info-box">
                        <div class="small text-muted">Email</div>
                        <div class="fw-semibold text-break">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="staff-info-box">
                        <div class="small text-muted">Telepon</div>
                        <div class="fw-semibold">
                            {{ $user->phone ?: '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="staff-info-box">
                        <div class="small text-muted">Jenis Kelamin</div>
                        <div class="fw-semibold">
                            {{ $user->gender === 'male' ? 'Laki-laki' : ($user->gender === 'female' ? 'Perempuan' : '-') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="staff-info-box">
                        <div class="small text-muted">Tanggal Lahir</div>
                        <div class="fw-semibold">
                            {{ optional($user->date_of_birth)->format('d M Y') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ([['Pesanan Dibuat', $statistics['created_orders']], ['Pesanan Hari Ini', $statistics['orders_today']], ['Pembayaran Diproses', $statistics['processed_payments']], ['Total Pembayaran', 'Rp' . number_format($statistics['payment_total'], 0, ',', '.')]] as [$label, $value])
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">{{ $label }}</div>
                        <div class="staff-detail-stat">{{ $value }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            Pesanan Terbaru yang Dibuat
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('management.orders.show', $order) }}">
                                        {{ $order->order_number }}
                                    </a>
                                </td>

                                <td>{{ $order->customer_name }}</td>
                                <td>{{ ucfirst($order->status) }}</td>

                                <td>
                                    Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ optional($order->ordered_at)->format('d M Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada aktivitas pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .staff-detail-header {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #0061f2, #6900c7);
        }

        .staff-info-box {
            height: 100%;
            padding: 1rem;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .staff-detail-stat {
            margin-top: 0.35rem;
            color: #363d47;
            font-size: 1.35rem;
            font-weight: 700;
        }

        @media (max-width: 767.98px) {
            .staff-detail-header {
                flex-direction: column;
            }
        }
    </style>
@endpush
