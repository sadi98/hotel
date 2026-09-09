@extends('admin.layouts.main')

@section('page_title', 'Dashboard')
@section('meta_description', 'Ringkasan operasional restoran.')
@section('header_title', 'Dashboard')
@section('header_subtitle', 'Ringkasan pesanan, pembayaran, reservasi, dan customer.')
@section('header_icon', 'activity')

@section('header_action')
    <a href="{{ route('management.orders.create') }}" class="btn btn-light">
        <i data-feather="plus" class="me-2"></i>
        Buat Pesanan
    </a>
@endsection

@section('content')
    <div class="row">
        @foreach ([['Pesanan Hari Ini', $statistics['orders_today'], 'shopping-cart', 'primary', null], ['Pesanan Aktif', $statistics['pending_orders'], 'clock', 'warning', route('management.orders.index')], ['Pendapatan Hari Ini', 'Rp' . number_format($statistics['revenue_today'], 0, ',', '.'), 'dollar-sign', 'success', route('management.payments.index')], ['Reservasi Hari Ini', $statistics['reservations_today'], 'calendar', 'info', route('management.reservations.index')]] as [$label, $value, $icon, $color, $url])
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="dashboard-stat-icon bg-{{ $color }}-soft text-{{ $color }}">
                            <i data-feather="{{ $icon }}"></i>
                        </div>

                        <div class="small text-muted mt-3">
                            {{ $label }}
                        </div>

                        <div class="dashboard-stat-value">
                            {{ $value }}
                        </div>

                        @if ($url)
                            <a href="{{ $url }}" class="small text-decoration-none">
                                Lihat detail
                                <i data-feather="arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    Pendapatan 6 Bulan Terakhir
                </div>

                <div class="card-body">
                    <div class="dashboard-chart-wrapper">
                        <canvas id="dashboardRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    Status Pesanan
                </div>

                <div class="card-body">
                    <div class="dashboard-chart-wrapper">
                        <canvas id="dashboardOrderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pesanan Terbaru</span>

                        <a href="{{ route('management.orders.index') }}" class="btn btn-outline-primary btn-sm">
                            Semua Pesanan
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('management.orders.show', $order) }}"
                                                class="fw-semibold text-decoration-none">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>

                                        <td>{{ $order->customer_name }}</td>

                                        <td>
                                            {{ ucwords(str_replace('_', ' ', $order->order_type)) }}
                                        </td>

                                        <td>
                                            <span class="badge bg-primary-soft text-primary">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>

                                        <td class="fw-semibold">
                                            Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Belum ada pesanan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    Reservasi Mendatang
                </div>

                <div class="card-body">
                    @forelse ($upcomingReservations as $reservation)
                        <a href="{{ route('management.reservations.show', $reservation) }}"
                            class="dashboard-reservation">
                            <div class="dashboard-reservation-date">
                                <strong>
                                    {{ $reservation->reservation_start->format('d') }}
                                </strong>

                                <span>
                                    {{ $reservation->reservation_start->format('M') }}
                                </span>
                            </div>

                            <div>
                                <div class="fw-semibold text-dark">
                                    {{ $reservation->customer_name }}
                                </div>

                                <div class="small text-muted">
                                    {{ $reservation->reservation_start->format('H:i') }}
                                    · {{ $reservation->guest_count }} orang
                                    · Meja {{ $reservation->restaurantTable?->table_number ?? '-' }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted">
                            Tidak ada reservasi mendatang.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <a href="{{ route('management.customers.index') }}"
                class="card dashboard-link-card shadow-sm text-decoration-none">
                <div class="card-body">
                    <i data-feather="users"></i>

                    <div>
                        <div class="text-muted small">Total Customer</div>
                        <div class="dashboard-link-value">
                            {{ number_format($statistics['customers']) }}
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 mb-4">
            <a href="{{ route('management.menu-items.index') }}"
                class="card dashboard-link-card shadow-sm text-decoration-none">
                <div class="card-body">
                    <i data-feather="alert-circle"></i>

                    <div>
                        <div class="text-muted small">Menu Tidak Tersedia</div>
                        <div class="dashboard-link-value">
                            {{ number_format($statistics['unavailable_menu']) }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .dashboard-stat-card {
            border: 0;
            border-radius: 0.75rem;
        }

        .dashboard-stat-icon {
            width: 2.75rem;
            height: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
        }

        .dashboard-stat-value {
            margin: 0.2rem 0;
            color: #363d47;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .dashboard-stat-card a svg {
            width: 0.8rem;
            height: 0.8rem;
        }

        .dashboard-chart-wrapper {
            position: relative;
            min-height: 300px;
        }

        .dashboard-reservation {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 0;
            border-bottom: 1px solid #e0e5ec;
            text-decoration: none;
        }

        .dashboard-reservation:last-child {
            border-bottom: 0;
        }

        .dashboard-reservation-date {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            flex-shrink: 0;
            border-radius: 0.65rem;
            color: #0061f2;
            background: rgba(0, 97, 242, 0.1);
            line-height: 1;
        }

        .dashboard-reservation-date span {
            margin-top: 0.15rem;
            font-size: 0.65rem;
            text-transform: uppercase;
        }

        .dashboard-link-card .card-body {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dashboard-link-card svg {
            width: 2rem;
            height: 2rem;
            color: #0061f2;
        }

        .dashboard-link-value {
            color: #363d47;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .table th {
            white-space: nowrap;
            background: #f8f9fa;
            color: #69707a;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        @media (max-width: 767.98px) {
            .table {
                min-width: 750px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Chart(
                document.getElementById('dashboardRevenueChart'), {
                    type: 'line',
                    data: {
                        labels: @json($revenueLabels),
                        datasets: [{
                            label: 'Pendapatan',
                            data: @json($revenueValues),
                            borderColor: '#0061f2',
                            backgroundColor: 'rgba(0, 97, 242, 0.1)',
                            pointBackgroundColor: '#0061f2',
                            borderWidth: 2,
                            fill: true,
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false,
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        return 'Rp' +
                                            new Intl.NumberFormat('id-ID')
                                            .format(value);
                                    },
                                },
                            }],
                        },
                    },
                }
            );

            new Chart(
                document.getElementById('dashboardOrderChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($orderStatusLabels),
                        datasets: [{
                            data: @json($orderStatusValues),
                            backgroundColor: [
                                '#f4a100',
                                '#00cfd5',
                                '#0061f2',
                                '#6900c7',
                                '#00ac69',
                                '#198754',
                                '#e81500',
                            ],
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom',
                        },
                    },
                }
            );
        });
    </script>
@endpush
