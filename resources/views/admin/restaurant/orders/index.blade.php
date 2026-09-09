@extends('admin.layouts.main')

@section('page_title', 'Pesanan Restoran || ' . config('app.name'))

@section('meta_description', 'Kelola pesanan restoran dari customer dan guest.')

@section('header_title', 'Pesanan Restoran')

@section('header_subtitle', 'Kelola pesanan dine-in, delivery, room service, status pengerjaan, dan pembayaran.')

@section('header_icon', 'shopping-bag')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Pesanan
    </li>
@endsection

@push('style')
    <style>
        .order-list-card {
            overflow: hidden;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.08);
        }

        .order-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e5ec;
        }

        .order-list-title {
            display: flex;
            align-items: center;
            margin: 0;
            color: #363d47;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .order-list-title svg {
            width: 18px;
            height: 18px;
        }

        .order-filter {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e5ec;
            border-radius: 0.65rem;
        }

        .order-filter-label {
            margin-bottom: 0.4rem;
            color: #69707a;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .order-table {
            margin-bottom: 0;
        }

        .order-table thead th {
            padding: 0.85rem 1rem;
            color: #69707a;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e5ec;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .order-table tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #edf0f4;
            vertical-align: middle;
        }

        .order-number {
            color: #0061f2;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-customer {
            margin-bottom: 0.15rem;
            color: #1f2d3d;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .order-secondary {
            color: #69707a;
            font-size: 0.71rem;
        }

        .order-total {
            color: #0061f2;
            font-size: 0.86rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            border-radius: 2rem;
            font-size: 0.67rem;
            font-weight: 700;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .order-type-dine_in {
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
        }

        .order-type-delivery {
            color: #6900c7;
            background-color: rgba(105, 0, 199, 0.1);
        }

        .order-type-room_service {
            color: #b27400;
            background-color: rgba(244, 161, 0, 0.15);
        }

        .order-status-pending,
        .order-status-preparing {
            color: #b27400;
            background-color: rgba(244, 161, 0, 0.15);
        }

        .order-status-confirmed,
        .order-status-ready {
            color: #0061f2;
            background-color: rgba(0, 97, 242, 0.1);
        }

        .order-status-served,
        .order-status-completed {
            color: #008a56;
            background-color: rgba(0, 172, 105, 0.12);
        }

        .order-status-cancelled {
            color: #c51f1a;
            background-color: rgba(232, 21, 0, 0.1);
        }

        .order-payment-paid {
            color: #008a56;
            background-color: rgba(0, 172, 105, 0.12);
        }

        .order-payment-unpaid,
        .order-payment-pending {
            color: #b27400;
            background-color: rgba(244, 161, 0, 0.15);
        }

        .order-payment-failed,
        .order-payment-refunded {
            color: #c51f1a;
            background-color: rgba(232, 21, 0, 0.1);
        }

        .order-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            padding: 0;
            border-radius: 0.45rem;
        }

        .order-action svg {
            width: 15px;
            height: 15px;
        }

        .order-empty {
            padding: 4rem 1.5rem;
            text-align: center;
        }

        .order-empty-icon {
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

        .order-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-top: 1px solid #e0e5ec;
        }

        .order-pagination .pagination {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {

            .order-list-header,
            .order-pagination {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.75rem;
            }

            .order-pagination nav {
                width: 100%;
                overflow-x: auto;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Disiapkan',
            'ready' => 'Siap',
            'served' => 'Disajikan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $paymentStatusLabels = [
            'unpaid' => 'Belum Dibayar',
            'pending' => 'Menunggu',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];

        $orderTypeLabels = [
            'dine_in' => 'Dine-in',
            'delivery' => 'Delivery',
            'room_service' => 'Room Service',
        ];

        $orderTypeIcons = [
            'dine_in' => 'coffee',
            'delivery' => 'truck',
            'room_service' => 'home',
        ];
    @endphp

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <i data-feather="check-circle" class="me-2"></i>

                <div>
                    <strong>Berhasil!</strong>
                    {{ session('success') }}
                </div>
            </div>

            <button class="btn-close" type="button" data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <i data-feather="alert-circle" class="me-2"></i>

                <div>
                    <strong>Gagal!</strong>
                    {{ session('error') }}
                </div>
            </div>

            <button class="btn-close" type="button" data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <div class="card order-list-card mb-4">
        <div class="order-list-header">
            <h2 class="order-list-title">
                <i data-feather="filter" class="me-2"></i>
                Pencarian dan Filter
            </h2>

            @if (request()->filled('search') ||
                    request()->filled('status') ||
                    request()->filled('payment_status') ||
                    request()->filled('order_type') ||
                    request()->filled('date'))
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('management.orders.index') }}">
                    <i data-feather="x" class="me-1"></i>
                    Hapus Filter
                </a>
            @endif
        </div>

        <div class="card-body">
            <form action="{{ route('management.orders.index') }}" method="GET">

                <div class="order-filter">
                    <div class="row gx-3">
                        <div class="col-xl-4 col-md-6 mb-3">
                            <label class="order-filter-label" for="search">
                                Pencarian
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search"></i>
                                </span>

                                <input class="form-control" id="search" name="search" type="text"
                                    value="{{ $search }}" placeholder="Nomor order, customer, kamar...">
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-6 mb-3">
                            <label class="order-filter-label" for="status">
                                Status Pesanan
                            </label>

                            <select class="form-select" id="status" name="status">

                                <option value="">Semua status</option>

                                @foreach ($statuses as $statusOption)
                                    <option value="{{ $statusOption }}" @selected($status === $statusOption)>
                                        {{ $statusLabels[$statusOption] ?? ucfirst($statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-6 mb-3">
                            <label class="order-filter-label" for="payment_status">
                                Pembayaran
                            </label>

                            <select class="form-select" id="payment_status" name="payment_status">

                                <option value="">Semua pembayaran</option>

                                @foreach ($paymentStatusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected($paymentStatus === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-6 mb-3">
                            <label class="order-filter-label" for="order_type">
                                Jenis Pesanan
                            </label>

                            <select class="form-select" id="order_type" name="order_type">

                                <option value="">Semua jenis</option>

                                @foreach ($orderTypeLabels as $value => $label)
                                    <option value="{{ $value }}" @selected($orderType === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-6 mb-3">
                            <label class="order-filter-label" for="date">
                                Tanggal
                            </label>

                            <input class="form-control" id="date" name="date" type="date"
                                value="{{ $date }}">
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="search" class="me-1"></i>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card order-list-card mb-4">
        <div class="order-list-header">
            <h2 class="order-list-title">
                <i data-feather="shopping-bag" class="me-2"></i>
                Daftar Pesanan
            </h2>

            <span class="badge bg-primary-soft text-primary">
                {{ $orders->total() }} pesanan
            </span>
        </div>

        @if ($orders->count() > 0)
            <div class="table-responsive">
                <table class="table order-table">
                    <thead>
                        <tr>
                            <th>Pesanan</th>
                            <th>Customer</th>
                            <th>Jenis</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <div class="order-number">
                                        {{ $order->order_number }}
                                    </div>

                                    <div class="order-secondary">
                                        {{ $order->user_id ? 'User terdaftar' : 'Guest' }}

                                        @if ($order->created_by)
                                            · Kasir:
                                            {{ $order->createdBy?->name ?? '-' }}
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <div class="order-customer">
                                        {{ $order->customer_name }}
                                    </div>

                                    <div class="order-secondary">
                                        {{ $order->customer_phone }}
                                    </div>
                                </td>

                                <td>
                                    <span class="order-badge order-type-{{ $order->order_type }}">
                                        <i data-feather="{{ $orderTypeIcons[$order->order_type] ?? 'shopping-bag' }}"
                                            class="me-1">
                                        </i>

                                        {{ $orderTypeLabels[$order->order_type] ?? ucfirst($order->order_type) }}
                                    </span>

                                    @if ($order->order_type === 'dine_in')
                                        <div class="order-secondary mt-1">
                                            Meja:
                                            {{ $order->restaurantTable?->table_number ?? '-' }}

                                            @if ($order->ordered_from_table_qr)
                                                · QR
                                            @endif
                                        </div>
                                    @elseif ($order->order_type === 'room_service')
                                        <div class="order-secondary mt-1">
                                            Kamar: {{ $order->room_number ?: '-' }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="order-customer">
                                        {{ $order->ordered_at ? \Illuminate\Support\Carbon::parse($order->ordered_at)->translatedFormat('d M Y') : '-' }}
                                    </div>

                                    <div class="order-secondary">
                                        {{ $order->ordered_at ? \Illuminate\Support\Carbon::parse($order->ordered_at)->format('H:i') : '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="order-total">
                                        Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                    </div>

                                    <div class="order-secondary">
                                        {{ $order->payment_timing === 'pay_later' ? 'Bayar nanti' : 'Bayar sekarang' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="order-badge order-status-{{ $order->status }}">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="order-badge order-payment-{{ $order->payment_status }}">
                                        {{ $paymentStatusLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <a class="btn btn-outline-primary order-action"
                                        href="{{ route('management.orders.show', $order) }}" data-bs-toggle="tooltip"
                                        title="Detail pesanan">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="order-pagination">
                <div class="text-muted small">
                    Menampilkan
                    <strong>{{ $orders->firstItem() }}</strong>
                    sampai
                    <strong>{{ $orders->lastItem() }}</strong>
                    dari
                    <strong>{{ $orders->total() }}</strong>
                    pesanan
                </div>

                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="order-empty">
                <div class="order-empty-icon">
                    <i data-feather="shopping-bag"></i>
                </div>

                <h3 class="h6 fw-bold">
                    Pesanan belum tersedia
                </h3>

                <p class="text-muted small mb-0">
                    Belum ada pesanan yang sesuai dengan filter.
                </p>
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
    </script>
@endpush
