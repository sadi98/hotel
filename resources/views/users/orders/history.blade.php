@extends('users.layouts.main')

@push('style')
    <style>
        .restaurant-history-page {
            --history-text: #172033;
            --history-muted: #718096;
            --history-surface: #ffffff;
            --history-soft: #f3f9fc;
            --history-border: #dbeaf0;

            padding: 20px 0 100px;
            color: var(--history-text);
        }

        /* =====================================================
               HERO
            ====================================================== */

        .restaurant-history-hero {
            position: relative;
            margin-bottom: 20px;
            padding: 27px;
            overflow: hidden;
            border-radius: 26px;
            background:
                radial-gradient(circle at 92% 8%,
                    rgba(103, 232, 249, 0.38),
                    transparent 30%),
                linear-gradient(140deg,
                    #071827 0%,
                    #075985 53%,
                    #0ea5b7 100%);
            color: #ffffff;
            box-shadow: 0 22px 45px rgba(7, 89, 133, 0.22);
        }

        .restaurant-history-hero::after {
            position: absolute;
            right: -70px;
            bottom: -95px;
            width: 220px;
            height: 220px;
            border: 35px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            content: "";
        }

        .restaurant-history-hero-content {
            position: relative;
            z-index: 2;
            max-width: 650px;
        }

        .restaurant-history-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 13px;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .restaurant-history-eyebrow-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #67e8f9;
            box-shadow: 0 0 0 5px rgba(103, 232, 249, 0.15);
        }

        .restaurant-history-title {
            margin: 0 0 8px;
            color: #ffffff;
            font-size: clamp(27px, 5vw, 39px);
            font-weight: 800;
            line-height: 1.15;
        }

        .restaurant-history-description {
            max-width: 580px;
            margin: 0;
            color: rgba(255, 255, 255, 0.76);
            font-size: 13px;
            line-height: 1.7;
        }

        /* =====================================================
               SECTION HEADING
            ====================================================== */

        .restaurant-history-section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 13px;
            margin-bottom: 12px;
        }

        .restaurant-history-section-title {
            margin: 0;
            color: var(--history-text);
            font-size: 19px;
            font-weight: 800;
        }

        .restaurant-history-count {
            display: inline-flex;
            align-items: center;
            min-height: 31px;
            padding: 6px 11px;
            border: 1px solid var(--history-border);
            border-radius: 999px;
            background: var(--history-soft);
            color: #087ca5;
            font-size: 10px;
            font-weight: 800;
        }

        /* =====================================================
               ORDER LIST
            ====================================================== */

        .restaurant-history-list {
            display: grid;
            gap: 12px;
        }

        .restaurant-history-item {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 17px;
            align-items: center;
            min-width: 0;
            padding: 18px;
            overflow: hidden;
            border: 1px solid var(--history-border);
            border-radius: 19px;
            background: var(--history-surface);
            color: var(--history-text);
            text-decoration: none;
            box-shadow: 0 10px 27px rgba(29, 78, 99, 0.07);
            transition:
                transform 0.2s ease,
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .restaurant-history-item::before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 4px;
            border-radius: 4px;
            background: linear-gradient(to bottom,
                    #075985,
                    #06b6d4,
                    #67e8f9);
            content: "";
        }

        .restaurant-history-item:hover,
        .restaurant-history-item:focus {
            border-color: rgba(6, 182, 212, 0.5);
            color: var(--history-text);
            text-decoration: none;
            transform: translateY(-3px);
            box-shadow: 0 17px 34px rgba(7, 89, 133, 0.13);
        }

        .restaurant-history-item:focus-visible {
            outline: 3px solid rgba(34, 211, 238, 0.25);
            outline-offset: 3px;
        }

        .restaurant-history-content {
            display: flex;
            align-items: flex-start;
            min-width: 0;
            gap: 13px;
        }

        .restaurant-history-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            flex: 0 0 45px;
            border-radius: 14px;
            background: linear-gradient(135deg, #075985, #22d3ee);
            color: #ffffff;
            box-shadow: 0 9px 20px rgba(6, 182, 212, 0.2);
        }

        .restaurant-history-icon svg {
            width: 21px;
            height: 21px;
        }

        .restaurant-history-information {
            min-width: 0;
        }

        .restaurant-history-top {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 5px;
        }

        .restaurant-history-number {
            margin: 0;
            overflow-wrap: anywhere;
            color: var(--history-text);
            font-size: 14px;
            font-weight: 800;
        }

        .restaurant-history-date {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            color: var(--history-muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .restaurant-history-date svg {
            width: 14px;
            height: 14px;
            flex: 0 0 auto;
        }

        .restaurant-history-order-type {
            margin: 6px 0 0;
            color: var(--history-muted);
            font-size: 10px;
            font-weight: 650;
            text-transform: capitalize;
        }

        .restaurant-history-summary {
            display: grid;
            justify-items: end;
            min-width: 135px;
            gap: 9px;
        }

        .restaurant-history-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border: 1px solid transparent;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .restaurant-history-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .restaurant-history-status.is-pending {
            border-color: #fde68a;
            background: #fffbeb;
            color: #a16207;
        }

        .restaurant-history-status.is-confirmed,
        .restaurant-history-status.is-processing,
        .restaurant-history-status.is-preparing {
            border-color: #bae6fd;
            background: #f0f9ff;
            color: #0369a1;
        }

        .restaurant-history-status.is-ready {
            border-color: #a5f3fc;
            background: #ecfeff;
            color: #0e7490;
        }

        .restaurant-history-status.is-completed,
        .restaurant-history-status.is-paid,
        .restaurant-history-status.is-success {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #15803d;
        }

        .restaurant-history-status.is-cancelled,
        .restaurant-history-status.is-failed,
        .restaurant-history-status.is-refunded {
            border-color: #fecdd3;
            background: #fff1f2;
            color: #be123c;
        }

        .restaurant-history-status.is-default {
            border-color: var(--history-border);
            background: var(--history-soft);
            color: var(--history-muted);
        }

        .restaurant-history-price {
            margin: 0;
            color: #075985;
            font-size: 15px;
            font-weight: 850;
            white-space: nowrap;
        }

        .restaurant-history-view {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: var(--history-muted);
            font-size: 10px;
            font-weight: 700;
        }

        .restaurant-history-view svg {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
        }

        .restaurant-history-item:hover .restaurant-history-view svg {
            transform: translateX(3px);
        }

        /* =====================================================
               EMPTY STATE
            ====================================================== */

        .restaurant-history-empty {
            display: grid;
            justify-items: center;
            padding: 37px 20px;
            border: 1px dashed #b7d8e3;
            border-radius: 22px;
            background: var(--history-surface);
            text-align: center;
        }

        .restaurant-history-empty-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 68px;
            height: 68px;
            margin-bottom: 16px;
            border-radius: 22px;
            background: linear-gradient(135deg,
                    rgba(7, 89, 133, 0.12),
                    rgba(34, 211, 238, 0.18));
            color: #087ca5;
        }

        .restaurant-history-empty-icon svg {
            width: 31px;
            height: 31px;
        }

        .restaurant-history-empty-title {
            margin: 0 0 7px;
            color: var(--history-text);
            font-size: 18px;
            font-weight: 800;
        }

        .restaurant-history-empty-description {
            max-width: 390px;
            margin: 0 0 18px;
            color: var(--history-muted);
            font-size: 12px;
            line-height: 1.65;
        }

        .restaurant-history-button-icon {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
        }

        /* =====================================================
               PAGINATION
            ====================================================== */

        .restaurant-history-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 11px;
            margin-top: 18px;
        }

        .restaurant-history-pagination-spacer {
            flex: 1;
        }

        /* =====================================================
               DARK MODE
            ====================================================== */

        [data-theme="dark"] .restaurant-history-page {
            --history-text: #edf6fb;
            --history-muted: #9caebe;
            --history-surface: #111c28;
            --history-soft: #162532;
            --history-border: #294353;
        }

        [data-theme="dark"] .restaurant-history-item {
            box-shadow: 0 13px 30px rgba(0, 0, 0, 0.18);
        }

        [data-theme="dark"] .restaurant-history-status.is-pending {
            border-color: rgba(251, 191, 36, 0.25);
            background: rgba(146, 64, 14, 0.18);
            color: #fcd34d;
        }

        [data-theme="dark"] .restaurant-history-status.is-confirmed,
        [data-theme="dark"] .restaurant-history-status.is-processing,
        [data-theme="dark"] .restaurant-history-status.is-preparing {
            border-color: rgba(56, 189, 248, 0.25);
            background: rgba(3, 105, 161, 0.18);
            color: #7dd3fc;
        }

        [data-theme="dark"] .restaurant-history-status.is-ready {
            border-color: rgba(34, 211, 238, 0.25);
            background: rgba(14, 116, 144, 0.18);
            color: #67e8f9;
        }

        [data-theme="dark"] .restaurant-history-status.is-completed,
        [data-theme="dark"] .restaurant-history-status.is-paid,
        [data-theme="dark"] .restaurant-history-status.is-success {
            border-color: rgba(74, 222, 128, 0.25);
            background: rgba(21, 128, 61, 0.18);
            color: #86efac;
        }

        [data-theme="dark"] .restaurant-history-status.is-cancelled,
        [data-theme="dark"] .restaurant-history-status.is-failed,
        [data-theme="dark"] .restaurant-history-status.is-refunded {
            border-color: rgba(251, 113, 133, 0.25);
            background: rgba(159, 18, 57, 0.18);
            color: #fda4af;
        }

        [data-theme="dark"] .restaurant-history-price {
            color: #67e8f9;
        }

        /* =====================================================
               RESPONSIVE
            ====================================================== */

        @media (max-width: 600px) {
            .restaurant-history-page {
                padding-top: 14px;
            }

            .restaurant-history-hero {
                padding: 23px 19px;
                border-radius: 21px;
            }

            .restaurant-history-item {
                grid-template-columns: 1fr;
                padding: 16px;
            }

            .restaurant-history-summary {
                display: flex;
                align-items: center;
                justify-content: space-between;
                justify-items: initial;
                min-width: 0;
                padding-top: 12px;
                border-top: 1px solid var(--history-border);
            }

            .restaurant-history-view {
                display: none;
            }
        }

        @media (max-width: 400px) {
            .restaurant-history-content {
                gap: 10px;
            }

            .restaurant-history-icon {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
                border-radius: 12px;
            }

            .restaurant-history-section-heading {
                align-items: flex-start;
            }

            .restaurant-history-pagination {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .restaurant-history-pagination-spacer {
                display: none;
            }

            .restaurant-history-pagination .hotel-btn {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .restaurant-history-item,
            .restaurant-history-view svg {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-history-page">
            <section class="restaurant-history-hero">
                <div class="restaurant-history-hero-content">
                    <div class="restaurant-history-eyebrow">
                        <span class="restaurant-history-eyebrow-dot"></span>
                        Dining history
                    </div>

                    <h1 class="restaurant-history-title">
                        My Orders
                    </h1>

                    <p class="restaurant-history-description">
                        Track your current food orders and review every dining
                        experience you have enjoyed with us.
                    </p>
                </div>
            </section>

            <div class="restaurant-history-section-heading">
                <h2 class="restaurant-history-section-title">
                    Order history
                </h2>

                @if ($orders->total() > 0)
                    <span class="restaurant-history-count">
                        {{ $orders->total() }}
                        {{ $orders->total() === 1 ? 'order' : 'orders' }}
                    </span>
                @endif
            </div>

            @forelse ($orders as $order)
                @php
                    $orderStatus = strtolower($order->status);
                    $availableStatusClasses = [
                        'pending',
                        'confirmed',
                        'processing',
                        'preparing',
                        'ready',
                        'completed',
                        'paid',
                        'success',
                        'cancelled',
                        'failed',
                        'refunded',
                    ];

                    $orderStatusClass = in_array($orderStatus, $availableStatusClasses) ? $orderStatus : 'default';
                @endphp

                @if ($loop->first)
                    <div class="restaurant-history-list">
                @endif

                <a class="restaurant-history-item" href="{{ route('orders.show', $order) }}"
                    aria-label="View order {{ $order->order_number }}">
                    <div class="restaurant-history-content">
                        <span class="restaurant-history-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M6 2h12l2 5H4l2-5Z"></path>
                                <path d="M5 7v14h14V7"></path>
                                <path d="M9 11h6"></path>
                                <path d="M9 15h6"></path>
                            </svg>
                        </span>

                        <div class="restaurant-history-information">
                            <div class="restaurant-history-top">
                                <h3 class="restaurant-history-number">
                                    {{ $order->order_number }}
                                </h3>
                            </div>

                            <p class="restaurant-history-date">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                    <path d="M16 3v4"></path>
                                    <path d="M8 3v4"></path>
                                    <path d="M3 11h18"></path>
                                </svg>

                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>

                            @if ($order->order_type)
                                <p class="restaurant-history-order-type">
                                    {{ str_replace('_', ' ', $order->order_type) }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="restaurant-history-summary">
                        <span class="restaurant-history-status is-{{ $orderStatusClass }}">
                            <span class="restaurant-history-status-dot"></span>
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>

                        <p class="restaurant-history-price">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </p>

                        <span class="restaurant-history-view">
                            View details

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </span>
                    </div>
                </a>

                @if ($loop->last)
    </div>
    @endif
@empty
    <section class="restaurant-history-empty">
        <span class="restaurant-history-empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <path d="M6 2h12l2 5H4l2-5Z"></path>
                <path d="M5 7v14h14V7"></path>
                <path d="M9 12h6"></path>
            </svg>
        </span>

        <h2 class="restaurant-history-empty-title">
            No orders yet
        </h2>

        <p class="restaurant-history-empty-description">
            Your order history will appear here after you place your
            first food or dining package order.
        </p>

        <a class="hotel-btn hotel-btn-add hotel-btn-mobile-full" href="{{ route('menus.index') }}">
            <svg class="restaurant-history-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 11h18"></path>
                <path d="M12 2v4"></path>
                <path d="M12 18v4"></path>
                <path d="M5.6 5.6 8.4 8.4"></path>
                <path d="m15.6 15.6 2.8 2.8"></path>
            </svg>

            Browse menu
        </a>
    </section>
    @endforelse

    @if ($orders->hasPages())
        <nav class="restaurant-history-pagination" aria-label="Order history pagination">
            @if ($orders->previousPageUrl())
                <a class="hotel-btn hotel-btn-neutral hotel-btn-sm" href="{{ $orders->previousPageUrl() }}">
                    <svg class="restaurant-history-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>

                    Previous
                </a>
            @else
                <span class="restaurant-history-pagination-spacer"></span>
            @endif

            @if ($orders->nextPageUrl())
                <a class="hotel-btn hotel-btn-add hotel-btn-sm" href="{{ $orders->nextPageUrl() }}">
                    Next

                    <svg class="restaurant-history-button-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>
            @else
                <span class="restaurant-history-pagination-spacer"></span>
            @endif
        </nav>
    @endif
    </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderLinks = document.querySelectorAll('.restaurant-history-item');

            orderLinks.forEach(function(orderLink) {
                orderLink.addEventListener('click', function() {
                    orderLink.style.pointerEvents = 'none';
                    orderLink.style.opacity = '0.75';
                });
            });
        });
    </script>
@endpush
