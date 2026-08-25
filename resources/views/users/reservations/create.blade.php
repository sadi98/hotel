@extends('users.layouts.main')

@push('style')
    <style>
        .restaurant-reserve-page {
            --reserve-text: #172033;
            --reserve-muted: #6f7d91;
            --reserve-surface: #ffffff;
            --reserve-soft: #f3f9fc;
            --reserve-border: #dbeaf0;
            --reserve-input: #ffffff;

            padding: 20px 0 100px;
            color: var(--reserve-text);
        }

        .restaurant-reserve-hero {
            position: relative;
            margin-bottom: 18px;
            padding: 28px;
            overflow: hidden;
            border-radius: 26px;
            background:
                radial-gradient(circle at 92% 10%,
                    rgba(103, 232, 249, 0.38),
                    transparent 30%),
                linear-gradient(140deg,
                    #071827 0%,
                    #075985 52%,
                    #0ea5b7 100%);
            color: #ffffff;
            box-shadow: 0 22px 45px rgba(7, 89, 133, 0.22);
        }

        .restaurant-reserve-hero::after {
            position: absolute;
            right: -65px;
            bottom: -105px;
            width: 220px;
            height: 220px;
            border: 35px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            content: "";
        }

        .restaurant-reserve-hero-content {
            position: relative;
            z-index: 2;
            max-width: 680px;
        }

        .restaurant-reserve-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 14px;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .restaurant-reserve-eyebrow-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #67e8f9;
            box-shadow: 0 0 0 5px rgba(103, 232, 249, 0.15);
        }

        .restaurant-reserve-title {
            margin: 0 0 9px;
            color: #ffffff;
            font-size: clamp(27px, 5vw, 39px);
            font-weight: 800;
            line-height: 1.12;
        }

        .restaurant-reserve-description {
            max-width: 600px;
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 14px;
            line-height: 1.7;
        }

        .restaurant-reserve-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 290px;
            gap: 18px;
            align-items: start;
        }

        .restaurant-reserve-panel,
        .restaurant-reserve-info {
            border: 1px solid var(--reserve-border);
            border-radius: 23px;
            background: var(--reserve-surface);
            box-shadow: 0 16px 35px rgba(29, 78, 99, 0.09);
        }

        .restaurant-reserve-panel {
            padding: 23px;
        }

        .restaurant-reserve-panel-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
        }

        .restaurant-reserve-panel-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            border-radius: 14px;
            background: linear-gradient(135deg, #075985, #22d3ee);
            color: #ffffff;
            box-shadow: 0 9px 20px rgba(6, 182, 212, 0.2);
        }

        .restaurant-reserve-panel-icon svg {
            width: 21px;
            height: 21px;
        }

        .restaurant-reserve-panel-title {
            margin: 0 0 3px;
            color: var(--reserve-text);
            font-size: 18px;
            font-weight: 800;
        }

        .restaurant-reserve-panel-subtitle {
            margin: 0;
            color: var(--reserve-muted);
            font-size: 12px;
        }

        .restaurant-reserve-error {
            display: flex;
            gap: 12px;
            margin-bottom: 17px;
            padding: 14px;
            border: 1px solid #fecdd3;
            border-radius: 15px;
            background: #fff1f2;
            color: #9f1239;
        }

        .restaurant-reserve-error-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 31px;
            height: 31px;
            flex: 0 0 31px;
            border-radius: 10px;
            background: #ffe4e6;
        }

        .restaurant-reserve-error-title {
            margin: 0 0 5px;
            font-size: 13px;
            font-weight: 800;
        }

        .restaurant-reserve-error-list {
            display: grid;
            gap: 3px;
            margin: 0;
            padding-left: 17px;
            font-size: 12px;
            line-height: 1.5;
        }

        .restaurant-reserve-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .restaurant-reserve-field {
            display: grid;
            min-width: 0;
            gap: 7px;
            margin: 0;
        }

        .restaurant-reserve-wide {
            grid-column: 1 / -1;
        }

        .restaurant-reserve-label {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            color: var(--reserve-text);
            font-size: 12px;
            font-weight: 750;
        }

        .restaurant-reserve-optional {
            color: var(--reserve-muted);
            font-size: 10px;
            font-weight: 500;
        }

        .restaurant-reserve-control {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid var(--reserve-border);
            border-radius: 13px;
            outline: none;
            background: var(--reserve-input);
            color: var(--reserve-text);
            font-family: inherit;
            font-size: 13px;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .restaurant-reserve-control::placeholder {
            color: #9aa8b8;
        }

        .restaurant-reserve-control:hover {
            border-color: #a9d2df;
        }

        .restaurant-reserve-control:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.12);
        }

        textarea.restaurant-reserve-control {
            min-height: 105px;
            resize: vertical;
            line-height: 1.6;
        }

        select.restaurant-reserve-control {
            cursor: pointer;
        }

        .restaurant-reserve-hint {
            margin: 0;
            color: var(--reserve-muted);
            font-size: 10px;
            line-height: 1.5;
        }

        .restaurant-reserve-actions {
            display: grid;
            gap: 9px;
            margin-top: 19px;
        }

        .restaurant-reserve-submit {
            min-height: 50px;
        }

        .restaurant-reserve-submit-icon,
        .restaurant-reserve-back-icon {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
        }

        .restaurant-reserve-info {
            position: sticky;
            top: 88px;
            padding: 20px;
        }

        .restaurant-reserve-info-label {
            margin: 0 0 5px;
            color: #087ca5;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.13em;
            text-transform: uppercase;
        }

        .restaurant-reserve-info-title {
            margin: 0 0 9px;
            color: var(--reserve-text);
            font-size: 18px;
            font-weight: 800;
        }

        .restaurant-reserve-info-description {
            margin: 0 0 18px;
            color: var(--reserve-muted);
            font-size: 12px;
            line-height: 1.65;
        }

        .restaurant-reserve-info-list {
            display: grid;
            gap: 11px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .restaurant-reserve-info-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px;
            border: 1px solid var(--reserve-border);
            border-radius: 14px;
            background: var(--reserve-soft);
        }

        .restaurant-reserve-info-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 27px;
            height: 27px;
            flex: 0 0 27px;
            border-radius: 9px;
            background: linear-gradient(135deg, #075985, #22d3ee);
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
        }

        .restaurant-reserve-info-item strong {
            display: block;
            margin-bottom: 2px;
            color: var(--reserve-text);
            font-size: 11px;
        }

        .restaurant-reserve-info-item span {
            display: block;
            color: var(--reserve-muted);
            font-size: 10px;
            line-height: 1.45;
        }

        [data-theme="dark"] .restaurant-reserve-page {
            --reserve-text: #edf6fb;
            --reserve-muted: #9caebe;
            --reserve-surface: #111c28;
            --reserve-soft: #162532;
            --reserve-border: #294353;
            --reserve-input: #0d1823;
        }

        [data-theme="dark"] .restaurant-reserve-panel,
        [data-theme="dark"] .restaurant-reserve-info {
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] .restaurant-reserve-error {
            border-color: rgba(251, 113, 133, 0.25);
            background: rgba(159, 18, 57, 0.16);
            color: #fda4af;
        }

        [data-theme="dark"] .restaurant-reserve-error-icon {
            background: rgba(225, 29, 72, 0.18);
        }

        [data-theme="dark"] .restaurant-reserve-control {
            color-scheme: dark;
        }

        @media (max-width: 850px) {
            .restaurant-reserve-layout {
                grid-template-columns: 1fr;
            }

            .restaurant-reserve-info {
                position: static;
            }
        }

        @media (max-width: 560px) {
            .restaurant-reserve-page {
                padding-top: 14px;
            }

            .restaurant-reserve-hero {
                padding: 23px 19px;
                border-radius: 21px;
            }

            .restaurant-reserve-panel,
            .restaurant-reserve-info {
                padding: 17px;
                border-radius: 19px;
            }

            .restaurant-reserve-grid {
                grid-template-columns: 1fr;
            }

            .restaurant-reserve-wide {
                grid-column: auto;
            }
        }

        @media (max-width: 360px) {

            .restaurant-reserve-hero,
            .restaurant-reserve-panel,
            .restaurant-reserve-info {
                border-radius: 16px;
            }

            .restaurant-reserve-panel-heading {
                align-items: flex-start;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .restaurant-reserve-control {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-reserve-page">
            <section class="restaurant-reserve-hero">
                <div class="restaurant-reserve-hero-content">
                    <div class="restaurant-reserve-eyebrow">
                        <span class="restaurant-reserve-eyebrow-dot"></span>
                        An Exclusive Dining Experience
                    </div>

                    <h1 class="restaurant-reserve-title">
                        Reserve your perfect table
                    </h1>

                    <p class="restaurant-reserve-description">
                        Plan an unforgettable dining experience. Choose your preferred
                        table, date, and time, then let our team prepare everything for you.
                    </p>
                </div>
            </section>

            <div class="restaurant-reserve-layout">
                <section class="restaurant-reserve-panel">
                    <div class="restaurant-reserve-panel-heading">
                        <span class="restaurant-reserve-panel-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M3 11h18"></path>
                            </svg>
                        </span>

                        <div>
                            <h2 class="restaurant-reserve-panel-title">
                                Reservation details
                            </h2>

                            <p class="restaurant-reserve-panel-subtitle">
                                Complete the information below to reserve your table.
                            </p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="restaurant-reserve-error" role="alert">
                            <span class="restaurant-reserve-error-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 8v4"></path>
                                    <path d="M12 16h.01"></path>
                                </svg>
                            </span>

                            <div>
                                <p class="restaurant-reserve-error-title">
                                    Please check your reservation details
                                </p>

                                <ul class="restaurant-reserve-error-list">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('reservations.store') }}" method="POST" id="restaurantReservationForm">
                        @csrf

                        <div class="restaurant-reserve-grid">
                            <label class="restaurant-reserve-field">
                                <span class="restaurant-reserve-label">
                                    Full name
                                </span>

                                <input class="restaurant-reserve-control" type="text" name="customer_name"
                                    value="{{ old('customer_name', auth()->user()?->name) }}"
                                    placeholder="Enter your full name" autocomplete="name" required>
                            </label>

                            <label class="restaurant-reserve-field">
                                <span class="restaurant-reserve-label">
                                    Phone number
                                </span>

                                <input class="restaurant-reserve-control" type="tel" name="customer_phone"
                                    value="{{ old('customer_phone', auth()->user()?->phone) }}"
                                    placeholder="Example: 081234567890" autocomplete="tel" inputmode="tel" required>
                            </label>

                            <label class="restaurant-reserve-field restaurant-reserve-wide">
                                <span class="restaurant-reserve-label">
                                    Email address
                                    <span class="restaurant-reserve-optional">Optional</span>
                                </span>

                                <input class="restaurant-reserve-control" type="email" name="customer_email"
                                    value="{{ old('customer_email', auth()->user()?->email) }}"
                                    placeholder="name@example.com" autocomplete="email">
                            </label>

                            <label class="restaurant-reserve-field">
                                <span class="restaurant-reserve-label">
                                    Number of guests
                                </span>

                                <input class="restaurant-reserve-control" type="number" name="guest_count" min="1"
                                    max="50" value="{{ old('guest_count', 2) }}" inputmode="numeric" required>
                            </label>

                            <label class="restaurant-reserve-field">
                                <span class="restaurant-reserve-label">
                                    Dining duration
                                </span>

                                <select class="restaurant-reserve-control" name="duration_minutes" required>
                                    <option value="60" @selected(old('duration_minutes') == 60)>
                                        1 hour
                                    </option>

                                    <option value="90" @selected(old('duration_minutes') == 90)>
                                        1 hour 30 minutes
                                    </option>

                                    <option value="120" @selected(old('duration_minutes', 120) == 120)>
                                        2 hours
                                    </option>

                                    <option value="180" @selected(old('duration_minutes') == 180)>
                                        3 hours
                                    </option>

                                    <option value="240" @selected(old('duration_minutes') == 240)>
                                        4 hours
                                    </option>

                                    <option value="360" @selected(old('duration_minutes') == 360)>
                                        6 hours
                                    </option>
                                </select>
                            </label>

                            <label class="restaurant-reserve-field restaurant-reserve-wide">
                                <span class="restaurant-reserve-label">
                                    Reservation date and time
                                </span>

                                <input class="restaurant-reserve-control" id="restaurantReservationStart"
                                    type="datetime-local" name="reservation_start" value="{{ old('reservation_start') }}"
                                    required>

                                <p class="restaurant-reserve-hint">
                                    Select an available date and arrival time.
                                </p>
                            </label>

                            <label class="restaurant-reserve-field restaurant-reserve-wide">
                                <span class="restaurant-reserve-label">
                                    Preferred table
                                </span>

                                <select class="restaurant-reserve-control" name="restaurant_table_id" required>
                                    <option value="">Select an available table</option>

                                    @foreach ($tables as $table)
                                        <option value="{{ $table->id }}" @selected(old('restaurant_table_id') == $table->id)>
                                            Table {{ $table->table_number }}
                                            · {{ ucfirst($table->area) }}
                                            · Up to {{ $table->capacity }} guests
                                        </option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="restaurant-reserve-field restaurant-reserve-wide">
                                <span class="restaurant-reserve-label">
                                    Special request
                                    <span class="restaurant-reserve-optional">Optional</span>
                                </span>

                                <textarea class="restaurant-reserve-control" name="special_request"
                                    placeholder="Tell us about dietary requirements, celebrations, seating preferences, or other requests.">{{ old('special_request') }}</textarea>
                            </label>
                        </div>

                        <div class="restaurant-reserve-actions">
                            <button class="hotel-btn hotel-btn-add hotel-btn-lg hotel-btn-w-100 restaurant-reserve-submit"
                                type="submit" id="restaurantReservationSubmit">
                                <svg class="restaurant-reserve-submit-icon" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>

                                <span id="restaurantReservationSubmitText">
                                    Reserve table
                                </span>
                            </button>

                            <a class="hotel-btn hotel-btn-neutral hotel-btn-w-100" href="{{ route('menus.index') }}">
                                <svg class="restaurant-reserve-back-icon" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="m15 18-6-6 6-6"></path>
                                    <path d="M9 12h10"></path>
                                </svg>

                                Back to menu
                            </a>
                        </div>
                    </form>
                </section>

                <aside class="restaurant-reserve-info">
                    <p class="restaurant-reserve-info-label">
                        Reservation guide
                    </p>

                    <h2 class="restaurant-reserve-info-title">
                        Before you reserve
                    </h2>

                    <p class="restaurant-reserve-info-description">
                        Make sure the information you provide is correct so our team
                        can prepare the best dining experience for you.
                    </p>

                    <ol class="restaurant-reserve-info-list">
                        <li class="restaurant-reserve-info-item">
                            <span class="restaurant-reserve-info-number">1</span>

                            <div>
                                <strong>Choose your schedule</strong>
                                <span>Select your preferred dining date and time.</span>
                            </div>
                        </li>

                        <li class="restaurant-reserve-info-item">
                            <span class="restaurant-reserve-info-number">2</span>

                            <div>
                                <strong>Select a suitable table</strong>
                                <span>Match the table capacity with your guest count.</span>
                            </div>
                        </li>

                        <li class="restaurant-reserve-info-item">
                            <span class="restaurant-reserve-info-number">3</span>

                            <div>
                                <strong>Review your details</strong>
                                <span>Check your contact information before submitting.</span>
                            </div>
                        </li>
                    </ol>
                </aside>
            </div>
        </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reservationForm = document.getElementById('restaurantReservationForm');
            const submitButton = document.getElementById('restaurantReservationSubmit');
            const submitText = document.getElementById('restaurantReservationSubmitText');
            const reservationStart = document.getElementById('restaurantReservationStart');

            if (reservationStart && !reservationStart.value) {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                reservationStart.min = now.toISOString().slice(0, 16);
            }

            if (reservationForm && submitButton && submitText) {
                reservationForm.addEventListener('submit', function() {
                    submitButton.disabled = true;
                    submitButton.classList.add('is-disabled');
                    submitText.textContent = 'Creating reservation...';
                });
            }
        });
    </script>
@endpush
