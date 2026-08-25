@extends('users.layouts.main')

@push('style')
    <style>
        /* =====================================================
               RESTAURANT QR SCANNER
            ====================================================== */

        .restaurant-qr-page {
            --qr-navy: #071a32;
            --qr-blue: #075985;
            --qr-cyan: #0ea5b7;
            --qr-light-cyan: #67e8f9;
            --qr-text: #172033;
            --qr-muted: #718096;
            --qr-surface: #ffffff;
            --qr-soft: #f2f9fb;
            --qr-border: #dcebf0;
            --qr-success: #16805b;

            width: 100%;
            padding: 20px 0 110px;
            color: var(--qr-text);
        }

        /* =====================================================
               HEADER
            ====================================================== */

        .restaurant-qr-header {
            margin-bottom: 18px;
        }

        .restaurant-qr-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: var(--qr-blue);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .restaurant-qr-eyebrow::before {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--qr-cyan);
            content: "";
            box-shadow: 0 0 0 4px rgba(14, 165, 183, 0.12);
        }

        .restaurant-qr-title {
            margin: 0 0 7px;
            color: var(--qr-text);
            font-size: clamp(26px, 5vw, 37px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.8px;
        }

        .restaurant-qr-description {
            max-width: 630px;
            margin: 0;
            color: var(--qr-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        /* =====================================================
               LAYOUT
            ====================================================== */

        .restaurant-qr-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
            gap: 18px;
            align-items: start;
        }

        /* =====================================================
               CAMERA CARD
            ====================================================== */

        .restaurant-qr-camera-card {
            min-width: 0;
            padding: 12px;
            border: 1px solid var(--qr-border);
            border-radius: 25px;
            background: var(--qr-surface);
            box-shadow: 0 18px 42px rgba(20, 76, 104, 0.13);
        }

        .restaurant-qr-camera-shell {
            position: relative;
            width: 100%;
            height: 510px;
            overflow: hidden;
            border-radius: 19px;
            background:
                radial-gradient(circle at 50% 42%,
                    rgba(14, 165, 183, 0.19),
                    transparent 38%),
                linear-gradient(145deg,
                    #07111f 0%,
                    #071a32 52%,
                    #0a3446 100%);
        }

        /* =====================================================
               HTML5 QR READER
            ====================================================== */

        .restaurant-qr-reader {
            position: absolute;
            inset: 0;
            z-index: 1;
            width: 100%;
            height: 100%;
            overflow: hidden;
            opacity: 0;
            background: #07111f;
            transition: opacity 0.25s ease;
        }

        .restaurant-qr-camera-shell.is-camera-active .restaurant-qr-reader {
            opacity: 1;
        }

        /*
             * Area video yang dibuat library.
             */

        #restaurantQrReader__scan_region {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            overflow: hidden !important;
            border: 0 !important;
            background: #07111f !important;
        }

        /*
             * Hanya satu video kamera yang terlihat.
             */

        #restaurantQrReader__scan_region video {
            position: absolute !important;
            inset: 0 !important;
            z-index: 1 !important;
            display: block !important;
            width: 100% !important;
            height: 100% !important;
            max-width: none !important;
            border: 0 !important;
            object-fit: cover !important;
        }

        /*
             * Canvas digunakan library untuk membaca QR.
             * Canvas tidak diubah ukuran dan tidak ditampilkan.
             */

        .restaurant-qr-reader canvas {
            position: absolute !important;
            inset: 0 !important;
            z-index: -1 !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        /*
             * Hilangkan seluruh UI tambahan bawaan library.
             */

        #restaurantQrReader__dashboard,
        #restaurantQrReader__dashboard_section,
        #restaurantQrReader__header_message,
        .restaurant-qr-reader img,
        .restaurant-qr-reader select,
        .restaurant-qr-reader button {
            display: none !important;
        }

        /* =====================================================
               CAMERA OVERLAY
            ====================================================== */

        .restaurant-qr-video-overlay {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: none;
            pointer-events: none;
            background:
                linear-gradient(to bottom,
                    rgba(3, 17, 30, 0.26),
                    transparent 30%,
                    transparent 70%,
                    rgba(3, 17, 30, 0.40));
        }

        .restaurant-qr-camera-shell.is-camera-active .restaurant-qr-video-overlay {
            display: block;
        }

        /* =====================================================
               CUSTOM SCAN FRAME
            ====================================================== */

        .restaurant-qr-scan-frame {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 4;
            display: none;
            width: min(64%, 290px);
            aspect-ratio: 1 / 1;
            border-radius: 21px;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .restaurant-qr-camera-shell.is-camera-active .restaurant-qr-scan-frame {
            display: block;
        }

        .restaurant-qr-frame-corner {
            position: absolute;
            width: 45px;
            height: 45px;
            border-color: var(--qr-light-cyan);
            border-style: solid;
            filter: drop-shadow(0 0 7px rgba(103, 232, 249, 0.52));
        }

        .restaurant-qr-frame-corner.is-top-left {
            top: 0;
            left: 0;
            border-width: 4px 0 0 4px;
            border-radius: 18px 0 0;
        }

        .restaurant-qr-frame-corner.is-top-right {
            top: 0;
            right: 0;
            border-width: 4px 4px 0 0;
            border-radius: 0 18px 0 0;
        }

        .restaurant-qr-frame-corner.is-bottom-left {
            bottom: 0;
            left: 0;
            border-width: 0 0 4px 4px;
            border-radius: 0 0 0 18px;
        }

        .restaurant-qr-frame-corner.is-bottom-right {
            right: 0;
            bottom: 0;
            border-width: 0 4px 4px 0;
            border-radius: 0 0 18px;
        }

        .restaurant-qr-scan-line {
            position: absolute;
            top: 15px;
            right: 15px;
            left: 15px;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg,
                    transparent,
                    #67e8f9,
                    #ffffff,
                    #67e8f9,
                    transparent);
            box-shadow: 0 0 12px #67e8f9;
            animation: restaurantQrScanLine 2.2s ease-in-out infinite;
        }

        @keyframes restaurantQrScanLine {

            0%,
            100% {
                top: 15px;
                opacity: 0.55;
            }

            50% {
                top: calc(100% - 17px);
                opacity: 1;
            }
        }

        /* =====================================================
               CAMERA STATUS
            ====================================================== */

        .restaurant-qr-live-status {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 5;
            display: none;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 999px;
            background: rgba(4, 25, 39, 0.72);
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            backdrop-filter: blur(9px);
        }

        .restaurant-qr-camera-shell.is-camera-active .restaurant-qr-live-status {
            display: inline-flex;
        }

        .restaurant-qr-live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 9px #34d399;
        }

        .restaurant-qr-camera-instruction {
            position: absolute;
            right: 15px;
            bottom: 15px;
            left: 15px;
            z-index: 5;
            display: none;
            padding: 10px 13px;
            border-radius: 12px;
            background: rgba(4, 25, 39, 0.74);
            color: rgba(255, 255, 255, 0.86);
            font-size: 10px;
            font-weight: 700;
            line-height: 1.5;
            text-align: center;
            backdrop-filter: blur(9px);
        }

        .restaurant-qr-camera-shell.is-camera-active .restaurant-qr-camera-instruction {
            display: block;
        }

        /* =====================================================
               LOADING AND ERROR
            ====================================================== */

        .restaurant-qr-state {
            position: absolute;
            inset: 0;
            z-index: 6;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
            text-align: center;
        }

        .restaurant-qr-state[hidden] {
            display: none;
        }

        .restaurant-qr-state-content {
            width: 100%;
            max-width: 390px;
        }

        .restaurant-qr-loading-ring {
            width: 34px;
            height: 34px;
            margin: 0 auto 18px;
            border: 3px solid rgba(255, 255, 255, 0.18);
            border-top-color: var(--qr-light-cyan);
            border-radius: 50%;
            animation: restaurantQrLoading 0.75s linear infinite;
        }

        @keyframes restaurantQrLoading {
            to {
                transform: rotate(360deg);
            }
        }

        .restaurant-qr-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            margin-bottom: 17px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 25px;
            background: linear-gradient(135deg,
                    rgba(7, 89, 133, 0.92),
                    rgba(14, 165, 183, 0.92));
            color: #ffffff;
        }

        .restaurant-qr-state-icon svg {
            width: 34px;
            height: 34px;
        }

        .restaurant-qr-state-title {
            margin: 0 0 8px;
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
        }

        .restaurant-qr-state-message {
            margin: 0;
            color: rgba(255, 255, 255, 0.68);
            font-size: 11px;
            line-height: 1.7;
        }

        .restaurant-qr-state-help {
            margin-top: 13px;
            padding: 11px 12px;
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.07);
            color: rgba(255, 255, 255, 0.76);
            font-size: 10px;
            line-height: 1.65;
        }

        .restaurant-qr-state-actions {
            display: flex;
            justify-content: center;
            margin-top: 18px;
        }

        .restaurant-qr-retry-button {
            min-height: 42px;
            padding: 10px 17px;
            border: 0;
            border-radius: 12px;
            background: #ffffff;
            color: var(--qr-blue);
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        /* =====================================================
               INFORMATION
            ====================================================== */

        .restaurant-qr-information {
            min-width: 0;
            padding: 22px;
            border: 1px solid var(--qr-border);
            border-radius: 22px;
            background: var(--qr-surface);
            box-shadow: 0 15px 35px rgba(20, 76, 104, 0.10);
        }

        .restaurant-qr-information-label {
            display: block;
            margin-bottom: 5px;
            color: var(--qr-blue);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .restaurant-qr-information-title {
            margin: 0 0 7px;
            color: var(--qr-text);
            font-size: 20px;
            font-weight: 800;
        }

        .restaurant-qr-information-description {
            margin: 0 0 18px;
            color: var(--qr-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        /* =====================================================
               GUIDE
            ====================================================== */

        .restaurant-qr-guide {
            display: grid;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .restaurant-qr-guide-item {
            display: grid;
            grid-template-columns: 34px minmax(0, 1fr);
            gap: 10px;
            align-items: center;
            padding: 11px;
            border: 1px solid var(--qr-border);
            border-radius: 13px;
            background: var(--qr-soft);
        }

        .restaurant-qr-guide-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 11px;
            background: linear-gradient(135deg,
                    var(--qr-blue),
                    var(--qr-cyan));
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
        }

        .restaurant-qr-guide-title {
            display: block;
            margin-bottom: 2px;
            color: var(--qr-text);
            font-size: 10px;
            font-weight: 800;
        }

        .restaurant-qr-guide-description {
            display: block;
            color: var(--qr-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /* =====================================================
               RESULT
            ====================================================== */

        .restaurant-qr-result {
            display: none;
            margin-top: 17px;
            padding: 16px;
            border: 1px solid rgba(22, 128, 91, 0.18);
            border-radius: 15px;
            background: #ecfbf3;
        }

        .restaurant-qr-result.is-visible {
            display: block;
        }

        .restaurant-qr-result-label {
            display: block;
            margin-bottom: 5px;
            color: var(--qr-success);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .restaurant-qr-result-value {
            display: block;
            margin-bottom: 12px;
            overflow-wrap: anywhere;
            color: #174c39;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.6;
        }

        .restaurant-qr-result-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .restaurant-qr-result-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 9px 11px;
            border: 0;
            border-radius: 10px;
            background: #ffffff;
            color: #12643e;
            font-family: inherit;
            font-size: 10px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .restaurant-qr-result-button.is-primary {
            background: linear-gradient(135deg,
                    var(--qr-blue),
                    var(--qr-cyan));
            color: #ffffff;
        }

        .restaurant-qr-result-button:hover {
            color: #12643e;
            text-decoration: none;
        }

        .restaurant-qr-result-button.is-primary:hover {
            color: #ffffff;
        }

        .restaurant-qr-security {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 16px;
            padding: 12px;
            border-radius: 12px;
            background: #fff8e8;
            color: #815010;
            font-size: 9px;
            line-height: 1.6;
        }

        /* =====================================================
               RESPONSIVE
            ====================================================== */

        @media (max-width: 900px) {
            .restaurant-qr-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575px) {
            .restaurant-qr-page {
                padding-top: 14px;
            }

            .restaurant-qr-camera-card {
                padding: 9px;
                border-radius: 21px;
            }

            .restaurant-qr-camera-shell {
                height: min(68vh, 530px);
                min-height: 430px;
                border-radius: 16px;
            }

            .restaurant-qr-information {
                padding: 18px;
                border-radius: 19px;
            }

            .restaurant-qr-scan-frame {
                width: min(70%, 270px);
            }
        }

        @media (max-width: 380px) {
            .restaurant-qr-camera-shell {
                height: 430px;
                min-height: 430px;
            }

            .restaurant-qr-state {
                padding: 18px;
            }

            .restaurant-qr-result-actions {
                grid-template-columns: 1fr;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .restaurant-qr-scan-line,
            .restaurant-qr-loading-ring {
                animation: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-qr-page">

            <header class="restaurant-qr-header">
                <span class="restaurant-qr-eyebrow">
                    Hotel Restaurant
                </span>

                <h1 class="restaurant-qr-title">
                    Scan QR Code
                </h1>

                <p class="restaurant-qr-description">
                    Point your camera at the restaurant QR code to view a menu,
                    table information, reservation, or order details.
                </p>
            </header>

            <div class="restaurant-qr-layout">

                {{-- Camera --}}
                <section class="restaurant-qr-camera-card">
                    <div class="restaurant-qr-camera-shell" id="restaurantQrCameraShell">

                        <div class="restaurant-qr-reader" id="restaurantQrReader">
                        </div>

                        <div class="restaurant-qr-video-overlay"></div>

                        <div class="restaurant-qr-scan-frame">
                            <span class="restaurant-qr-frame-corner is-top-left">
                            </span>

                            <span class="restaurant-qr-frame-corner is-top-right">
                            </span>

                            <span class="restaurant-qr-frame-corner is-bottom-left">
                            </span>

                            <span class="restaurant-qr-frame-corner is-bottom-right">
                            </span>

                            <span class="restaurant-qr-scan-line"></span>
                        </div>

                        <span class="restaurant-qr-live-status">
                            <span class="restaurant-qr-live-dot"></span>
                            Camera active
                        </span>

                        <div class="restaurant-qr-camera-instruction">
                            Keep the QR code inside the frame and hold your
                            device steady.
                        </div>

                        {{-- Loading --}}
                        <div class="restaurant-qr-state" id="restaurantQrLoadingState">

                            <div class="restaurant-qr-state-content">
                                <div class="restaurant-qr-loading-ring"></div>

                                <h2 class="restaurant-qr-state-title">
                                    Opening camera
                                </h2>

                                <p class="restaurant-qr-state-message">
                                    Please allow camera access when your browser
                                    asks for permission.
                                </p>
                            </div>
                        </div>

                        {{-- Error --}}
                        <div class="restaurant-qr-state" id="restaurantQrErrorState" hidden>

                            <div class="restaurant-qr-state-content">
                                <span class="restaurant-qr-state-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">

                                        <path
                                            d="M14.5 4H9.5l-1.7 2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-2.8l-1.7-2Z">
                                        </path>

                                        <circle cx="12" cy="13" r="3"></circle>

                                        <path d="m4 4 16 16"></path>
                                    </svg>
                                </span>

                                <h2 class="restaurant-qr-state-title" id="restaurantQrErrorTitle">
                                    Camera access is required
                                </h2>

                                <p class="restaurant-qr-state-message" id="restaurantQrErrorMessage">
                                    We cannot open your camera.
                                </p>

                                <div class="restaurant-qr-state-help" id="restaurantQrErrorHelp">
                                    Allow camera access and try again.
                                </div>

                                <div class="restaurant-qr-state-actions">
                                    <button class="restaurant-qr-retry-button" id="restaurantQrRetryButton" type="button">
                                        Try again
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Information --}}
                <aside class="restaurant-qr-information">
                    <span class="restaurant-qr-information-label">
                        How it works
                    </span>

                    <h2 class="restaurant-qr-information-title">
                        Scan in three easy steps
                    </h2>

                    <p class="restaurant-qr-information-description">
                        Make sure the QR code is clearly visible and your
                        camera lens is clean.
                    </p>

                    <ol class="restaurant-qr-guide">
                        <li class="restaurant-qr-guide-item">
                            <span class="restaurant-qr-guide-number">1</span>

                            <span>
                                <strong class="restaurant-qr-guide-title">
                                    Allow camera access
                                </strong>

                                <span class="restaurant-qr-guide-description">
                                    Your browser will request permission to use
                                    your camera.
                                </span>
                            </span>
                        </li>

                        <li class="restaurant-qr-guide-item">
                            <span class="restaurant-qr-guide-number">2</span>

                            <span>
                                <strong class="restaurant-qr-guide-title">
                                    Position the QR code
                                </strong>

                                <span class="restaurant-qr-guide-description">
                                    Place the entire QR code inside the scanning
                                    frame.
                                </span>
                            </span>
                        </li>

                        <li class="restaurant-qr-guide-item">
                            <span class="restaurant-qr-guide-number">3</span>

                            <span>
                                <strong class="restaurant-qr-guide-title">
                                    Review the result
                                </strong>

                                <span class="restaurant-qr-guide-description">
                                    The detected QR content will appear below.
                                </span>
                            </span>
                        </li>
                    </ol>

                    {{-- Result --}}
                    <div class="restaurant-qr-result" id="restaurantQrResult">

                        <span class="restaurant-qr-result-label">
                            QR code detected
                        </span>

                        <span class="restaurant-qr-result-value" id="restaurantQrResultValue">
                        </span>

                        <div class="restaurant-qr-result-actions">
                            <button class="restaurant-qr-result-button" id="restaurantQrCopyButton" type="button">
                                Copy result
                            </button>

                            <a class="restaurant-qr-result-button is-primary" id="restaurantQrOpenButton" href="#"
                                target="_blank" rel="noopener noreferrer">
                                Open result
                            </a>
                        </div>
                    </div>

                    <div class="restaurant-qr-security">
                        <span>ⓘ</span>

                        <span>
                            Review the detected link before opening it.
                            Camera processing is performed directly inside
                            your browser.
                        </span>
                    </div>
                </aside>
            </div>
        </main>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            'use strict';

            var cameraShell = document.getElementById(
                'restaurantQrCameraShell'
            );

            var readerElement = document.getElementById(
                'restaurantQrReader'
            );

            var loadingState = document.getElementById(
                'restaurantQrLoadingState'
            );

            var errorState = document.getElementById(
                'restaurantQrErrorState'
            );

            var errorTitle = document.getElementById(
                'restaurantQrErrorTitle'
            );

            var errorMessage = document.getElementById(
                'restaurantQrErrorMessage'
            );

            var errorHelp = document.getElementById(
                'restaurantQrErrorHelp'
            );

            var retryButton = document.getElementById(
                'restaurantQrRetryButton'
            );

            var resultBox = document.getElementById(
                'restaurantQrResult'
            );

            var resultValue = document.getElementById(
                'restaurantQrResultValue'
            );

            var copyButton = document.getElementById(
                'restaurantQrCopyButton'
            );

            var openButton = document.getElementById(
                'restaurantQrOpenButton'
            );

            var html5QrCode = null;
            var scannerIsRunning = false;
            var scannerIsStarting = false;
            var lastResult = '';
            var lastResultTime = 0;

            function showLoading() {
                loadingState.hidden = false;
                errorState.hidden = true;

                cameraShell.classList.remove(
                    'is-camera-active'
                );
            }

            function showCamera() {
                loadingState.hidden = true;
                errorState.hidden = true;

                cameraShell.classList.add(
                    'is-camera-active'
                );

                /*
                 * Pastikan semua canvas pemrosesan library
                 * tidak terlihat dan tidak mengambil layout.
                 */

                var canvases = readerElement.querySelectorAll(
                    'canvas'
                );

                canvases.forEach(function(canvas) {
                    canvas.style.position = 'absolute';
                    canvas.style.inset = '0';
                    canvas.style.zIndex = '-1';
                    canvas.style.opacity = '0';
                    canvas.style.visibility = 'hidden';
                    canvas.style.pointerEvents = 'none';
                });

                /*
                 * Hilangkan elemen shading dan frame bawaan.
                 * Hanya elemen video yang tetap terlihat.
                 */

                var scanRegion = document.getElementById(
                    'restaurantQrReader__scan_region'
                );

                if (scanRegion) {
                    Array.from(scanRegion.children).forEach(
                        function(child) {
                            if (
                                child.tagName !== 'VIDEO' &&
                                child.tagName !== 'CANVAS'
                            ) {
                                child.style.display = 'none';
                            }
                        }
                    );
                }
            }

            function showError(title, message, help) {
                loadingState.hidden = true;
                errorState.hidden = false;

                cameraShell.classList.remove(
                    'is-camera-active'
                );

                errorTitle.textContent = title;
                errorMessage.textContent = message;
                errorHelp.textContent = help;
            }

            function getErrorText(error) {
                if (!error) {
                    return '';
                }

                if (typeof error === 'string') {
                    return error.toLowerCase();
                }

                if (error.message) {
                    return error.message.toLowerCase();
                }

                return String(error).toLowerCase();
            }

            function isSafeUrl(value) {
                try {
                    var parsedUrl = new URL(value);

                    return parsedUrl.protocol === 'http:' ||
                        parsedUrl.protocol === 'https:';
                } catch (error) {
                    return false;
                }
            }

            function displayResult(value) {
                if (!value) {
                    return;
                }

                var currentTime = Date.now();

                if (
                    value === lastResult &&
                    currentTime - lastResultTime < 3000
                ) {
                    return;
                }

                lastResult = value;
                lastResultTime = currentTime;

                resultValue.textContent = value;
                resultBox.classList.add('is-visible');

                if (isSafeUrl(value)) {
                    openButton.href = value;
                    openButton.style.display = 'inline-flex';
                } else {
                    openButton.removeAttribute('href');
                    openButton.style.display = 'none';
                }

                if (navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }
            }

            function handleScannerError(error) {
                var errorText = getErrorText(error);

                if (
                    errorText.includes('permission') ||
                    errorText.includes('denied') ||
                    errorText.includes('notallowederror')
                ) {
                    showError(
                        'Camera permission was denied',
                        'Camera access is required to scan a QR code.',
                        'Open the browser site settings, set Camera to Allow, then tap “Try again”.'
                    );

                    return;
                }

                if (
                    errorText.includes('notfound') ||
                    errorText.includes('no camera') ||
                    errorText.includes('requested device not found')
                ) {
                    showError(
                        'No camera was found',
                        'We could not find an available camera on this device.',
                        'Check that your device has a working camera and try again.'
                    );

                    return;
                }

                if (
                    errorText.includes('notreadable') ||
                    errorText.includes('could not start') ||
                    errorText.includes('trackstarterror')
                ) {
                    showError(
                        'Camera is currently busy',
                        'Another application may be using your camera.',
                        'Close other camera applications or browser tabs, then tap “Try again”.'
                    );

                    return;
                }

                showError(
                    'Unable to open the camera',
                    'The QR scanner could not start your camera.',
                    'Make sure camera access is allowed and open this page using HTTPS or localhost.'
                );
            }

            async function clearScanner() {
                if (!html5QrCode) {
                    readerElement.innerHTML = '';
                    return;
                }

                if (scannerIsRunning) {
                    try {
                        await html5QrCode.stop();
                    } catch (error) {
                        /*
                         * Scanner mungkin sudah berhenti.
                         */
                    }
                }

                try {
                    html5QrCode.clear();
                } catch (error) {
                    /*
                     * Reader mungkin sudah kosong.
                     */
                }

                readerElement.innerHTML = '';

                html5QrCode = null;
                scannerIsRunning = false;
            }

            async function startScanner() {
                if (scannerIsStarting) {
                    return;
                }

                scannerIsStarting = true;
                showLoading();

                if (
                    window.location.protocol !== 'https:' &&
                    window.location.hostname !== 'localhost' &&
                    window.location.hostname !== '127.0.0.1'
                ) {
                    showError(
                        'A secure connection is required',
                        'The browser blocks camera access on an unsecured connection.',
                        'Open this page using HTTPS, Ngrok HTTPS, localhost, or 127.0.0.1.'
                    );

                    scannerIsStarting = false;
                    return;
                }

                if (typeof Html5Qrcode === 'undefined') {
                    showError(
                        'QR scanner could not be loaded',
                        'The QR scanner library is unavailable.',
                        'Check your internet connection, refresh the page, then tap “Try again”.'
                    );

                    scannerIsStarting = false;
                    return;
                }

                try {
                    await clearScanner();

                    html5QrCode = new Html5Qrcode(
                        'restaurantQrReader', {
                            verbose: false
                        }
                    );

                    /*
                     * Tidak menggunakan qrbox bawaan library.
                     * Frame scan hanya berasal dari HTML/CSS kita.
                     */

                    var scannerConfig = {
                        fps: 10,
                        disableFlip: false,

                        experimentalFeatures: {
                            useBarCodeDetectorIfSupported: true
                        }
                    };

                    await html5QrCode.start({
                            facingMode: 'environment'
                        },
                        scannerConfig,

                        function(decodedText) {
                            displayResult(decodedText);
                        },

                        function() {
                            /*
                             * Gagal membaca satu frame adalah normal.
                             */
                        }
                    );

                    scannerIsRunning = true;
                    showCamera();
                } catch (error) {
                    scannerIsRunning = false;
                    handleScannerError(error);
                } finally {
                    scannerIsStarting = false;
                }
            }

            retryButton.addEventListener(
                'click',
                function() {
                    startScanner();
                }
            );

            copyButton.addEventListener(
                'click',
                async function() {
                    if (!lastResult) {
                        return;
                    }

                    try {
                        await navigator.clipboard.writeText(
                            lastResult
                        );

                        copyButton.textContent = 'Copied';

                        window.setTimeout(function() {
                            copyButton.textContent =
                                'Copy result';
                        }, 1500);
                    } catch (error) {
                        copyButton.textContent =
                            'Copy failed';

                        window.setTimeout(function() {
                            copyButton.textContent =
                                'Copy result';
                        }, 1500);
                    }
                }
            );

            window.addEventListener(
                'pagehide',
                function() {
                    clearScanner();
                }
            );

            window.addEventListener(
                'beforeunload',
                function() {
                    clearScanner();
                }
            );

            startScanner();
        });
    </script>
@endpush
