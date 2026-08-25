<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Kayu Manis Restaurant - Ayaka Suites')
    </title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">

    <style>
        /*
        |--------------------------------------------------------------------------
        | KAYU MANIS BLUE AURORA AUTHENTICATION
        |--------------------------------------------------------------------------
        | Semua komponen baru menggunakan prefix ayaka-auth-
        | agar tidak bertabrakan dengan Bootstrap atau template Suha.
        */
        .hotel-btn {
            --hotel-btn-background: #eef2f7;
            --hotel-btn-color: #243247;
            --hotel-btn-border: transparent;
            --hotel-btn-shadow: 0 8px 20px rgba(20, 42, 74, 0.12);

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 16px;
            border: 1px solid var(--hotel-btn-border);
            border-radius: 12px;
            background: var(--hotel-btn-background);
            color: var(--hotel-btn-color);
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            box-shadow: var(--hotel-btn-shadow);
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                filter 0.2s ease,
                opacity 0.2s ease;
        }

        .hotel-btn:hover {
            color: var(--hotel-btn-color);
            text-decoration: none;
            filter: brightness(1.04);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(20, 42, 74, 0.18);
        }

        .hotel-btn:active {
            transform: translateY(0);
        }

        .hotel-btn:focus-visible {
            outline: 3px solid rgba(54, 207, 255, 0.28);
            outline-offset: 3px;
        }

        .hotel-btn:disabled,
        .hotel-btn.is-disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
            transform: none;
        }

        .hotel-btn-icon {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
        }

        /* Create / Add: Blue Aurora */

        .hotel-btn-create,
        .hotel-btn-add {
            --hotel-btn-background: linear-gradient(135deg,
                    #075985 0%,
                    #0284c7 38%,
                    #06b6d4 70%,
                    #67e8f9 100%);
            --hotel-btn-color: #ffffff;
            --hotel-btn-shadow: 0 10px 22px rgba(2, 132, 199, 0.25);
        }

        /* Edit / Update: Amber Gold */

        .hotel-btn-edit,
        .hotel-btn-update {
            --hotel-btn-background: linear-gradient(135deg,
                    #92400e 0%,
                    #d97706 48%,
                    #fbbf24 100%);
            --hotel-btn-color: #ffffff;
            --hotel-btn-shadow: 0 10px 22px rgba(217, 119, 6, 0.23);
        }

        /* Delete: Rose Red */

        .hotel-btn-delete {
            --hotel-btn-background: linear-gradient(135deg,
                    #9f1239 0%,
                    #e11d48 55%,
                    #fb7185 100%);
            --hotel-btn-color: #ffffff;
            --hotel-btn-shadow: 0 10px 22px rgba(225, 29, 72, 0.22);
        }

        /* View / Detail: Neutral */

        .hotel-btn-detail,
        .hotel-btn-neutral {
            --hotel-btn-background: #f3f6fa;
            --hotel-btn-color: #334155;
            --hotel-btn-border: #dde5ef;
            --hotel-btn-shadow: 0 7px 18px rgba(15, 23, 42, 0.08);
        }

        /* Outline */

        .hotel-btn-outline {
            --hotel-btn-background: transparent;
            --hotel-btn-color: #087aa9;
            --hotel-btn-border: rgba(8, 122, 169, 0.35);
            --hotel-btn-shadow: none;
        }

        /* Button sizes */

        .hotel-btn-sm {
            min-height: 35px;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 12px;
        }

        .hotel-btn-lg {
            min-height: 50px;
            padding: 13px 20px;
            border-radius: 15px;
            font-size: 14px;
        }

        /* Width utilities */

        .hotel-btn-w-25 {
            width: 25%;
        }

        .hotel-btn-w-50 {
            width: 50%;
        }

        .hotel-btn-w-75 {
            width: 75%;
        }

        .hotel-btn-w-100 {
            width: 100%;
        }

        /* Shape utilities */

        .hotel-btn-rounded {
            border-radius: 999px;
        }

        .hotel-btn-square {
            width: 42px;
            min-width: 42px;
            padding-right: 0;
            padding-left: 0;
        }

        /* Dark mode */

        [data-theme="dark"] .hotel-btn-detail,
        [data-theme="dark"] .hotel-btn-neutral {
            --hotel-btn-background: #242b35;
            --hotel-btn-color: #edf4ff;
            --hotel-btn-border: #394452;
        }

        [data-theme="dark"] .hotel-btn-outline {
            --hotel-btn-background: rgba(34, 211, 238, 0.08);
            --hotel-btn-color: #67e8f9;
            --hotel-btn-border: rgba(103, 232, 249, 0.35);
        }

        /* Mobile button behavior */

        @media (max-width: 480px) {
            .hotel-btn-mobile-full {
                width: 100%;
            }
        }


        :root {
            --ayaka-auth-primary: #075985;
            --ayaka-auth-secondary: #0891b2;
            --ayaka-auth-aurora: #22d3ee;
            --ayaka-auth-light: #a5f3fc;
            --ayaka-auth-text: #172033;
            --ayaka-auth-muted: #728095;
            --ayaka-auth-surface: #ffffff;
            --ayaka-auth-soft: #f3f9fc;
            --ayaka-auth-border: #dbeaf0;
            --ayaka-auth-danger: #be123c;
            --ayaka-auth-success: #15803d;
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
        }

        body {
            min-width: 280px;
            min-height: 100vh;
            margin: 0;
            background: #eef4ff;
            color: var(--ayaka-auth-text);
            font-family: "DM Sans", sans-serif;
        }

        button,
        input {
            font-family: inherit;
        }

        /* =====================================================
           PAGE BACKGROUND
        ====================================================== */

        .ayaka-auth-page {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 100vh;
            padding: 30px;
            overflow: hidden;
            background:
                radial-gradient(circle at 8% 5%,
                    rgba(103, 232, 249, 0.32),
                    transparent 29%),
                radial-gradient(circle at 93% 95%,
                    rgba(14, 165, 183, 0.22),
                    transparent 32%),
                linear-gradient(135deg,
                    #f4f7ff 0%,
                    #e9f6fa 50%,
                    #eef2ff 100%);
        }

        .ayaka-auth-background-grid {
            position: absolute;
            inset: 0;
            z-index: 0;
            opacity: 0.32;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(7, 89, 133, 0.045) 1px,
                    transparent 1px),
                linear-gradient(90deg,
                    rgba(7, 89, 133, 0.045) 1px,
                    transparent 1px);
            background-size: 34px 34px;
            mask-image: linear-gradient(to bottom,
                    rgba(0, 0, 0, 0.75),
                    transparent 92%);
        }

        .ayaka-auth-orb {
            position: absolute;
            z-index: 0;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(2px);
        }

        .ayaka-auth-orb-one {
            top: -120px;
            left: -90px;
            width: 310px;
            height: 310px;
            background: rgba(34, 211, 238, 0.13);
        }

        .ayaka-auth-orb-two {
            right: -130px;
            bottom: -160px;
            width: 390px;
            height: 390px;
            background: rgba(7, 89, 133, 0.12);
        }

        /* =====================================================
           AUTH SHELL
        ====================================================== */

        .ayaka-auth-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1120px;
            margin: auto;
        }

        .ayaka-auth-shell {
            display: grid;
            grid-template-columns: minmax(0, 1.03fr) minmax(420px, 0.97fr);
            min-height: 690px;
            overflow: hidden;
            border: 1px solid rgba(219, 234, 240, 0.92);
            border-radius: 31px;
            background: var(--ayaka-auth-surface);
            box-shadow:
                0 35px 80px rgba(22, 75, 102, 0.16),
                0 5px 18px rgba(22, 75, 102, 0.06);
        }

        /* =====================================================
           SHOWCASE
        ====================================================== */

        .ayaka-auth-showcase {
            position: relative;
            display: flex;
            min-width: 0;
            min-height: 690px;
            overflow: hidden;
            background: #071827;
            isolation: isolate;
        }

        .ayaka-auth-showcase-image {
            position: absolute;
            inset: 0;
            z-index: -4;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ayaka-auth-showcase-overlay {
            position: absolute;
            inset: 0;
            z-index: -3;
            background:
                linear-gradient(180deg,
                    rgba(4, 19, 31, 0.48) 0%,
                    rgba(4, 23, 37, 0.78) 47%,
                    rgba(4, 23, 37, 0.97) 100%);
        }

        .ayaka-auth-showcase-glow {
            position: absolute;
            top: -100px;
            right: -115px;
            z-index: -2;
            width: 330px;
            height: 330px;
            border-radius: 50%;
            background: rgba(103, 232, 249, 0.22);
            filter: blur(3px);
        }

        .ayaka-auth-showcase-content {
            position: relative;
            z-index: 2;
            display: flex;
            width: 100%;
            min-width: 0;
            flex-direction: column;
            justify-content: space-between;
            padding: 38px;
        }

        /* =====================================================
           BRAND
        ====================================================== */

        .ayaka-auth-brand {
            display: inline-flex;
            align-items: center;
            align-self: flex-start;
            min-width: 0;
            gap: 11px;
            color: #ffffff;
            text-decoration: none;
        }

        .ayaka-auth-brand:hover,
        .ayaka-auth-brand:focus {
            color: #ffffff;
            text-decoration: none;
        }

        .ayaka-auth-brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            flex: 0 0 45px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            background: linear-gradient(135deg,
                    rgba(7, 89, 133, 0.92),
                    rgba(34, 211, 238, 0.82));
            color: #ffffff;
            font-size: 21px;
            box-shadow: 0 10px 22px rgba(6, 182, 212, 0.22);
            backdrop-filter: blur(10px);
        }

        .ayaka-auth-brand-text {
            display: grid;
            min-width: 0;
            gap: 1px;
        }

        .ayaka-auth-brand-name {
            overflow: hidden;
            color: #ffffff;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ayaka-auth-brand-subtitle {
            overflow: hidden;
            color: rgba(255, 255, 255, 0.63);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }

        /* =====================================================
           SHOWCASE CONTENT
        ====================================================== */

        .ayaka-auth-showcase-main {
            max-width: 490px;
            margin: 55px 0;
        }

        .ayaka-auth-showcase-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            backdrop-filter: blur(9px);
        }

        .ayaka-auth-showcase-label-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #67e8f9;
            box-shadow: 0 0 0 5px rgba(103, 232, 249, 0.14);
        }

        .ayaka-auth-showcase-title {
            margin: 0 0 15px;
            color: #ffffff;
            font-size: clamp(36px, 4.5vw, 57px);
            font-weight: 850;
            letter-spacing: -0.045em;
            line-height: 1.03;
        }

        .ayaka-auth-showcase-title span {
            color: #8be9f6;
        }

        .ayaka-auth-showcase-description {
            max-width: 460px;
            margin: 0;
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            line-height: 1.75;
        }

        .ayaka-auth-features {
            display: grid;
            gap: 10px;
            margin-top: 24px;
        }

        .ayaka-auth-feature {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .ayaka-auth-feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 31px;
            height: 31px;
            flex: 0 0 31px;
            border-radius: 10px;
            background: rgba(103, 232, 249, 0.13);
            color: #67e8f9;
            font-size: 14px;
        }

        .ayaka-auth-feature-content {
            display: grid;
            gap: 2px;
        }

        .ayaka-auth-feature-title {
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
        }

        .ayaka-auth-feature-text {
            color: rgba(255, 255, 255, 0.57);
            font-size: 9px;
            line-height: 1.45;
        }

        .ayaka-auth-showcase-footer {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 17px;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.63);
            font-size: 9px;
            line-height: 1.5;
        }

        .ayaka-auth-showcase-footer i {
            color: #67e8f9;
            font-size: 17px;
        }

        /* =====================================================
           FORM SIDE
        ====================================================== */

        .ayaka-auth-form-panel {
            display: flex;
            min-width: 0;
            flex-direction: column;
            background:
                radial-gradient(circle at 100% 0,
                    rgba(103, 232, 249, 0.1),
                    transparent 28%),
                var(--ayaka-auth-surface);
        }

        .ayaka-auth-mobile-header {
            display: none;
        }

        .ayaka-auth-form-scroll {
            display: flex;
            flex: 1;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .ayaka-auth-form-container {
            width: 100%;
            max-width: 430px;
            margin: auto;
        }

        .ayaka-auth-copyright {
            padding: 0 30px 22px;
            color: #97a4b5;
            font-size: 9px;
            text-align: center;
        }

        /* =====================================================
           HEADING
        ====================================================== */

        .ayaka-auth-heading {
            margin-bottom: 24px;
        }

        .ayaka-auth-heading.is-centered {
            text-align: center;
        }

        .ayaka-auth-eyebrow {
            display: block;
            margin-bottom: 7px;
            color: var(--ayaka-auth-primary);
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .ayaka-auth-heading-title {
            margin: 0 0 9px;
            color: var(--ayaka-auth-text);
            font-size: clamp(25px, 4vw, 35px);
            font-weight: 850;
            letter-spacing: -0.035em;
            line-height: 1.14;
        }

        .ayaka-auth-heading-description {
            max-width: 400px;
            margin: 0;
            color: var(--ayaka-auth-muted);
            font-size: 11px;
            line-height: 1.65;
        }

        .ayaka-auth-heading.is-centered .ayaka-auth-heading-description {
            margin-right: auto;
            margin-left: auto;
        }

        /* =====================================================
           ALERTS
        ====================================================== */

        .ayaka-auth-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 17px;
            padding: 12px 13px;
            border: 1px solid;
            border-radius: 14px;
            font-size: 10px;
            line-height: 1.5;
        }

        .ayaka-auth-alert-icon {
            margin-top: 1px;
            font-size: 15px;
        }

        .ayaka-auth-alert-content {
            min-width: 0;
        }

        .ayaka-auth-alert-title {
            display: block;
            margin-bottom: 3px;
            font-weight: 800;
        }

        .ayaka-auth-alert-list {
            display: grid;
            gap: 2px;
            margin: 0;
            padding-left: 16px;
        }

        .ayaka-auth-alert.is-danger {
            border-color: #fecdd3;
            background: #fff1f2;
            color: #9f1239;
        }

        .ayaka-auth-alert.is-success {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }

        .ayaka-auth-alert.is-information {
            border-color: #bae6fd;
            background: #f0f9ff;
            color: #075985;
        }

        /* =====================================================
           FORM
        ====================================================== */

        .ayaka-auth-form {
            display: grid;
            gap: 17px;
        }

        .ayaka-auth-field {
            display: grid;
            min-width: 0;
            gap: 7px;
        }

        .ayaka-auth-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .ayaka-auth-label {
            color: var(--ayaka-auth-text);
            font-size: 11px;
            font-weight: 750;
        }

        .ayaka-auth-forgot-link,
        .ayaka-auth-inline-link,
        .ayaka-auth-switch-link {
            color: var(--ayaka-auth-primary);
            font-weight: 800;
            text-decoration: none;
        }

        .ayaka-auth-forgot-link {
            flex: 0 0 auto;
            font-size: 9px;
        }

        .ayaka-auth-forgot-link:hover,
        .ayaka-auth-inline-link:hover,
        .ayaka-auth-switch-link:hover {
            color: var(--ayaka-auth-secondary);
            text-decoration: none;
        }

        .ayaka-auth-input-wrapper {
            position: relative;
            min-width: 0;
        }

        .ayaka-auth-input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #7c91a5;
            font-size: 16px;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .ayaka-auth-input {
            display: block;
            width: 100%;
            min-height: 48px;
            margin: 0;
            padding: 11px 44px;
            border: 1px solid var(--ayaka-auth-border);
            border-radius: 14px;
            outline: none;
            background: #f8fbfd;
            color: var(--ayaka-auth-text);
            font-size: 12px;
            line-height: 1.4;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .ayaka-auth-input::placeholder {
            color: #9aa8b8;
        }

        .ayaka-auth-input:hover {
            border-color: #b9d7e1;
            background: #ffffff;
        }

        .ayaka-auth-input:focus {
            border-color: var(--ayaka-auth-secondary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.11);
        }

        .ayaka-auth-input.is-invalid {
            border-color: #fb7185;
            background: #fffafa;
        }

        .ayaka-auth-input.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.09);
        }

        .ayaka-auth-password-toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            z-index: 3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            padding: 0;
            border: 0;
            border-radius: 10px;
            outline: none;
            background: transparent;
            color: #75869a;
            cursor: pointer;
            transform: translateY(-50%);
            transition:
                color 0.2s ease,
                background 0.2s ease;
        }

        .ayaka-auth-password-toggle:hover,
        .ayaka-auth-password-toggle:focus {
            background: #eaf5f8;
            color: var(--ayaka-auth-primary);
        }

        .ayaka-auth-field-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            color: var(--ayaka-auth-danger);
            font-size: 9px;
            line-height: 1.5;
        }

        .ayaka-auth-field-error i {
            margin-top: 1px;
        }

        .ayaka-auth-client-error {
            display: none;
            color: var(--ayaka-auth-danger);
            font-size: 9px;
            line-height: 1.5;
        }

        .ayaka-auth-input.is-client-invalid~.ayaka-auth-client-error {
            display: block;
        }

        /* =====================================================
           CHECKBOX
        ====================================================== */

        .ayaka-auth-check {
            display: flex;
            align-items: flex-start;
            gap: 9px;
        }

        .ayaka-auth-checkbox {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            margin: 1px 0 0;
            border: 1px solid #b8cbd5;
            border-radius: 5px;
            accent-color: var(--ayaka-auth-primary);
            cursor: pointer;
        }

        .ayaka-auth-check-label {
            margin: 0;
            color: var(--ayaka-auth-muted);
            font-size: 9px;
            line-height: 1.55;
            cursor: pointer;
        }

        /* =====================================================
           BUTTONS
        ====================================================== */

        .ayaka-auth-submit {
            min-height: 50px;
        }

        .ayaka-auth-button-icon {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
        }

        .ayaka-auth-google-button {
            --hotel-btn-background: #ffffff;
            --hotel-btn-color: #334155;
            --hotel-btn-border: #dbe5ec;
            --hotel-btn-shadow: 0 7px 18px rgba(15, 23, 42, 0.07);

            min-height: 48px;
        }

        .ayaka-auth-google-button:hover {
            --hotel-btn-background: #f8fbfd;
        }

        /* =====================================================
           DIVIDER AND SWITCH
        ====================================================== */

        .ayaka-auth-divider {
            display: flex;
            align-items: center;
            gap: 11px;
            color: #98a6b6;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .ayaka-auth-divider::before,
        .ayaka-auth-divider::after {
            height: 1px;
            flex: 1;
            background: var(--ayaka-auth-border);
            content: "";
        }

        .ayaka-auth-switch {
            margin: 0;
            color: var(--ayaka-auth-muted);
            font-size: 10px;
            line-height: 1.5;
            text-align: center;
        }

        /* =====================================================
           PASSWORD STRENGTH
        ====================================================== */

        .ayaka-auth-strength {
            display: grid;
            gap: 6px;
        }

        .ayaka-auth-strength-bars {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
        }

        .ayaka-auth-strength-bar {
            height: 4px;
            border-radius: 999px;
            background: #e2e8f0;
            transition: background 0.2s ease;
        }

        .ayaka-auth-strength[data-strength="1"] .ayaka-auth-strength-bar:nth-child(1) {
            background: #fb7185;
        }

        .ayaka-auth-strength[data-strength="2"] .ayaka-auth-strength-bar:nth-child(-n+2) {
            background: #f59e0b;
        }

        .ayaka-auth-strength[data-strength="3"] .ayaka-auth-strength-bar:nth-child(-n+3) {
            background: #06b6d4;
        }

        .ayaka-auth-strength[data-strength="4"] .ayaka-auth-strength-bar {
            background: #22c55e;
        }

        .ayaka-auth-strength-text,
        .ayaka-auth-match-message {
            min-height: 14px;
            color: var(--ayaka-auth-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        .ayaka-auth-match-message.is-valid {
            color: var(--ayaka-auth-success);
        }

        .ayaka-auth-match-message.is-invalid {
            color: var(--ayaka-auth-danger);
        }

        /* =====================================================
           RECOVERY PAGE
        ====================================================== */

        .ayaka-auth-back-wrapper {
            margin-bottom: 20px;
        }

        .ayaka-auth-back-button {
            min-height: 37px;
        }

        .ayaka-auth-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            margin: 0 auto 17px;
            border-radius: 21px;
            background:
                linear-gradient(135deg,
                    rgba(7, 89, 133, 0.13),
                    rgba(34, 211, 238, 0.21));
            color: var(--ayaka-auth-primary);
            font-size: 27px;
            box-shadow: 0 12px 25px rgba(7, 89, 133, 0.1);
        }

        .ayaka-auth-state-icon-wrapper {
            display: flex;
            justify-content: center;
        }

        .ayaka-auth-security-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 11px 12px;
            border: 1px solid #d8edf2;
            border-radius: 13px;
            background: #f2fafc;
            color: #557180;
            font-size: 9px;
            line-height: 1.55;
        }

        .ayaka-auth-security-note i {
            margin-top: 1px;
            color: var(--ayaka-auth-primary);
            font-size: 15px;
        }

        .ayaka-auth-security-note p {
            margin: 0;
        }

        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 991px) {
            .ayaka-auth-page {
                padding: 22px;
            }

            .ayaka-auth-shell {
                display: block;
                width: 100%;
                max-width: 620px;
                min-height: 0;
                margin: auto;
            }

            .ayaka-auth-showcase {
                display: none;
            }

            .ayaka-auth-mobile-header {
                display: block;
                padding: 21px 24px;
                background:
                    radial-gradient(circle at 90% 0,
                        rgba(103, 232, 249, 0.28),
                        transparent 32%),
                    linear-gradient(135deg,
                        #071827,
                        #075985,
                        #0ea5b7);
            }

            .ayaka-auth-mobile-header .ayaka-auth-brand {
                margin: 0;
            }

            .ayaka-auth-form-scroll {
                min-height: auto;
                padding: 35px 38px;
            }
        }

        @media (max-width: 575px) {
            .ayaka-auth-page {
                display: block;
                min-height: 100vh;
                padding: 0;
                background: #ffffff;
            }

            .ayaka-auth-background-grid,
            .ayaka-auth-orb {
                display: none;
            }

            .ayaka-auth-container {
                min-height: 100vh;
            }

            .ayaka-auth-shell {
                min-height: 100vh;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .ayaka-auth-mobile-header {
                padding: 17px 18px;
            }

            .ayaka-auth-brand-icon {
                width: 41px;
                height: 41px;
                flex-basis: 41px;
                border-radius: 13px;
            }

            .ayaka-auth-form-scroll {
                display: block;
                padding: 28px 20px 35px;
            }

            .ayaka-auth-heading {
                margin-bottom: 21px;
            }

            .ayaka-auth-heading-title {
                font-size: clamp(24px, 7vw, 30px);
            }

            .ayaka-auth-form {
                gap: 15px;
            }

            .ayaka-auth-copyright {
                padding-bottom: 20px;
            }
        }

        @media (max-width: 350px) {
            .ayaka-auth-form-scroll {
                padding-right: 15px;
                padding-left: 15px;
            }

            .ayaka-auth-mobile-header {
                padding-right: 15px;
                padding-left: 15px;
            }

            .ayaka-auth-brand-name {
                font-size: 12px;
            }

            .ayaka-auth-brand-subtitle {
                font-size: 8px;
            }

            .ayaka-auth-heading-title {
                font-size: 23px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .ayaka-auth-input,
            .ayaka-auth-password-toggle,
            .hotel-btn {
                transition: none;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <main class="ayaka-auth-page">
        <div class="ayaka-auth-background-grid"></div>
        <div class="ayaka-auth-orb ayaka-auth-orb-one"></div>
        <div class="ayaka-auth-orb ayaka-auth-orb-two"></div>

        <div class="ayaka-auth-container">
            <div class="ayaka-auth-shell">
                {{-- =====================================================
                     SHOWCASE
                ====================================================== --}}

                <section class="ayaka-auth-showcase">
                    <img class="ayaka-auth-showcase-image" src="{{ asset('users/img/restaurant/fine-dining.jpg') }}"
                        alt="Kayu Manis Restaurant at Ayaka Suites">

                    <div class="ayaka-auth-showcase-overlay"></div>
                    <div class="ayaka-auth-showcase-glow"></div>

                    <div class="ayaka-auth-showcase-content">
                        <a class="ayaka-auth-brand" href="{{ url('/') }}">
                            <span class="ayaka-auth-brand-icon">
                                <i class="bi bi-cup-hot-fill"></i>
                            </span>

                            <span class="ayaka-auth-brand-text">
                                <strong class="ayaka-auth-brand-name">
                                    Kayu Manis Restaurant
                                </strong>

                                <small class="ayaka-auth-brand-subtitle">
                                    Ayaka Suites Jakarta
                                </small>
                            </span>
                        </a>

                        <div class="ayaka-auth-showcase-main">
                            <span class="ayaka-auth-showcase-label">
                                <span class="ayaka-auth-showcase-label-dot"></span>
                                Signature dining at Ayaka Suites
                            </span>

                            <h1 class="ayaka-auth-showcase-title">
                                Asian warmth,
                                <span>Western elegance.</span>
                            </h1>

                            <p class="ayaka-auth-showcase-description">
                                Access your account to order thoughtfully prepared
                                cuisine, manage restaurant reservations, and review
                                your dining history.
                            </p>

                            <div class="ayaka-auth-features">
                                <div class="ayaka-auth-feature">
                                    <span class="ayaka-auth-feature-icon">
                                        <i class="bi bi-calendar2-check"></i>
                                    </span>

                                    <span class="ayaka-auth-feature-content">
                                        <strong class="ayaka-auth-feature-title">
                                            Easy reservations
                                        </strong>

                                        <small class="ayaka-auth-feature-text">
                                            Reserve your preferred table in a few steps.
                                        </small>
                                    </span>
                                </div>

                                <div class="ayaka-auth-feature">
                                    <span class="ayaka-auth-feature-icon">
                                        <i class="bi bi-bag-check"></i>
                                    </span>

                                    <span class="ayaka-auth-feature-content">
                                        <strong class="ayaka-auth-feature-title">
                                            Convenient ordering
                                        </strong>

                                        <small class="ayaka-auth-feature-text">
                                            Browse the menu and follow every order.
                                        </small>
                                    </span>
                                </div>

                                <div class="ayaka-auth-feature">
                                    <span class="ayaka-auth-feature-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </span>

                                    <span class="ayaka-auth-feature-content">
                                        <strong class="ayaka-auth-feature-title">
                                            Secure account
                                        </strong>

                                        <small class="ayaka-auth-feature-text">
                                            Your account information remains protected.
                                        </small>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="ayaka-auth-showcase-footer">
                            <i class="bi bi-stars"></i>

                            <span>
                                A memorable dining experience in the heart of Jakarta.
                            </span>
                        </div>
                    </div>
                </section>

                {{-- =====================================================
                     AUTH FORM PANEL
                ====================================================== --}}

                <section class="ayaka-auth-form-panel">
                    <header class="ayaka-auth-mobile-header">
                        <a class="ayaka-auth-brand" href="{{ url('/') }}">
                            <span class="ayaka-auth-brand-icon">
                                <i class="bi bi-cup-hot-fill"></i>
                            </span>

                            <span class="ayaka-auth-brand-text">
                                <strong class="ayaka-auth-brand-name">
                                    Kayu Manis Restaurant
                                </strong>

                                <small class="ayaka-auth-brand-subtitle">
                                    Ayaka Suites Jakarta
                                </small>
                            </span>
                        </a>
                    </header>

                    <div class="ayaka-auth-form-scroll">
                        <div class="ayaka-auth-form-container">
                            @if ($errors->any())
                                <div class="ayaka-auth-alert is-danger" role="alert">
                                    <i class="bi bi-exclamation-circle-fill ayaka-auth-alert-icon"></i>

                                    <div class="ayaka-auth-alert-content">
                                        <strong class="ayaka-auth-alert-title">
                                            Please check the following information
                                        </strong>

                                        <ul class="ayaka-auth-alert-list">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="ayaka-auth-alert is-success" role="alert">
                                    <i class="bi bi-check-circle-fill ayaka-auth-alert-icon"></i>

                                    <div class="ayaka-auth-alert-content">
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="ayaka-auth-alert is-information" role="status">
                                    <i class="bi bi-info-circle-fill ayaka-auth-alert-icon"></i>

                                    <div class="ayaka-auth-alert-content">
                                        {{ session('status') }}
                                    </div>
                                </div>
                            @endif

                            @yield('content')
                        </div>
                    </div>

                    <footer class="ayaka-auth-copyright">
                        &copy; {{ date('Y') }} Ayaka Suites.
                        All rights reserved.
                    </footer>
                </section>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /*
            |--------------------------------------------------------------------------
            | SHOW OR HIDE PASSWORD
            |--------------------------------------------------------------------------
            */

            var passwordButtons = document.querySelectorAll(
                '[data-ayaka-password-target]'
            );

            passwordButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var targetId = button.getAttribute(
                        'data-ayaka-password-target'
                    );

                    var input = document.getElementById(targetId);
                    var icon = button.querySelector('i');

                    if (!input) {
                        return;
                    }

                    var showPassword = input.type === 'password';

                    input.type = showPassword ? 'text' : 'password';

                    button.setAttribute(
                        'aria-label',
                        showPassword ? 'Hide password' : 'Show password'
                    );

                    if (icon) {
                        icon.className = showPassword ?
                            'bi bi-eye-slash' :
                            'bi bi-eye';
                    }
                });
            });

            /*
            |--------------------------------------------------------------------------
            | CLIENT VALIDATION
            |--------------------------------------------------------------------------
            */

            var authForms = document.querySelectorAll(
                '.ayaka-auth-form'
            );

            authForms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    var password = form.querySelector(
                        '#password'
                    );

                    var passwordConfirmation = form.querySelector(
                        '#password_confirmation'
                    );

                    if (
                        password &&
                        passwordConfirmation &&
                        password.value !== passwordConfirmation.value
                    ) {
                        event.preventDefault();

                        passwordConfirmation.classList.add(
                            'is-invalid'
                        );

                        var matchMessage = document.getElementById(
                            'ayakaPasswordMatch'
                        );

                        if (matchMessage) {
                            matchMessage.textContent =
                                'The password confirmation does not match.';

                            matchMessage.className =
                                'ayaka-auth-match-message is-invalid';
                        }

                        passwordConfirmation.focus();

                        return;
                    }

                    if (!form.checkValidity()) {
                        event.preventDefault();

                        var firstInvalid = form.querySelector(
                            ':invalid'
                        );

                        if (firstInvalid) {
                            firstInvalid.focus();
                        }

                        return;
                    }

                    var submitButton = form.querySelector(
                        '[data-ayaka-submit]'
                    );

                    var submitText = form.querySelector(
                        '[data-ayaka-submit-text]'
                    );

                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.classList.add('is-disabled');
                    }

                    if (submitText) {
                        submitText.textContent =
                            submitButton.getAttribute('data-loading-text') ||
                            'Please wait...';
                    }
                });

                var formInputs = form.querySelectorAll(
                    'input[required]'
                );

                formInputs.forEach(function(input) {
                    input.addEventListener('blur', function() {
                        if (!input.validity.valid) {
                            input.classList.add('is-invalid');
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    input.addEventListener('input', function() {
                        if (input.validity.valid) {
                            input.classList.remove('is-invalid');
                        }
                    });
                });
            });

            /*
            |--------------------------------------------------------------------------
            | PASSWORD STRENGTH
            |--------------------------------------------------------------------------
            */

            var registerPassword = document.getElementById(
                'password'
            );

            var strengthContainer = document.getElementById(
                'ayakaPasswordStrength'
            );

            var strengthText = document.getElementById(
                'ayakaPasswordStrengthText'
            );

            function calculatePasswordStrength(value) {
                var strength = 0;

                if (value.length >= 8) {
                    strength++;
                }

                if (/[a-z]/.test(value) && /[A-Z]/.test(value)) {
                    strength++;
                }

                if (/\d/.test(value)) {
                    strength++;
                }

                if (/[^A-Za-z0-9]/.test(value)) {
                    strength++;
                }

                return strength;
            }

            if (
                registerPassword &&
                strengthContainer &&
                strengthText
            ) {
                registerPassword.addEventListener('input', function() {
                    var strength = calculatePasswordStrength(
                        registerPassword.value
                    );

                    var strengthLabels = {
                        0: 'Use at least 8 characters.',
                        1: 'Your password is still weak.',
                        2: 'Your password strength is fair.',
                        3: 'Your password is strong.',
                        4: 'Your password is very strong.'
                    };

                    strengthContainer.setAttribute(
                        'data-strength',
                        strength
                    );

                    strengthText.textContent =
                        strengthLabels[strength];
                });
            }

            /*
            |--------------------------------------------------------------------------
            | PASSWORD MATCH
            |--------------------------------------------------------------------------
            */

            var confirmationInput = document.getElementById(
                'password_confirmation'
            );

            var passwordMatch = document.getElementById(
                'ayakaPasswordMatch'
            );

            function updatePasswordMatch() {
                if (
                    !registerPassword ||
                    !confirmationInput ||
                    !passwordMatch
                ) {
                    return;
                }

                if (!confirmationInput.value) {
                    passwordMatch.textContent = '';
                    passwordMatch.className =
                        'ayaka-auth-match-message';

                    return;
                }

                if (
                    registerPassword.value ===
                    confirmationInput.value
                ) {
                    passwordMatch.textContent =
                        'The passwords match.';

                    passwordMatch.className =
                        'ayaka-auth-match-message is-valid';

                    confirmationInput.classList.remove(
                        'is-invalid'
                    );
                } else {
                    passwordMatch.textContent =
                        'The passwords do not match.';

                    passwordMatch.className =
                        'ayaka-auth-match-message is-invalid';
                }
            }

            if (registerPassword && confirmationInput) {
                registerPassword.addEventListener(
                    'input',
                    updatePasswordMatch
                );

                confirmationInput.addEventListener(
                    'input',
                    updatePasswordMatch
                );
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
