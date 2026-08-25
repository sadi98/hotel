@extends('users.layouts.main')

@push('style')
    <style>
        .restaurant-order-page {
            --order-text: #172033;
            --order-muted: #718096;
            --order-surface: #ffffff;
            --order-soft: #f3f9fc;
            --order-border: #dbeaf0;

            padding: 20px 0 100px;
            color: var(--order-text);
        }

        /* =====================================================
               ORDER BANNER
            ====================================================== */

        .restaurant-order-banner {
            position: relative;
            margin-bottom: 18px;
            padding: 28px;
            overflow: hidden;
            border-radius: 27px;
            background:
                radial-gradient(circle at 92% 8%,
                    rgba(103, 232, 249, 0.4),
                    transparent 29%),
                linear-gradient(140deg,
                    #071827 0%,
                    #075985 54%,
                    #0ea5b7 100%);
            color: #ffffff;
            box-shadow: 0 24px 50px rgba(7, 89, 133, 0.24);
        }

        .restaurant-order-banner::after {
            position: absolute;
            right: -70px;
            bottom: -100px;
            width: 225px;
            height: 225px;
            border: 36px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            content: "";
        }

        .restaurant-order-banner-content {
            position: relative;
            z-index: 2;
        }

        .restaurant-order-banner-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .restaurant-order-eyebrow {
            margin: 0 0 7px;
            color: rgba(255, 255, 255, 0.66);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.13em;
            text-transform: uppercase;
        }

        .restaurant-order-number {
            margin: 0;
            color: #ffffff;
            font-size: clamp(24px, 5vw, 35px);
            font-weight: 800;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .restaurant-order-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            flex: 0 0 auto;
            padding: 8px 11px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            color: #ffffff;
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .restaurant-order-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #67e8f9;
            box-shadow: 0 0 0 4px rgba(103, 232, 249, 0.16);
        }

        .restaurant-order-banner-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .restaurant-order-banner-detail {
            min-width: 0;
            padding: 13px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(7px);
        }

        .restaurant-order-banner-label {
            display: block;
            margin-bottom: 5px;
            color: rgba(255, 255, 255, 0.62);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.09em;
            text-transform: uppercase;
        }

        .restaurant-order-banner-value {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 750;
            line-height: 1.5;
            text-transform: capitalize;
        }

        .restaurant-order-banner-value svg {
            width: 16px;
            height: 16px;
            flex: 0 0 auto;
            color: #a5f3fc;
        }

        /* =====================================================
               LAYOUT
            ====================================================== */

        .restaurant-order-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(290px, 0.85fr);
            gap: 18px;
            align-items: start;
        }

        .restaurant-order-main,
        .restaurant-order-side {
            display: grid;
            min-width: 0;
            gap: 15px;
        }

        .restaurant-order-panel {
            min-width: 0;
            padding: 21px;
            border: 1px solid var(--order-border);
            border-radius: 22px;
            background: var(--order-surface);
            box-shadow: 0 15px 34px rgba(29, 78, 99, 0.08);
        }

        .restaurant-order-panel-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 17px;
        }

        .restaurant-order-panel-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 41px;
            height: 41px;
            flex: 0 0 41px;
            border-radius: 13px;
            background: linear-gradient(135deg, #075985, #22d3ee);
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(6, 182, 212, 0.2);
        }

        .restaurant-order-panel-icon svg {
            width: 19px;
            height: 19px;
        }

        .restaurant-order-panel-title {
            margin: 0 0 2px;
            color: var(--order-text);
            font-size: 18px;
            font-weight: 800;
        }

        .restaurant-order-panel-subtitle {
            margin: 0;
            color: var(--order-muted);
            font-size: 10px;
        }

        /* =====================================================
               ORDER ITEMS
            ====================================================== */

        .restaurant-order-items {
            display: grid;
            gap: 11px;
        }

        .restaurant-order-item {
            padding: 14px;
            border: 1px solid var(--order-border);
            border-radius: 16px;
            background: var(--order-soft);
        }

        .restaurant-order-item-main {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: start;
        }

        .restaurant-order-item-content {
            display: flex;
            align-items: flex-start;
            min-width: 0;
            gap: 11px;
        }

        .restaurant-order-quantity {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 31px;
            height: 31px;
            flex: 0 0 auto;
            padding: 0 7px;
            border-radius: 10px;
            background: linear-gradient(135deg, #075985, #22d3ee);
            color: #ffffff;
            font-size: 11px;
            font-weight: 850;
        }

        .restaurant-order-item-information {
            min-width: 0;
        }

        .restaurant-order-item-name {
            margin: 1px 0 4px;
            overflow-wrap: anywhere;
            color: var(--order-text);
            font-size: 13px;
            font-weight: 800;
            line-height: 1.4;
        }

        .restaurant-order-item-type {
            margin: 0;
            color: var(--order-muted);
            font-size: 9px;
            font-weight: 650;
            text-transform: capitalize;
        }

        .restaurant-order-item-price {
            margin: 3px 0 0;
            color: #075985;
            font-size: 13px;
            font-weight: 850;
            white-space: nowrap;
        }

        .restaurant-order-components {
            display: grid;
            gap: 6px;
            margin-top: 11px;
            margin-left: 42px;
            padding-top: 10px;
            border-top: 1px dashed var(--order-border);
        }

        .restaurant-order-component {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--order-muted);
            font-size: 10px;
            line-height: 1.5;
        }

        .restaurant-order-component-name {
            display: flex;
            align-items: center;
            min-width: 0;
            gap: 7px;
        }

        .restaurant-order-component-dot {
            width: 5px;
            height: 5px;
            flex: 0 0 5px;
            border-radius: 50%;
            background: #06b6d4;
        }

        .restaurant-order-component-quantity {
            flex: 0 0 auto;
            color: var(--order-text);
            font-weight: 700;
        }

        .restaurant-order-note {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            margin: 11px 0 0 42px;
            padding: 9px 10px;
            border-radius: 11px;
            background: rgba(251, 191, 36, 0.1);
            color: #92400e;
            font-size: 9px;
            line-height: 1.5;
        }

        .restaurant-order-note svg {
            width: 13px;
            height: 13px;
            flex: 0 0 auto;
            margin-top: 1px;
        }

        /* =====================================================
               PAYMENT SUMMARY
            ====================================================== */

        .restaurant-order-summary-list {
            display: grid;
            gap: 0;
        }

        .restaurant-order-summary-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 11px 0;
            border-bottom: 1px solid var(--order-border);
            color: var(--order-muted);
            font-size: 12px;
        }

        .restaurant-order-summary-line:last-child {
            border-bottom: 0;
        }

        .restaurant-order-summary-line span:last-child {
            color: var(--order-text);
            font-weight: 700;
            text-align: right;
            white-space: nowrap;
        }

        .restaurant-order-total {
            margin-top: 10px;
            padding: 16px;
            border-radius: 16px;
            background:
                radial-gradient(circle at 90% 10%,
                    rgba(103, 232, 249, 0.3),
                    transparent 35%),
                linear-gradient(135deg, #071827, #075985, #0ea5b7);
            color: #ffffff;
        }

        .restaurant-order-total-label {
            display: block;
            margin-bottom: 5px;
            color: rgba(255, 255, 255, 0.68);
            font-size: 10px;
            font-weight: 750;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .restaurant-order-total-value {
            margin: 0;
            color: #ffffff;
            font-size: clamp(23px, 5vw, 30px);
            font-weight: 850;
            line-height: 1.2;
        }

        /* =====================================================
               PAYMENT INFORMATION
            ====================================================== */

        .restaurant-order-payment-status {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 13px;
            border: 1px solid var(--order-border);
            border-radius: 15px;
            background: var(--order-soft);
        }

        .restaurant-order-payment-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            border-radius: 11px;
            background: linear-gradient(135deg, #075985, #22d3ee);
            color: #ffffff;
        }

        .restaurant-order-payment-icon svg {
            width: 17px;
            height: 17px;
        }

        .restaurant-order-payment-label {
            display: block;
            margin-bottom: 2px;
            color: var(--order-muted);
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .restaurant-order-payment-value {
            display: block;
            color: var(--order-text);
            font-size: 12px;
            font-weight: 850;
            text-transform: uppercase;
        }

        .restaurant-order-info-list {
            display: grid;
            gap: 10px;
            margin: 15px 0 0;
            padding: 0;
            list-style: none;
        }

        .restaurant-order-info-item {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            color: var(--order-muted);
            font-size: 10px;
            line-height: 1.55;
        }

        .restaurant-order-info-item svg {
            width: 15px;
            height: 15px;
            flex: 0 0 auto;
            margin-top: 1px;
            color: #0891b2;
        }

        /* =====================================================
               ACTIONS
            ====================================================== */

        .restaurant-order-actions {
            display: grid;
            gap: 9px;
        }

        .restaurant-order-action-icon {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
        }

        /* =====================================================
               DARK MODE
            ====================================================== */

        [data-theme="dark"] .restaurant-order-page {
            --order-text: #edf6fb;
            --order-muted: #9caebe;
            --order-surface: #111c28;
            --order-soft: #162532;
            --order-border: #294353;
        }

        [data-theme="dark"] .restaurant-order-panel {
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] .restaurant-order-item-price {
            color: #67e8f9;
        }

        [data-theme="dark"] .restaurant-order-note {
            background: rgba(146, 64, 14, 0.2);
            color: #fcd34d;
        }

        /* =====================================================
               RESPONSIVE
            ====================================================== */

        @media (max-width: 820px) {
            .restaurant-order-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .restaurant-order-page {
                padding-top: 14px;
            }

            .restaurant-order-banner {
                padding: 22px 18px;
                border-radius: 22px;
            }

            .restaurant-order-banner-top {
                display: grid;
            }

            .restaurant-order-status {
                width: max-content;
            }

            .restaurant-order-banner-grid {
                grid-template-columns: 1fr;
            }

            .restaurant-order-panel {
                padding: 17px;
                border-radius: 19px;
            }
        }

        @media (max-width: 430px) {
            .restaurant-order-item-main {
                grid-template-columns: 1fr;
            }

            .restaurant-order-item-price {
                margin-left: 42px;
            }

            .restaurant-order-component {
                align-items: flex-start;
            }

            .restaurant-order-summary-line {
                align-items: flex-start;
            }
        }

        @media (max-width: 350px) {

            .restaurant-order-banner,
            .restaurant-order-panel {
                border-radius: 16px;
            }

            .restaurant-order-panel-heading {
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-order-page">
            @php
                $orderStatus = strtolower($order->status);
                $paymentStatus = strtolower($order->payment_status);
            @endphp

            <header class="restaurant-order-banner">
                <div class="restaurant-order-banner-content">
                    <div class="restaurant-order-banner-top">
                        <div>
                            <p class="restaurant-order-eyebrow">
                                Order number
                            </p>

                            <h1 class="restaurant-order-number">
                                {{ $order->order_number }}
                            </h1>
                        </div>

                        <span class="restaurant-order-status">
                            <span class="restaurant-order-status-dot"></span>
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>

                    <div class="restaurant-order-banner-grid">
                        <section class="restaurant-order-banner-detail">
                            <span class="restaurant-order-banner-label">
                                Order type
                            </span>

                            <div class="restaurant-order-banner-value">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 11h16"></path>
                                    <path d="M12 3v3"></path>
                                    <path d="M6 11a6 6 0 0 1 12 0"></path>
                                    <path d="M3 17h18"></path>
                                </svg>

                                {{ str_replace('_', ' ', $order->order_type) }}
                            </div>
                        </section>

                        <section class="restaurant-order-banner-detail">
                            <span class="restaurant-order-banner-label">
                                Payment
                            </span>

                            <div class="restaurant-order-banner-value">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                </svg>

                                {{ str_replace('_', ' ', $order->payment_status) }}
                            </div>
                        </section>

                        <section class="restaurant-order-banner-detail">
                            <span class="restaurant-order-banner-label">
                                Ordered at
                            </span>

                            <div class="restaurant-order-banner-value">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 7v5l3 2"></path>
                                </svg>

                                {{ $order->created_at->format('d M Y, H:i') }}
                            </div>
                        </section>
                    </div>
                </div>
            </header>

            <div class="restaurant-order-layout">
                <div class="restaurant-order-main">
                    <section class="restaurant-order-panel">
                        <div class="restaurant-order-panel-heading">
                            <span class="restaurant-order-panel-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M6 2h12l2 5H4l2-5Z"></path>
                                    <path d="M5 7v14h14V7"></path>
                                    <path d="M9 11h6"></path>
                                </svg>
                            </span>

                            <div>
                                <h2 class="restaurant-order-panel-title">
                                    Order items
                                </h2>

                                <p class="restaurant-order-panel-subtitle">
                                    {{ $order->items->count() }}
                                    {{ $order->items->count() === 1 ? 'item' : 'items' }}
                                    in this order
                                </p>
                            </div>
                        </div>

                        <div class="restaurant-order-items">
                            @foreach ($order->items as $item)
                                <article class="restaurant-order-item">
                                    <div class="restaurant-order-item-main">
                                        <div class="restaurant-order-item-content">
                                            <span class="restaurant-order-quantity">
                                                {{ $item->quantity }}×
                                            </span>

                                            <div class="restaurant-order-item-information">
                                                <h3 class="restaurant-order-item-name">
                                                    {{ $item->item_name }}
                                                </h3>

                                                @if ($item->item_type)
                                                    <p class="restaurant-order-item-type">
                                                        {{ str_replace('_', ' ', $item->item_type) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="restaurant-order-item-price">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    @if ($item->components->isNotEmpty())
                                        <div class="restaurant-order-components">
                                            @foreach ($item->components as $component)
                                                <div class="restaurant-order-component">
                                                    <span class="restaurant-order-component-name">
                                                        <span class="restaurant-order-component-dot"></span>

                                                        {{ $component->menu_name }}
                                                    </span>

                                                    <span class="restaurant-order-component-quantity">
                                                        {{ $component->total_quantity }}×
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if ($item->note)
                                        <p class="restaurant-order-note">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z">
                                                </path>
                                            </svg>

                                            {{ $item->note }}
                                        </p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                </div>

                <aside class="restaurant-order-side">
                    <section class="restaurant-order-panel">
                        <div class="restaurant-order-panel-heading">
                            <span class="restaurant-order-panel-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                    <path d="M7 15h2"></path>
                                </svg>
                            </span>

                            <div>
                                <h2 class="restaurant-order-panel-title">
                                    Payment summary
                                </h2>

                                <p class="restaurant-order-panel-subtitle">
                                    Complete order price details
                                </p>
                            </div>
                        </div>

                        <div class="restaurant-order-summary-list">
                            <div class="restaurant-order-summary-line">
                                <span>Subtotal</span>

                                <span>
                                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="restaurant-order-summary-line">
                                <span>Service charge</span>

                                <span>
                                    Rp {{ number_format($order->service_charge_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="restaurant-order-summary-line">
                                <span>Tax</span>

                                <span>
                                    Rp {{ number_format($order->tax_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="restaurant-order-total">
                            <span class="restaurant-order-total-label">
                                Grand total
                            </span>

                            <h3 class="restaurant-order-total-value">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </h3>
                        </div>
                    </section>

                    <section class="restaurant-order-panel">
                        <div class="restaurant-order-panel-heading">
                            <span class="restaurant-order-panel-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                            </span>

                            <div>
                                <h2 class="restaurant-order-panel-title">
                                    Order information
                                </h2>

                                <p class="restaurant-order-panel-subtitle">
                                    Current order and payment status
                                </p>
                            </div>
                        </div>

                        <div class="restaurant-order-payment-status">
                            <span class="restaurant-order-payment-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                </svg>
                            </span>

                            <div>
                                <span class="restaurant-order-payment-label">
                                    Payment status
                                </span>

                                <strong class="restaurant-order-payment-value">
                                    {{ str_replace('_', ' ', $order->payment_status) }}
                                </strong>
                            </div>
                        </div>

                        <ul class="restaurant-order-info-list">
                            <li class="restaurant-order-info-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 16v-4"></path>
                                    <path d="M12 8h.01"></path>
                                </svg>

                                Keep your order number available if you need
                                assistance from our restaurant team.
                            </li>

                            <li class="restaurant-order-info-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>

                                The order status will be updated as your food
                                is prepared and completed.
                            </li>
                        </ul>
                    </section>

                    <div class="restaurant-order-actions">
                        <a class="hotel-btn hotel-btn-add hotel-btn-w-100" href="{{ route('menus.index') }}">
                            <svg class="restaurant-order-action-icon" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>

                            Order more food
                        </a>

                        <a class="hotel-btn hotel-btn-neutral hotel-btn-w-100" href="{{ route('orders.history') }}">
                            <svg class="restaurant-order-action-icon" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="m15 18-6-6 6-6"></path>
                                <path d="M9 12h10"></path>
                            </svg>

                            Back to order history
                        </a>
                    </div>
                </aside>
            </div>
        </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderActionLinks = document.querySelectorAll(
                '.restaurant-order-actions a'
            );

            orderActionLinks.forEach(function(actionLink) {
                actionLink.addEventListener('click', function() {
                    actionLink.style.pointerEvents = 'none';
                    actionLink.style.opacity = '0.75';
                });
            });
        });
    </script>
@endpush
