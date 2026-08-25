@extends('users.layouts.main')

@push('style')
    <style>
        .restaurant-reservation-page {
            --reservation-text: #172033;
            --reservation-muted: #6f7d91;
            --reservation-surface: #ffffff;
            --reservation-soft: #f2f8fb;
            --reservation-border: #dbeaf0;

            padding: 20px 0 100px;
            color: var(--reservation-text);
        }

        .restaurant-reservation-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(280px, 0.9fr);
            gap: 18px;
            align-items: start;
        }

        .restaurant-reservation-ticket {
            position: relative;
            min-width: 0;
            padding: 29px;
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
            box-shadow: 0 24px 50px rgba(7, 89, 133, 0.25);
        }

        .restaurant-reservation-ticket::before {
            position: absolute;
            top: -65px;
            right: -65px;
            width: 190px;
            height: 190px;
            border: 32px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            content: "";
        }

        .restaurant-reservation-ticket::after {
            position: absolute;
            right: 24px;
            bottom: 23px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            content: "";
        }

        .restaurant-reservation-content {
            position: relative;
            z-index: 2;
        }

        .restaurant-reservation-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 27px;
        }

        .restaurant-reservation-eyebrow {
            margin: 0 0 7px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.13em;
            text-transform: uppercase;
        }

        .restaurant-reservation-number {
            margin: 0;
            color: #ffffff;
            font-size: clamp(23px, 5vw, 34px);
            font-weight: 800;
            line-height: 1.2;
            word-break: break-word;
        }

        .restaurant-reservation-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            flex: 0 0 auto;
            padding: 8px 11px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .restaurant-reservation-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #67e8f9;
            box-shadow: 0 0 0 4px rgba(103, 232, 249, 0.15);
        }

        .restaurant-reservation-status.is-cancelled .restaurant-reservation-status-dot {
            background: #fda4af;
            box-shadow: 0 0 0 4px rgba(253, 164, 175, 0.15);
        }

        .restaurant-reservation-status.is-completed .restaurant-reservation-status-dot {
            background: #86efac;
            box-shadow: 0 0 0 4px rgba(134, 239, 172, 0.15);
        }

        .restaurant-reservation-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
        }

        .restaurant-reservation-detail {
            min-width: 0;
            padding: 14px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(7px);
        }

        .restaurant-reservation-detail-wide {
            grid-column: 1 / -1;
        }

        .restaurant-reservation-detail-label {
            display: block;
            margin-bottom: 5px;
            color: rgba(255, 255, 255, 0.64);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .restaurant-reservation-detail-value {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
        }

        .restaurant-reservation-detail-value svg {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
            color: #a5f3fc;
        }

        .restaurant-reservation-footer-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 21px 0 0;
            padding-top: 18px;
            border-top: 1px dashed rgba(255, 255, 255, 0.25);
            color: rgba(255, 255, 255, 0.73);
            font-size: 11px;
            line-height: 1.55;
        }

        .restaurant-reservation-footer-note svg {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
            margin-top: 1px;
            color: #67e8f9;
        }

        .restaurant-reservation-side {
            display: grid;
            gap: 15px;
        }

        .restaurant-reservation-card {
            padding: 21px;
            border: 1px solid var(--reservation-border);
            border-radius: 22px;
            background: var(--reservation-surface);
            box-shadow: 0 16px 35px rgba(29, 78, 99, 0.09);
        }

        .restaurant-reservation-card-label {
            margin: 0 0 5px;
            color: #087ca5;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.13em;
            text-transform: uppercase;
        }

        .restaurant-reservation-card-title {
            margin: 0 0 8px;
            color: var(--reservation-text);
            font-size: 18px;
            font-weight: 800;
        }

        .restaurant-reservation-card-description {
            margin: 0;
            color: var(--reservation-muted);
            font-size: 12px;
            line-height: 1.65;
        }

        .restaurant-reservation-reminder {
            display: grid;
            gap: 10px;
            margin-top: 17px;
        }

        .restaurant-reservation-reminder-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px;
            border: 1px solid var(--reservation-border);
            border-radius: 14px;
            background: var(--reservation-soft);
        }

        .restaurant-reservation-reminder-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 29px;
            height: 29px;
            flex: 0 0 29px;
            border-radius: 9px;
            background: linear-gradient(135deg, #075985, #22d3ee);
            color: #ffffff;
        }

        .restaurant-reservation-reminder-icon svg {
            width: 14px;
            height: 14px;
        }

        .restaurant-reservation-reminder-item strong {
            display: block;
            margin-bottom: 2px;
            color: var(--reservation-text);
            font-size: 11px;
        }

        .restaurant-reservation-reminder-item span {
            display: block;
            color: var(--reservation-muted);
            font-size: 10px;
            line-height: 1.45;
        }

        .restaurant-reservation-cancel-form {
            display: grid;
            gap: 10px;
            margin-top: 17px;
        }

        .restaurant-reservation-field {
            display: grid;
            gap: 7px;
            margin: 0;
        }

        .restaurant-reservation-label {
            color: var(--reservation-text);
            font-size: 11px;
            font-weight: 750;
        }

        .restaurant-reservation-input {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid var(--reservation-border);
            border-radius: 13px;
            outline: none;
            background: var(--reservation-surface);
            color: var(--reservation-text);
            font-family: inherit;
            font-size: 12px;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .restaurant-reservation-input::placeholder {
            color: #9aa8b8;
        }

        .restaurant-reservation-input:focus {
            border-color: #e11d48;
            box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1);
        }

        .restaurant-reservation-actions {
            display: grid;
            gap: 9px;
        }

        .restaurant-reservation-action-icon {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
        }

        .restaurant-reservation-cancelled {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-top: 17px;
            padding: 13px;
            border: 1px solid #fecdd3;
            border-radius: 14px;
            background: #fff1f2;
            color: #9f1239;
        }

        .restaurant-reservation-cancelled svg {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
        }

        .restaurant-reservation-cancelled strong {
            display: block;
            margin-bottom: 2px;
            font-size: 12px;
        }

        .restaurant-reservation-cancelled span {
            display: block;
            font-size: 10px;
            line-height: 1.5;
        }

        [data-theme="dark"] .restaurant-reservation-page {
            --reservation-text: #edf6fb;
            --reservation-muted: #9caebe;
            --reservation-surface: #111c28;
            --reservation-soft: #162532;
            --reservation-border: #294353;
        }

        [data-theme="dark"] .restaurant-reservation-card {
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] .restaurant-reservation-input {
            background: #0d1823;
        }

        [data-theme="dark"] .restaurant-reservation-cancelled {
            border-color: rgba(251, 113, 133, 0.25);
            background: rgba(159, 18, 57, 0.16);
            color: #fda4af;
        }

        @media (max-width: 820px) {
            .restaurant-reservation-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 520px) {
            .restaurant-reservation-page {
                padding-top: 14px;
            }

            .restaurant-reservation-ticket {
                padding: 21px 18px;
                border-radius: 22px;
            }

            .restaurant-reservation-header {
                display: grid;
                margin-bottom: 21px;
            }

            .restaurant-reservation-status {
                width: max-content;
            }

            .restaurant-reservation-details {
                grid-template-columns: 1fr;
            }

            .restaurant-reservation-detail-wide {
                grid-column: auto;
            }

            .restaurant-reservation-card {
                padding: 18px;
                border-radius: 19px;
            }
        }

        @media (max-width: 350px) {
            .restaurant-reservation-number {
                font-size: 21px;
            }

            .restaurant-reservation-ticket,
            .restaurant-reservation-card {
                border-radius: 16px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-reservation-page">
            <div class="restaurant-reservation-layout">
                <article class="restaurant-reservation-ticket">
                    <div class="restaurant-reservation-content">
                        <header class="restaurant-reservation-header">
                            <div>
                                <p class="restaurant-reservation-eyebrow">
                                    Reservation number
                                </p>

                                <h1 class="restaurant-reservation-number">
                                    {{ $reservation->reservation_number }}
                                </h1>
                            </div>

                            <span
                                class="restaurant-reservation-status
                                @if ($reservation->status === 'cancelled') is-cancelled
                                @elseif ($reservation->status === 'completed') is-completed @endif">
                                <span class="restaurant-reservation-status-dot"></span>
                                {{ str_replace('_', ' ', strtoupper($reservation->status)) }}
                            </span>
                        </header>

                        <div class="restaurant-reservation-details">
                            <section class="restaurant-reservation-detail">
                                <span class="restaurant-reservation-detail-label">
                                    Table
                                </span>

                                <div class="restaurant-reservation-detail-value">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M4 10h16"></path>
                                        <path d="M6 10V7h12v3"></path>
                                        <path d="M7 10v9"></path>
                                        <path d="M17 10v9"></path>
                                    </svg>

                                    Table {{ $reservation->restaurantTable->table_number }}
                                </div>
                            </section>

                            <section class="restaurant-reservation-detail">
                                <span class="restaurant-reservation-detail-label">
                                    Dining area
                                </span>

                                <div class="restaurant-reservation-detail-value">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="2"></circle>
                                    </svg>

                                    {{ ucfirst($reservation->restaurantTable->area) }}
                                </div>
                            </section>

                            <section class="restaurant-reservation-detail restaurant-reservation-detail-wide">
                                <span class="restaurant-reservation-detail-label">
                                    Reservation schedule
                                </span>

                                <div class="restaurant-reservation-detail-value">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                        <path d="M16 3v4"></path>
                                        <path d="M8 3v4"></path>
                                        <path d="M3 11h18"></path>
                                    </svg>

                                    {{ $reservation->reservation_start->format('d M Y, H:i') }}
                                    –
                                    {{ $reservation->reservation_end->format('H:i') }}
                                </div>
                            </section>

                            <section class="restaurant-reservation-detail">
                                <span class="restaurant-reservation-detail-label">
                                    Guest
                                </span>

                                <div class="restaurant-reservation-detail-value">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>

                                    {{ $reservation->guest_count }} guests
                                </div>
                            </section>

                            <section class="restaurant-reservation-detail">
                                <span class="restaurant-reservation-detail-label">
                                    Reserved by
                                </span>

                                <div class="restaurant-reservation-detail-value">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="8" r="4"></circle>
                                        <path d="M4 21a8 8 0 0 1 16 0"></path>
                                    </svg>

                                    {{ $reservation->customer_name }}
                                </div>
                            </section>

                            @if ($reservation->special_request)
                                <section class="restaurant-reservation-detail restaurant-reservation-detail-wide">
                                    <span class="restaurant-reservation-detail-label">
                                        Special request
                                    </span>

                                    <div class="restaurant-reservation-detail-value">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z"></path>
                                        </svg>

                                        {{ $reservation->special_request }}
                                    </div>
                                </section>
                            @endif
                        </div>

                        <p class="restaurant-reservation-footer-note">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 16v-4"></path>
                                <path d="M12 8h.01"></path>
                            </svg>

                            Please arrive at least 10 minutes before your reservation time
                            and show this reservation number to our restaurant team.
                        </p>
                    </div>
                </article>

                <aside class="restaurant-reservation-side">
                    <section class="restaurant-reservation-card">
                        <p class="restaurant-reservation-card-label">
                            Dining reminder
                        </p>

                        <h2 class="restaurant-reservation-card-title">
                            Your table is being prepared
                        </h2>

                        <p class="restaurant-reservation-card-description">
                            Keep your reservation number available when you arrive
                            at the restaurant.
                        </p>

                        <div class="restaurant-reservation-reminder">
                            <div class="restaurant-reservation-reminder-item">
                                <span class="restaurant-reservation-reminder-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 6v6l4 2"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                    </svg>
                                </span>

                                <div>
                                    <strong>Arrive on time</strong>
                                    <span>Please arrive 10 minutes before your schedule.</span>
                                </div>
                            </div>

                            <div class="restaurant-reservation-reminder-item">
                                <span class="restaurant-reservation-reminder-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg>
                                </span>

                                <div>
                                    <strong>Show your reservation</strong>
                                    <span>Present this reservation number to our team.</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    @if (!in_array($reservation->status, ['completed', 'cancelled']))
                        <section class="restaurant-reservation-card">
                            <p class="restaurant-reservation-card-label">
                                Reservation management
                            </p>

                            <h2 class="restaurant-reservation-card-title">
                                Need to cancel?
                            </h2>

                            <p class="restaurant-reservation-card-description">
                                Tell us your reason before cancelling this reservation.
                                This action may not be reversible.
                            </p>

                            <form class="restaurant-reservation-cancel-form" id="restaurantReservationCancelForm"
                                action="{{ route('reservations.cancel', $reservation) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="token" value="{{ request('token') }}">

                                <label class="restaurant-reservation-field">
                                    <span class="restaurant-reservation-label">
                                        Cancellation reason
                                    </span>

                                    <input class="restaurant-reservation-input" type="text" name="reason"
                                        value="{{ old('reason') }}" placeholder="Explain why you need to cancel"
                                        required>
                                </label>

                                <div class="restaurant-reservation-actions">
                                    <button class="hotel-btn hotel-btn-delete hotel-btn-w-100" type="submit"
                                        id="restaurantReservationCancelButton">
                                        <svg class="restaurant-reservation-action-icon" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg>

                                        <span id="restaurantReservationCancelText">
                                            Cancel reservation
                                        </span>
                                    </button>

                                    <a class="hotel-btn hotel-btn-neutral hotel-btn-w-100"
                                        href="{{ route('menus.index') }}">
                                        <svg class="restaurant-reservation-action-icon" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="m15 18-6-6 6-6"></path>
                                            <path d="M9 12h10"></path>
                                        </svg>

                                        Back to menu
                                    </a>
                                </div>
                            </form>
                        </section>
                    @else
                        <section class="restaurant-reservation-card">
                            <p class="restaurant-reservation-card-label">
                                Reservation status
                            </p>

                            @if ($reservation->status === 'cancelled')
                                <h2 class="restaurant-reservation-card-title">
                                    Reservation cancelled
                                </h2>

                                <p class="restaurant-reservation-card-description">
                                    This reservation is no longer active.
                                </p>

                                <div class="restaurant-reservation-cancelled">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="m15 9-6 6"></path>
                                        <path d="m9 9 6 6"></path>
                                    </svg>

                                    <div>
                                        <strong>Cancelled</strong>
                                        <span>You can create a new reservation at any time.</span>
                                    </div>
                                </div>
                            @else
                                <h2 class="restaurant-reservation-card-title">
                                    Dining completed
                                </h2>

                                <p class="restaurant-reservation-card-description">
                                    Thank you for dining with us. We look forward to
                                    welcoming you again.
                                </p>
                            @endif

                            <div class="restaurant-reservation-actions" style="margin-top: 17px;">
                                <a class="hotel-btn hotel-btn-add hotel-btn-w-100"
                                    href="{{ route('reservations.create') }}">
                                    Create new reservation
                                </a>

                                <a class="hotel-btn hotel-btn-neutral hotel-btn-w-100" href="{{ route('menus.index') }}">
                                    Back to menu
                                </a>
                            </div>
                        </section>
                    @endif
                </aside>
            </div>
        </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelForm = document.getElementById('restaurantReservationCancelForm');
            const cancelButton = document.getElementById('restaurantReservationCancelButton');
            const cancelText = document.getElementById('restaurantReservationCancelText');

            if (cancelForm && cancelButton && cancelText) {
                cancelForm.addEventListener('submit', function(event) {
                    const confirmed = window.confirm(
                        'Are you sure you want to cancel this reservation?'
                    );

                    if (!confirmed) {
                        event.preventDefault();
                        return;
                    }

                    cancelButton.disabled = true;
                    cancelButton.classList.add('is-disabled');
                    cancelText.textContent = 'Cancelling reservation...';
                });
            }
        });
    </script>
@endpush
