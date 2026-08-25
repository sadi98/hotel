@extends('users.layouts.main')

@push('style')
    <style>
        /* =====================================================
               RESTAURANT CART PAGE
            ====================================================== */

        .restaurant-cart-page {
            --cart-navy: #071a32;
            --cart-blue: #075985;
            --cart-blue-light: #087ea4;
            --cart-cyan: #0ea5b7;
            --cart-cyan-light: #67e8f9;
            --cart-text: #172033;
            --cart-muted: #718096;
            --cart-surface: #ffffff;
            --cart-soft: #f3f9fc;
            --cart-border: #dcebf0;
            --cart-danger: #d92d4f;
            --cart-success: #16805b;
            --cart-shadow: 0 15px 38px rgba(20, 76, 104, 0.10);

            width: 100%;
            padding: 20px 0 110px;
            color: var(--cart-text);
        }

        /* =====================================================
               SUCCESS ALERT
            ====================================================== */

        .restaurant-cart-alert {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: 17px;
            padding: 13px 15px;
            border: 1px solid rgba(22, 128, 91, 0.18);
            border-radius: 15px;
            background: #ecfbf3;
            color: #12643e;
            box-shadow: 0 8px 22px rgba(22, 128, 91, 0.08);
        }

        .restaurant-cart-alert-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            border-radius: 50%;
            background: #18a568;
            color: #ffffff;
        }

        .restaurant-cart-alert-icon svg {
            width: 16px;
            height: 16px;
        }

        .restaurant-cart-alert-content {
            min-width: 0;
        }

        .restaurant-cart-alert-title {
            display: block;
            margin-bottom: 2px;
            font-size: 11px;
            font-weight: 800;
        }

        .restaurant-cart-alert-message {
            margin: 0;
            font-size: 10px;
            line-height: 1.6;
        }

        /* =====================================================
               CART HERO
            ====================================================== */

        .restaurant-cart-hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: 185px;
            margin-bottom: 20px;
            padding: 29px;
            overflow: hidden;
            border-radius: 26px;
            background:
                radial-gradient(circle at 91% 12%,
                    rgba(103, 232, 249, 0.42),
                    transparent 27%),
                radial-gradient(circle at 72% 110%,
                    rgba(59, 130, 246, 0.32),
                    transparent 38%),
                linear-gradient(135deg,
                    #071a32 0%,
                    #075985 52%,
                    #0ea5b7 100%);
            color: #ffffff;
            box-shadow: 0 22px 45px rgba(7, 89, 133, 0.22);
        }

        .restaurant-cart-hero::after {
            position: absolute;
            right: -55px;
            bottom: -80px;
            width: 190px;
            height: 190px;
            border: 30px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            content: "";
        }

        .restaurant-cart-hero-content {
            position: relative;
            z-index: 2;
            min-width: 0;
            max-width: 600px;
        }

        .restaurant-cart-hero-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 12px;
            padding: 6px 10px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
            color: #a5f3fc;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            backdrop-filter: blur(9px);
        }

        .restaurant-cart-hero-label::before {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #67e8f9;
            content: "";
            box-shadow: 0 0 10px #67e8f9;
        }

        .restaurant-cart-title {
            margin: 0 0 8px;
            color: #ffffff;
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 850;
            line-height: 1.12;
            letter-spacing: -1px;
        }

        .restaurant-cart-hero-description {
            max-width: 530px;
            margin: 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 11px;
            line-height: 1.7;
        }

        .restaurant-cart-count {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 92px;
            width: 92px;
            height: 92px;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.11);
            color: #ffffff;
            text-align: center;
            backdrop-filter: blur(12px);
        }

        .restaurant-cart-count-number {
            display: block;
            margin-bottom: 3px;
            font-size: 26px;
            font-weight: 900;
            line-height: 1;
        }

        .restaurant-cart-count-label {
            display: block;
            color: rgba(255, 255, 255, 0.65);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        /* =====================================================
               MAIN LAYOUT
            ====================================================== */

        .restaurant-cart-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(290px, 0.7fr);
            gap: 18px;
            align-items: start;
        }

        .restaurant-cart-list-column {
            min-width: 0;
        }

        .restaurant-cart-summary-column {
            min-width: 0;
        }

        .restaurant-cart-section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 13px;
        }

        .restaurant-cart-section-label {
            display: block;
            margin-bottom: 4px;
            color: var(--cart-blue-light);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .restaurant-cart-section-title {
            margin: 0;
            color: var(--cart-text);
            font-size: 20px;
            font-weight: 850;
        }

        .restaurant-cart-menu-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--cart-blue);
            font-size: 10px;
            font-weight: 800;
            text-decoration: none;
        }

        .restaurant-cart-menu-link:hover {
            color: var(--cart-cyan);
            text-decoration: none;
        }

        .restaurant-cart-menu-link svg {
            width: 14px;
            height: 14px;
        }

        /* =====================================================
               CART ITEM
            ====================================================== */

        .restaurant-cart-items {
            display: grid;
            gap: 13px;
        }

        .restaurant-cart-item {
            position: relative;
            min-width: 0;
            padding: 17px;
            overflow: hidden;
            border: 1px solid var(--cart-border);
            border-radius: 20px;
            background: var(--cart-surface);
            box-shadow: var(--cart-shadow);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .restaurant-cart-item:hover {
            border-color: rgba(14, 165, 183, 0.28);
            box-shadow: 0 20px 42px rgba(20, 76, 104, 0.15);
            transform: translateY(-3px);
        }

        .restaurant-cart-item-top {
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
            margin-bottom: 15px;
        }

        .restaurant-cart-item-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background:
                linear-gradient(135deg,
                    rgba(7, 89, 133, 0.12),
                    rgba(14, 165, 183, 0.18));
            color: var(--cart-blue);
        }

        .restaurant-cart-item-icon svg {
            width: 24px;
            height: 24px;
        }

        .restaurant-cart-item-identity {
            min-width: 0;
        }

        .restaurant-cart-item-type {
            display: block;
            margin-bottom: 3px;
            color: var(--cart-blue-light);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.9px;
            text-transform: uppercase;
        }

        .restaurant-cart-name {
            margin: 0 0 4px;
            overflow: hidden;
            color: var(--cart-text);
            font-size: 15px;
            font-weight: 850;
            line-height: 1.4;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .restaurant-cart-unit-price {
            color: var(--cart-muted);
            font-size: 10px;
            font-weight: 650;
        }

        .restaurant-cart-subtotal-wrapper {
            min-width: 115px;
            text-align: right;
        }

        .restaurant-cart-subtotal-label {
            display: block;
            margin-bottom: 3px;
            color: var(--cart-muted);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.7px;
            text-transform: uppercase;
        }

        .restaurant-cart-subtotal {
            color: var(--cart-blue);
            font-size: 16px;
            font-weight: 900;
            white-space: nowrap;
        }

        /* =====================================================
               ITEM FORM
            ====================================================== */

        .restaurant-cart-item-form {
            display: grid;
            grid-template-columns: minmax(135px, 0.35fr) minmax(0, 1fr) auto;
            gap: 10px;
            align-items: end;
            padding-top: 14px;
            border-top: 1px solid var(--cart-border);
        }

        .restaurant-cart-field {
            display: grid;
            gap: 6px;
            min-width: 0;
        }

        .restaurant-cart-field-label {
            color: var(--cart-text);
            font-size: 9px;
            font-weight: 800;
        }

        .restaurant-cart-quantity {
            display: grid;
            grid-template-columns: 38px minmax(42px, 1fr) 38px;
            min-height: 42px;
            overflow: hidden;
            border: 1px solid var(--cart-border);
            border-radius: 12px;
            background: var(--cart-soft);
        }

        .restaurant-cart-quantity-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            background: transparent;
            color: var(--cart-blue);
            font-family: inherit;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
            transition:
                color 0.2s ease,
                background-color 0.2s ease;
        }

        .restaurant-cart-quantity-button:hover {
            background: rgba(14, 165, 183, 0.11);
            color: var(--cart-cyan);
        }

        .restaurant-cart-quantity-button:disabled {
            color: #a7b2bc;
            cursor: not-allowed;
        }

        .restaurant-cart-quantity-input {
            width: 100%;
            min-width: 0;
            padding: 0 4px;
            border: 0;
            outline: none;
            background: transparent;
            color: var(--cart-text);
            font-family: inherit;
            font-size: 12px;
            font-weight: 850;
            text-align: center;
            appearance: textfield;
        }

        .restaurant-cart-quantity-input::-webkit-inner-spin-button,
        .restaurant-cart-quantity-input::-webkit-outer-spin-button {
            margin: 0;
            appearance: none;
        }

        .restaurant-cart-note-input {
            width: 100%;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid var(--cart-border);
            border-radius: 12px;
            outline: none;
            background: var(--cart-soft);
            color: var(--cart-text);
            font-family: inherit;
            font-size: 10px;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background-color 0.2s ease;
        }

        .restaurant-cart-note-input:focus {
            border-color: var(--cart-cyan);
            background: var(--cart-surface);
            box-shadow: 0 0 0 4px rgba(14, 165, 183, 0.11);
        }

        .restaurant-cart-note-input::placeholder {
            color: #9aa7b3;
        }

        /* =====================================================
               BUTTONS
            ====================================================== */

        .restaurant-cart-update-button,
        .restaurant-cart-remove-button,
        .restaurant-cart-clear-button,
        .restaurant-cart-checkout-button,
        .restaurant-cart-browse-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 42px;
            border: 0;
            border-radius: 12px;
            font-family: inherit;
            font-size: 10px;
            font-weight: 800;
            line-height: 1.2;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                opacity 0.2s ease;
        }

        .restaurant-cart-update-button {
            padding: 10px 15px;
            background: linear-gradient(135deg,
                    #075985,
                    #087ea4 48%,
                    #18b9c7);
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(8, 126, 164, 0.21);
        }

        .restaurant-cart-update-button:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 11px 22px rgba(8, 126, 164, 0.28);
        }

        .restaurant-cart-update-button svg,
        .restaurant-cart-remove-button svg,
        .restaurant-cart-checkout-button svg,
        .restaurant-cart-browse-button svg {
            width: 14px;
            height: 14px;
        }

        .restaurant-cart-remove-form {
            position: absolute;
            top: 8px;
            right: 8px;
            margin: 0;
        }

        .restaurant-cart-remove-button {
            width: 34px;
            min-width: 34px;
            min-height: 34px;
            padding: 0;
            border: 1px solid rgba(217, 45, 79, 0.13);
            border-radius: 10px;
            background: #fff0f3;
            color: var(--cart-danger);
        }

        .restaurant-cart-remove-button:hover {
            background: var(--cart-danger);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .restaurant-cart-remove-button:disabled,
        .restaurant-cart-update-button:disabled,
        .restaurant-cart-clear-button:disabled {
            opacity: 0.60;
            cursor: not-allowed;
            transform: none;
        }

        /* =====================================================
               SUMMARY
            ====================================================== */

        .restaurant-cart-summary {
            position: sticky;
            top: 15px;
            overflow: hidden;
            border-radius: 22px;
            background:
                radial-gradient(circle at 100% 0,
                    rgba(103, 232, 249, 0.28),
                    transparent 34%),
                linear-gradient(145deg,
                    #071827 0%,
                    #075985 58%,
                    #0e849e 100%);
            color: #ffffff;
            box-shadow: 0 20px 42px rgba(7, 65, 91, 0.22);
        }

        .restaurant-cart-summary-content {
            position: relative;
            z-index: 2;
            padding: 22px;
        }

        .restaurant-cart-summary-label {
            display: inline-flex;
            margin-bottom: 12px;
            padding: 6px 9px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.09);
            color: #a5f3fc;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .restaurant-cart-summary-title {
            margin: 0 0 5px;
            color: #ffffff;
            font-size: 20px;
            font-weight: 850;
        }

        .restaurant-cart-summary-description {
            margin: 0 0 20px;
            color: rgba(255, 255, 255, 0.64);
            font-size: 10px;
            line-height: 1.6;
        }

        .restaurant-cart-summary-details {
            display: grid;
            gap: 12px;
            margin-bottom: 17px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(9px);
        }

        .restaurant-cart-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: rgba(255, 255, 255, 0.73);
            font-size: 10px;
        }

        .restaurant-cart-summary-row strong {
            color: #ffffff;
            font-weight: 800;
        }

        .restaurant-cart-summary-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.13);
        }

        .restaurant-cart-summary-total-label {
            display: block;
            margin-bottom: 4px;
            color: rgba(255, 255, 255, 0.57);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .restaurant-cart-summary-total {
            display: block;
            color: #ffffff;
            font-size: clamp(23px, 4vw, 29px);
            font-weight: 900;
            line-height: 1.2;
        }

        .restaurant-cart-checkout-button {
            width: 100%;
            min-height: 48px;
            padding: 12px 16px;
            background: #ffffff;
            color: var(--cart-blue);
            box-shadow: 0 10px 23px rgba(0, 0, 0, 0.15);
        }

        .restaurant-cart-checkout-button:hover {
            color: var(--cart-blue);
            transform: translateY(-2px);
            box-shadow: 0 14px 27px rgba(0, 0, 0, 0.20);
        }

        .restaurant-cart-clear-form {
            margin-top: 11px;
        }

        .restaurant-cart-clear-button {
            width: 100%;
            min-height: 39px;
            padding: 9px 13px;
            border: 1px solid rgba(217, 45, 79, 0.17);
            background: #fff0f3;
            color: var(--cart-danger);
        }

        .restaurant-cart-clear-button:hover {
            background: var(--cart-danger);
            color: #ffffff;
        }

        /* =====================================================
               EMPTY CART
            ====================================================== */

        .restaurant-cart-empty {
            padding: 45px 22px;
            border: 1px dashed rgba(8, 126, 164, 0.28);
            border-radius: 23px;
            background: rgba(243, 249, 252, 0.82);
            text-align: center;
        }

        .restaurant-cart-empty-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 74px;
            height: 74px;
            margin-bottom: 16px;
            border-radius: 23px;
            background: linear-gradient(135deg,
                    #075985,
                    #0ea5b7);
            color: #ffffff;
            box-shadow: 0 14px 28px rgba(8, 126, 164, 0.25);
        }

        .restaurant-cart-empty-icon svg {
            width: 33px;
            height: 33px;
        }

        .restaurant-cart-empty-title {
            margin: 0 0 7px;
            color: var(--cart-text);
            font-size: 20px;
            font-weight: 850;
        }

        .restaurant-cart-empty-description {
            max-width: 430px;
            margin: 0 auto 18px;
            color: var(--cart-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .restaurant-cart-browse-button {
            min-height: 44px;
            padding: 11px 18px;
            background: linear-gradient(135deg,
                    #075985,
                    #087ea4 48%,
                    #18b9c7);
            color: #ffffff;
            box-shadow: 0 9px 21px rgba(8, 126, 164, 0.23);
        }

        .restaurant-cart-browse-button:hover {
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* =====================================================
               DARK MODE
            ====================================================== */

        [data-theme="dark"] .restaurant-cart-page {
            --cart-text: #edf5fa;
            --cart-muted: #9daab6;
            --cart-surface: #20272e;
            --cart-soft: #272f37;
            --cart-border: #35414b;
            --cart-shadow: 0 15px 35px rgba(0, 0, 0, 0.23);
        }

        [data-theme="dark"] .restaurant-cart-item {
            border-color: var(--cart-border);
            background: var(--cart-surface);
        }

        [data-theme="dark"] .restaurant-cart-item-icon {
            background:
                linear-gradient(135deg,
                    rgba(7, 89, 133, 0.28),
                    rgba(14, 165, 183, 0.16));
            color: #67e8f9;
        }

        [data-theme="dark"] .restaurant-cart-subtotal,
        [data-theme="dark"] .restaurant-cart-section-label,
        [data-theme="dark"] .restaurant-cart-item-type,
        [data-theme="dark"] .restaurant-cart-menu-link {
            color: #67e8f9;
        }

        [data-theme="dark"] .restaurant-cart-quantity,
        [data-theme="dark"] .restaurant-cart-note-input {
            border-color: var(--cart-border);
            background: var(--cart-soft);
            color: var(--cart-text);
        }

        [data-theme="dark"] .restaurant-cart-note-input:focus {
            border-color: var(--cart-cyan);
            background: #29343e;
        }

        [data-theme="dark"] .restaurant-cart-remove-button {
            border-color: rgba(251, 113, 133, 0.17);
            background: rgba(159, 18, 57, 0.20);
            color: #fb8ba0;
        }

        [data-theme="dark"] .restaurant-cart-clear-button {
            border-color: rgba(251, 113, 133, 0.17);
            background: rgba(159, 18, 57, 0.20);
            color: #fb8ba0;
        }

        [data-theme="dark"] .restaurant-cart-empty {
            border-color: rgba(103, 232, 249, 0.20);
            background: rgba(8, 126, 164, 0.08);
        }

        [data-theme="dark"] .restaurant-cart-alert {
            border-color: rgba(52, 211, 153, 0.16);
            background: rgba(5, 83, 58, 0.36);
            color: #a7f3d0;
        }

        /* =====================================================
               RESPONSIVE
            ====================================================== */

        @media (max-width: 950px) {
            .restaurant-cart-layout {
                grid-template-columns: 1fr;
            }

            .restaurant-cart-summary {
                position: relative;
                top: auto;
            }
        }

        @media (max-width: 700px) {
            .restaurant-cart-hero {
                min-height: auto;
                padding: 25px 21px;
                border-radius: 22px;
            }

            .restaurant-cart-count {
                flex-basis: 74px;
                width: 74px;
                height: 74px;
                border-radius: 22px;
            }

            .restaurant-cart-count-number {
                font-size: 22px;
            }

            .restaurant-cart-item-form {
                grid-template-columns: minmax(120px, 0.40fr) minmax(0, 1fr);
            }

            .restaurant-cart-update-button {
                grid-column: 1 / -1;
                width: 100%;
            }
        }

        @media (max-width: 520px) {
            .restaurant-cart-page {
                padding-top: 14px;
            }

            .restaurant-cart-hero {
                display: block;
            }

            .restaurant-cart-count {
                width: auto;
                height: auto;
                margin-top: 17px;
                padding: 12px 15px;
                border-radius: 15px;
            }

            .restaurant-cart-count-number,
            .restaurant-cart-count-label {
                display: inline;
            }

            .restaurant-cart-count-number {
                margin-right: 5px;
                font-size: 17px;
            }

            .restaurant-cart-item {
                padding: 15px;
                border-radius: 18px;
            }

            .restaurant-cart-item-top {
                grid-template-columns: 45px minmax(0, 1fr);
                padding-right: 32px;
            }

            .restaurant-cart-item-icon {
                width: 45px;
                height: 45px;
                border-radius: 14px;
            }

            .restaurant-cart-subtotal-wrapper {
                grid-column: 1 / -1;
                padding-left: 57px;
                text-align: left;
            }

            .restaurant-cart-item-form {
                grid-template-columns: 1fr;
            }

            .restaurant-cart-update-button {
                grid-column: auto;
            }

            .restaurant-cart-section-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
            }

            .restaurant-cart-summary-content {
                padding: 19px;
            }
        }

        @media (max-width: 360px) {
            .restaurant-cart-hero {
                padding: 22px 17px;
            }

            .restaurant-cart-subtotal-wrapper {
                padding-left: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .restaurant-cart-item,
            .restaurant-cart-update-button,
            .restaurant-cart-remove-button,
            .restaurant-cart-checkout-button,
            .restaurant-cart-browse-button {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-cart-page">

            {{-- Success message --}}
            @if (session('success'))
                <div class="restaurant-cart-alert" role="alert">
                    <span class="restaurant-cart-alert-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5"></path>
                        </svg>
                    </span>

                    <div class="restaurant-cart-alert-content">
                        <span class="restaurant-cart-alert-title">
                            Successfully updated
                        </span>

                        <p class="restaurant-cart-alert-message">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- Hero --}}
            <section class="restaurant-cart-hero">
                <div class="restaurant-cart-hero-content">
                    <span class="restaurant-cart-hero-label">
                        Your dining selection
                    </span>

                    <h1 class="restaurant-cart-title">
                        Your Cart
                    </h1>

                    <p class="restaurant-cart-hero-description">
                        Review your selected food, beverages, and dining
                        packages before continuing to checkout.
                    </p>
                </div>

                <div class="restaurant-cart-count">
                    <div>
                        <span class="restaurant-cart-count-number">
                            {{ $cart->items->count() }}
                        </span>

                        <span class="restaurant-cart-count-label">
                            Items
                        </span>
                    </div>
                </div>
            </section>

            @if ($cart->items->isEmpty())
                {{-- Empty cart --}}
                <section class="restaurant-cart-empty">
                    <span class="restaurant-cart-empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="20" r="1"></circle>
                            <circle cx="19" cy="20" r="1"></circle>
                            <path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 7H6"></path>
                        </svg>
                    </span>

                    <h2 class="restaurant-cart-empty-title">
                        Your cart is empty
                    </h2>

                    <p class="restaurant-cart-empty-description">
                        Explore our chef-crafted dishes, premium beverages, and
                        exclusive hotel dining packages.
                    </p>

                    <a class="restaurant-cart-browse-button" href="{{ route('menus.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="m13 6 6 6-6 6"></path>
                        </svg>

                        Browse menu
                    </a>
                </section>
            @else
                <div class="restaurant-cart-layout">

                    {{-- Cart items --}}
                    <section class="restaurant-cart-list-column">
                        <div class="restaurant-cart-section-header">
                            <div>
                                <span class="restaurant-cart-section-label">
                                    Order details
                                </span>

                                <h2 class="restaurant-cart-section-title">
                                    Selected items
                                </h2>
                            </div>

                            <a class="restaurant-cart-menu-link" href="{{ route('menus.index') }}">
                                Add more items

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="m13 6 6 6-6 6"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="restaurant-cart-items">
                            @foreach ($cart->items as $item)
                                <article class="restaurant-cart-item">

                                    {{-- Remove --}}
                                    <form class="restaurant-cart-remove-form" action="{{ route('cart.destroy', $item) }}"
                                        method="POST" data-cart-remove-form>
                                        @csrf
                                        @method('DELETE')

                                        <button class="restaurant-cart-remove-button" type="submit"
                                            aria-label="Remove {{ $item->name }}" title="Remove item">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M8 6V4h8v2"></path>
                                                <path d="M19 6l-1 14H6L5 6"></path>
                                                <path d="M10 11v5"></path>
                                                <path d="M14 11v5"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    <div class="restaurant-cart-item-top">
                                        <span class="restaurant-cart-item-icon">
                                            @if ($item->item_type === 'package')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 8h16v12H4z"></path>
                                                    <path d="M2 4h20v4H2z"></path>
                                                    <path d="M12 4v16"></path>
                                                    <path d="M12 4c-2.5 0-4-1-4-2 0-1 1-2 2-2 1.5 0 2 2 2 4Z"></path>
                                                    <path d="M12 4c2.5 0 4-1 4-2 0-1-1-2-2-2-1.5 0-2 2-2 4Z"></path>
                                                </svg>
                                            @else
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 11h16"></path>
                                                    <path d="M6 11a6 6 0 0 1 12 0"></path>
                                                    <path d="M3 15h18"></path>
                                                    <path d="M5 19h14"></path>
                                                </svg>
                                            @endif
                                        </span>

                                        <div class="restaurant-cart-item-identity">
                                            <span class="restaurant-cart-item-type">
                                                {{ ucfirst($item->item_type) }}
                                            </span>

                                            <h3 class="restaurant-cart-name">
                                                {{ $item->name }}
                                            </h3>

                                            <span class="restaurant-cart-unit-price">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                                per item
                                            </span>
                                        </div>

                                        <div class="restaurant-cart-subtotal-wrapper">
                                            <span class="restaurant-cart-subtotal-label">
                                                Subtotal
                                            </span>

                                            <strong class="restaurant-cart-subtotal">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </strong>
                                        </div>
                                    </div>

                                    {{-- Update --}}
                                    <form class="restaurant-cart-item-form" action="{{ route('cart.update', $item) }}"
                                        method="POST" data-cart-update-form>
                                        @csrf
                                        @method('PATCH')

                                        <div class="restaurant-cart-field">
                                            <label class="restaurant-cart-field-label"
                                                for="cart-quantity-{{ $item->id }}">
                                                Quantity
                                            </label>

                                            <div class="restaurant-cart-quantity" data-cart-quantity>
                                                <button class="restaurant-cart-quantity-button" type="button"
                                                    data-cart-decrease aria-label="Decrease quantity">
                                                    −
                                                </button>

                                                <input id="cart-quantity-{{ $item->id }}"
                                                    class="restaurant-cart-quantity-input" type="number" name="quantity"
                                                    min="1" max="50" value="{{ $item->quantity }}"
                                                    data-cart-quantity-input required>

                                                <button class="restaurant-cart-quantity-button" type="button"
                                                    data-cart-increase aria-label="Increase quantity">
                                                    +
                                                </button>
                                            </div>
                                        </div>

                                        <div class="restaurant-cart-field">
                                            <label class="restaurant-cart-field-label"
                                                for="cart-note-{{ $item->id }}">
                                                Special note
                                            </label>

                                            <input id="cart-note-{{ $item->id }}" class="restaurant-cart-note-input"
                                                type="text" name="note" value="{{ $item->note }}"
                                                maxlength="500" placeholder="Example: less spicy or no peanuts">
                                        </div>

                                        <button class="restaurant-cart-update-button" type="submit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>

                                            <span data-cart-update-text>
                                                Update
                                            </span>
                                        </button>
                                    </form>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    {{-- Summary --}}
                    <aside class="restaurant-cart-summary-column">
                        <section class="restaurant-cart-summary">
                            <div class="restaurant-cart-summary-content">
                                <span class="restaurant-cart-summary-label">
                                    Order summary
                                </span>

                                <h2 class="restaurant-cart-summary-title">
                                    Your dining order
                                </h2>

                                <p class="restaurant-cart-summary-description">
                                    Review your order before continuing to the
                                    checkout process.
                                </p>

                                <div class="restaurant-cart-summary-details">
                                    <div class="restaurant-cart-summary-row">
                                        <span>Selected items</span>

                                        <strong>
                                            {{ $cart->items->count() }}
                                        </strong>
                                    </div>

                                    <div class="restaurant-cart-summary-row">
                                        <span>Total quantity</span>

                                        <strong>
                                            {{ $cart->items->sum('quantity') }}
                                        </strong>
                                    </div>

                                    <div class="restaurant-cart-summary-divider"></div>

                                    <div>
                                        <span class="restaurant-cart-summary-total-label">
                                            Grand total
                                        </span>

                                        <strong class="restaurant-cart-summary-total">
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                </div>

                                <a class="restaurant-cart-checkout-button" href="{{ route('checkout.create') }}">
                                    Continue to checkout

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="m13 6 6 6-6 6"></path>
                                    </svg>
                                </a>
                            </div>
                        </section>

                        <form class="restaurant-cart-clear-form" action="{{ route('cart.clear') }}" method="POST"
                            data-cart-clear-form>
                            @csrf
                            @method('DELETE')

                            <button class="restaurant-cart-clear-button" type="submit">
                                <span data-cart-clear-text>
                                    Clear all items
                                </span>
                            </button>
                        </form>
                    </aside>
                </div>
            @endif
        </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            'use strict';

            /*
             * Quantity controls.
             */

            var quantityControls = document.querySelectorAll(
                '[data-cart-quantity]'
            );

            quantityControls.forEach(function(control) {
                var input = control.querySelector(
                    '[data-cart-quantity-input]'
                );

                var decreaseButton = control.querySelector(
                    '[data-cart-decrease]'
                );

                var increaseButton = control.querySelector(
                    '[data-cart-increase]'
                );

                if (
                    !input ||
                    !decreaseButton ||
                    !increaseButton
                ) {
                    return;
                }

                var minimum = Number(input.min) || 1;
                var maximum = Number(input.max) || 50;

                function normalizeValue() {
                    var value = Number(input.value);

                    if (!Number.isFinite(value)) {
                        value = minimum;
                    }

                    value = Math.max(minimum, value);
                    value = Math.min(maximum, value);

                    input.value = value;

                    decreaseButton.disabled =
                        value <= minimum;

                    increaseButton.disabled =
                        value >= maximum;
                }

                decreaseButton.addEventListener(
                    'click',
                    function() {
                        input.value =
                            Number(input.value || minimum) - 1;

                        normalizeValue();
                    }
                );

                increaseButton.addEventListener(
                    'click',
                    function() {
                        input.value =
                            Number(input.value || minimum) + 1;

                        normalizeValue();
                    }
                );

                input.addEventListener(
                    'input',
                    normalizeValue
                );

                input.addEventListener(
                    'blur',
                    normalizeValue
                );

                normalizeValue();
            });

            /*
             * Update loading state.
             */

            var updateForms = document.querySelectorAll(
                '[data-cart-update-form]'
            );

            updateForms.forEach(function(form) {
                form.addEventListener(
                    'submit',
                    function() {
                        var button = form.querySelector(
                            '.restaurant-cart-update-button'
                        );

                        var text = form.querySelector(
                            '[data-cart-update-text]'
                        );

                        if (!button) {
                            return;
                        }

                        button.disabled = true;

                        if (text) {
                            text.textContent = 'Updating...';
                        }
                    }
                );
            });

            /*
             * Remove item confirmation.
             */

            var removeForms = document.querySelectorAll(
                '[data-cart-remove-form]'
            );

            removeForms.forEach(function(form) {
                form.addEventListener(
                    'submit',
                    function(event) {
                        var confirmed = window.confirm(
                            'Remove this item from your cart?'
                        );

                        if (!confirmed) {
                            event.preventDefault();
                            return;
                        }

                        var button = form.querySelector(
                            '.restaurant-cart-remove-button'
                        );

                        if (button) {
                            button.disabled = true;
                        }
                    }
                );
            });

            /*
             * Clear cart confirmation.
             */

            var clearForm = document.querySelector(
                '[data-cart-clear-form]'
            );

            if (clearForm) {
                clearForm.addEventListener(
                    'submit',
                    function(event) {
                        var confirmed = window.confirm(
                            'Remove all items from your cart?'
                        );

                        if (!confirmed) {
                            event.preventDefault();
                            return;
                        }

                        var button = clearForm.querySelector(
                            '.restaurant-cart-clear-button'
                        );

                        var text = clearForm.querySelector(
                            '[data-cart-clear-text]'
                        );

                        if (button) {
                            button.disabled = true;
                        }

                        if (text) {
                            text.textContent = 'Clearing cart...';
                        }
                    }
                );
            }
        });
    </script>
@endpush
