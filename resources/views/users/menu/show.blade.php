@extends('users.layouts.main')

@push('style')
    <style>
        .restaurant-detail-page {
            --restaurant-detail-text: #172033;
            --restaurant-detail-muted: #748094;
            --restaurant-detail-surface: #ffffff;
            --restaurant-detail-soft: #f2f8fb;
            --restaurant-detail-border: #dcebf0;

            padding: 20px 0 90px;
            color: var(--restaurant-detail-text);
        }

        .restaurant-detail-panel {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(320px, 0.92fr);
            gap: 0;
            overflow: hidden;
            border: 1px solid var(--restaurant-detail-border);
            border-radius: 26px;
            background: var(--restaurant-detail-surface);
            box-shadow: 0 18px 42px rgba(20, 76, 104, 0.12);
        }

        .restaurant-detail-visual {
            position: relative;
            min-height: 570px;
            overflow: hidden;
            background: linear-gradient(145deg, #d9f1f5, #267f9b);
        }

        .restaurant-detail-photo {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 0.5s ease;
        }

        .restaurant-detail-panel:hover .restaurant-detail-photo {
            transform: scale(1.025);
        }

        .restaurant-detail-photo-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to top,
                    rgba(3, 29, 46, 0.62),
                    transparent 50%);
        }

        .restaurant-detail-photo-empty {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.90);
            font-size: 68px;
        }

        .restaurant-detail-visual-label {
            position: absolute;
            top: 18px;
            left: 18px;
            z-index: 2;
            padding: 8px 11px;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 999px;
            background: rgba(4, 37, 56, 0.72);
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            backdrop-filter: blur(9px);
        }

        .restaurant-detail-visual-caption {
            position: absolute;
            right: 20px;
            bottom: 20px;
            left: 20px;
            z-index: 2;
            color: rgba(255, 255, 255, 0.80);
            font-size: 10px;
            font-weight: 700;
        }

        .restaurant-detail-content {
            display: flex;
            min-width: 0;
            flex-direction: column;
            padding: 30px;
        }

        .restaurant-detail-tag {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
            color: #087ea4;
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .restaurant-detail-tag-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #18b9c7;
        }

        .restaurant-detail-alcohol {
            padding: 5px 8px;
            border-radius: 8px;
            background: #fff1e8;
            color: #a84616;
            font-size: 8px;
        }

        .restaurant-detail-name {
            margin: 0 0 11px;
            color: var(--restaurant-detail-text);
            font-size: clamp(29px, 5vw, 42px);
            font-weight: 850;
            line-height: 1.12;
            letter-spacing: -1px;
        }

        .restaurant-detail-description {
            margin: 0 0 20px;
            color: var(--restaurant-detail-muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .restaurant-detail-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }

        .restaurant-detail-info {
            padding: 12px;
            border: 1px solid var(--restaurant-detail-border);
            border-radius: 13px;
            background: var(--restaurant-detail-soft);
        }

        .restaurant-detail-info-label {
            display: block;
            margin-bottom: 3px;
            color: var(--restaurant-detail-muted);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .restaurant-detail-info-value {
            color: var(--restaurant-detail-text);
            font-size: 10px;
            font-weight: 800;
        }

        .restaurant-detail-price-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 13px;
            margin-bottom: 21px;
            padding: 16px;
            border: 1px solid rgba(24, 185, 199, 0.17);
            border-radius: 16px;
            background:
                linear-gradient(135deg,
                    rgba(7, 89, 133, 0.06),
                    rgba(24, 185, 199, 0.12));
        }

        .restaurant-detail-price-label {
            display: block;
            margin-bottom: 3px;
            color: var(--restaurant-detail-muted);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .restaurant-detail-price {
            color: #087ea4;
            font-size: 22px;
            font-weight: 900;
        }

        .restaurant-detail-available {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #13805b;
            font-size: 9px;
            font-weight: 800;
        }

        .restaurant-detail-available::before {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #15b878;
            content: "";
            box-shadow: 0 0 0 4px rgba(21, 184, 120, 0.11);
        }

        .restaurant-detail-form {
            display: grid;
            gap: 13px;
            margin-top: auto;
        }

        .restaurant-detail-field {
            display: grid;
            gap: 6px;
        }

        .restaurant-detail-field-label {
            color: var(--restaurant-detail-text);
            font-size: 10px;
            font-weight: 800;
        }

        .restaurant-detail-input {
            width: 100%;
            min-height: 45px;
            padding: 11px 13px;
            border: 1px solid var(--restaurant-detail-border);
            border-radius: 12px;
            outline: none;
            background: var(--restaurant-detail-soft);
            color: var(--restaurant-detail-text);
            font-family: inherit;
            font-size: 12px;
            transition: 0.2s ease;
        }

        textarea.restaurant-detail-input {
            min-height: 95px;
            resize: vertical;
        }

        .restaurant-detail-input:focus {
            border-color: #18b9c7;
            background: var(--restaurant-detail-surface);
            box-shadow: 0 0 0 4px rgba(24, 185, 199, 0.12);
        }

        .restaurant-detail-input::placeholder {
            color: #9ba8b3;
        }

        [data-theme="dark"] .restaurant-detail-page {
            --restaurant-detail-text: #edf5fa;
            --restaurant-detail-muted: #9daab6;
            --restaurant-detail-surface: #20272e;
            --restaurant-detail-soft: #272f37;
            --restaurant-detail-border: #35414b;
        }

        [data-theme="dark"] .restaurant-detail-alcohol {
            background: rgba(180, 68, 21, 0.17);
            color: #f2a57f;
        }

        [data-theme="dark"] .restaurant-detail-price-card {
            background:
                linear-gradient(135deg,
                    rgba(7, 89, 133, 0.18),
                    rgba(24, 185, 199, 0.10));
        }

        @media (max-width: 850px) {
            .restaurant-detail-panel {
                grid-template-columns: 1fr;
            }

            .restaurant-detail-visual {
                min-height: 390px;
            }
        }

        @media (max-width: 575px) {
            .restaurant-detail-page {
                padding-top: 14px;
            }

            .restaurant-detail-panel {
                border-radius: 22px;
            }

            .restaurant-detail-visual {
                min-height: 290px;
            }

            .restaurant-detail-content {
                padding: 21px;
            }

            .restaurant-detail-info-grid {
                grid-template-columns: 1fr;
            }

            .restaurant-detail-price-card {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 380px) {
            .restaurant-detail-content {
                padding: 18px;
            }

            .restaurant-detail-visual {
                min-height: 245px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-detail-page">
            <article class="restaurant-detail-panel">

                <div class="restaurant-detail-visual">
                    @if ($menuItem->image)
                        <div class="restaurant-detail-photo" style="background-image: url('{{ asset($menuItem->image) }}');">
                        </div>
                    @else
                        <div class="restaurant-detail-photo-empty">
                            ♨
                        </div>
                    @endif

                    <div class="restaurant-detail-photo-overlay"></div>

                    <span class="restaurant-detail-visual-label">
                        Signature Selection
                    </span>

                    <span class="restaurant-detail-visual-caption">
                        Prepared and served by our hotel restaurant team
                    </span>
                </div>

                <div class="restaurant-detail-content">
                    <div class="restaurant-detail-tag">
                        <span>{{ $menuItem->category->name }}</span>

                        @if ($menuItem->is_alcoholic)
                            <span class="restaurant-detail-tag-dot"></span>

                            <span class="restaurant-detail-alcohol">
                                Contains alcohol
                            </span>
                        @endif
                    </div>

                    <h1 class="restaurant-detail-name">
                        {{ $menuItem->name }}
                    </h1>

                    <p class="restaurant-detail-description">
                        {{ $menuItem->description ?: 'A carefully prepared selection using premium ingredients and our signature hotel presentation.' }}
                    </p>

                    <div class="restaurant-detail-info-grid">
                        <div class="restaurant-detail-info">
                            <span class="restaurant-detail-info-label">
                                Category
                            </span>

                            <span class="restaurant-detail-info-value">
                                {{ $menuItem->category->name }}
                            </span>
                        </div>

                        <div class="restaurant-detail-info">
                            <span class="restaurant-detail-info-label">
                                Service
                            </span>

                            <span class="restaurant-detail-info-value">
                                Freshly prepared
                            </span>
                        </div>
                    </div>

                    <div class="restaurant-detail-price-card">
                        <div>
                            <span class="restaurant-detail-price-label">
                                Menu price
                            </span>

                            <div class="restaurant-detail-price">
                                Rp {{ number_format($menuItem->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <span class="restaurant-detail-available">
                            Available
                        </span>
                    </div>

                    <form class="restaurant-detail-form" action="{{ route('cart.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="item_type" value="single">

                        <input type="hidden" name="reference_id" value="{{ $menuItem->id }}">

                        <div class="restaurant-detail-field">
                            <label class="restaurant-detail-field-label" for="restaurant-detail-quantity">
                                Quantity
                            </label>

                            <input id="restaurant-detail-quantity" class="restaurant-detail-input" type="number"
                                name="quantity" min="1" max="50" value="1" required>
                        </div>

                        <div class="restaurant-detail-field">
                            <label class="restaurant-detail-field-label" for="restaurant-detail-note">
                                Special request
                            </label>

                            <textarea id="restaurant-detail-note" class="restaurant-detail-input" name="note" maxlength="500"
                                placeholder="Example: less spicy, no peanuts, or other dietary requests"></textarea>
                        </div>

                        <button class="hotel-btn hotel-btn-add hotel-btn-w-100 restaurant-detail-submit" type="submit">
                            <span class="restaurant-detail-submit-text">
                                Add to cart
                            </span>
                        </button>

                        <a class="restaurant-package-back-button" href="{{ route('menus.index') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m15 18-6-6 6-6"></path>
                                <path d="M9 12h10"></path>
                            </svg>

                            Back to menu
                        </a>
                    </form>
                </div>
            </article>
        </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('.restaurant-detail-form');

            if (!form) {
                return;
            }

            form.addEventListener('submit', function() {
                var button = form.querySelector(
                    '.restaurant-detail-submit'
                );

                var text = form.querySelector(
                    '.restaurant-detail-submit-text'
                );

                if (!button) {
                    return;
                }

                button.disabled = true;

                if (text) {
                    text.textContent = 'Adding to cart...';
                }
            });
        });
    </script>
@endpush
