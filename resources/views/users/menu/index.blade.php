@extends('users.layouts.main')

@push('style')
    <style>
        .restaurant-menu-page {
            --restaurant-menu-primary: #087ea4;
            --restaurant-menu-secondary: #18b9c7;
            --restaurant-menu-accent: #6ee7f2;
            --restaurant-menu-text: #172033;
            --restaurant-menu-muted: #748094;
            --restaurant-menu-surface: #ffffff;
            --restaurant-menu-soft: #f3f9fc;
            --restaurant-menu-border: #deebf0;
            --restaurant-menu-shadow: 0 14px 35px rgba(20, 76, 104, 0.10);

            padding: 20px 0 90px;
            color: var(--restaurant-menu-text);
        }

        .restaurant-menu-alert {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 16px;
            padding: 13px 15px;
            border: 1px solid rgba(20, 160, 98, 0.18);
            border-radius: 15px;
            background: #ecfbf3;
            color: #12643e;
            font-size: 12px;
            line-height: 1.5;
        }

        .restaurant-menu-alert-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            border-radius: 50%;
            background: #18a568;
            color: #ffffff;
            font-size: 15px;
            font-weight: 800;
        }

        /* =====================================================
               HERO
            ====================================================== */

        .restaurant-menu-hero {
            position: relative;
            min-height: 235px;
            margin-bottom: 18px;
            padding: 32px;
            overflow: hidden;
            border-radius: 27px;
            background:
                radial-gradient(circle at 88% 15%,
                    rgba(111, 231, 242, 0.42),
                    transparent 28%),
                radial-gradient(circle at 75% 100%,
                    rgba(59, 130, 246, 0.34),
                    transparent 36%),
                linear-gradient(135deg,
                    #071a32 0%,
                    #075985 50%,
                    #0ea5b7 100%);
            color: #ffffff;
            box-shadow: 0 24px 48px rgba(8, 126, 164, 0.22);
        }

        .restaurant-menu-hero::before {
            position: absolute;
            top: -90px;
            right: -65px;
            width: 220px;
            height: 220px;
            border: 32px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            content: "";
        }

        .restaurant-menu-hero-content {
            position: relative;
            z-index: 2;
            max-width: 610px;
        }

        .restaurant-menu-hero-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .restaurant-menu-hero-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #67e8f9;
            box-shadow: 0 0 12px #67e8f9;
        }

        .restaurant-menu-title {
            margin: 0 0 11px;
            color: #ffffff;
            font-size: clamp(30px, 5vw, 46px);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: -1.2px;
        }

        .restaurant-menu-hero-description {
            max-width: 560px;
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 13px;
            line-height: 1.75;
        }

        .restaurant-menu-hero-features {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 22px;
        }

        .restaurant-menu-hero-feature {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.10);
            color: rgba(255, 255, 255, 0.91);
            font-size: 10px;
            font-weight: 700;
        }

        /* =====================================================
               FILTER
            ====================================================== */

        .restaurant-menu-filter-wrapper {
            position: sticky;
            top: 8px;
            z-index: 20;
            margin-bottom: 24px;
            padding: 8px;
            border: 1px solid var(--restaurant-menu-border);
            border-radius: 17px;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 10px 28px rgba(20, 76, 104, 0.08);
            backdrop-filter: blur(16px);
        }

        .restaurant-menu-filter {
            display: flex;
            gap: 7px;
            margin: 0;
            padding: 0;
            overflow-x: auto;
            list-style: none;
            scrollbar-width: none;
        }

        .restaurant-menu-filter::-webkit-scrollbar {
            display: none;
        }

        .restaurant-menu-filter-item {
            flex: 0 0 auto;
        }

        .restaurant-menu-filter-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 39px;
            padding: 9px 14px;
            border: 1px solid transparent;
            border-radius: 11px;
            color: #5f6f80;
            font-size: 11px;
            font-weight: 750;
            text-decoration: none;
            white-space: nowrap;
            transition: 0.2s ease;
        }

        .restaurant-menu-filter-link:hover {
            background: #edf8fb;
            color: #067c9f;
            text-decoration: none;
        }

        .restaurant-menu-filter-link.is-active {
            border-color: rgba(103, 232, 249, 0.45);
            background: linear-gradient(135deg, #075985, #0ea5b7);
            color: #ffffff;
            box-shadow: 0 7px 17px rgba(8, 126, 164, 0.24);
        }

        /* =====================================================
               SECTION HEADING
            ====================================================== */

        .restaurant-menu-section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 14px;
        }

        .restaurant-menu-section-label {
            display: block;
            margin-bottom: 4px;
            color: var(--restaurant-menu-primary);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .restaurant-menu-heading {
            margin: 0;
            color: var(--restaurant-menu-text);
            font-size: clamp(21px, 4vw, 27px);
            font-weight: 800;
        }

        .restaurant-menu-section-description {
            margin: 5px 0 0;
            color: var(--restaurant-menu-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        /* =====================================================
               MENU GRID
            ====================================================== */

        .restaurant-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .restaurant-menu-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--restaurant-menu-border);
            border-radius: 21px;
            background: var(--restaurant-menu-surface);
            box-shadow: var(--restaurant-menu-shadow);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .restaurant-menu-card:hover {
            border-color: rgba(14, 165, 183, 0.30);
            transform: translateY(-5px);
            box-shadow: 0 21px 42px rgba(20, 76, 104, 0.16);
        }

        .restaurant-menu-photo-wrapper {
            position: relative;
            height: 185px;
            overflow: hidden;
            background: linear-gradient(145deg, #d8f2f7, #348ca5);
        }

        .restaurant-menu-photo {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.4s ease;
        }

        .restaurant-menu-card:hover .restaurant-menu-photo {
            transform: scale(1.05);
        }

        .restaurant-menu-photo-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: rgba(255, 255, 255, 0.9);
            font-size: 40px;
        }

        .restaurant-menu-category-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            max-width: calc(100% - 24px);
            padding: 7px 10px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            background: rgba(5, 39, 60, 0.76);
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.7px;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
            backdrop-filter: blur(9px);
        }

        .restaurant-menu-alcohol-badge {
            position: absolute;
            right: 12px;
            bottom: 12px;
            padding: 6px 9px;
            border-radius: 9px;
            background: rgba(159, 65, 26, 0.89);
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
        }

        .restaurant-menu-body {
            display: flex;
            min-width: 0;
            min-height: 190px;
            flex-direction: column;
            padding: 16px;
        }

        .restaurant-menu-name {
            display: -webkit-box;
            min-height: 42px;
            margin: 0 0 6px;
            overflow: hidden;
            color: var(--restaurant-menu-text);
            font-size: 16px;
            font-weight: 800;
            line-height: 1.35;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .restaurant-menu-description {
            display: -webkit-box;
            min-height: 35px;
            margin: 0 0 12px;
            overflow: hidden;
            color: var(--restaurant-menu-muted);
            font-size: 10px;
            line-height: 1.65;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .restaurant-menu-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: auto;
            margin-bottom: 13px;
        }

        .restaurant-menu-price-label {
            display: block;
            margin-bottom: 2px;
            color: var(--restaurant-menu-muted);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .restaurant-menu-price {
            color: var(--restaurant-menu-primary);
            font-size: 17px;
            font-weight: 900;
        }

        .restaurant-menu-available {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #13805b;
            font-size: 9px;
            font-weight: 800;
        }

        .restaurant-menu-available::before {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #15b878;
            content: "";
            box-shadow: 0 0 0 4px rgba(21, 184, 120, 0.11);
        }

        .restaurant-menu-actions {
            display: grid;
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
            gap: 8px;
        }

        .restaurant-menu-actions form {
            display: flex;
            min-width: 0;
            margin: 0;
        }

        .restaurant-menu-actions .hotel-btn {
            width: 100%;
            min-width: 0;
        }

        /* =====================================================
               EMPTY STATE
            ====================================================== */

        .restaurant-menu-empty {
            grid-column: 1 / -1;
            padding: 42px 18px;
            border: 1px dashed rgba(8, 126, 164, 0.30);
            border-radius: 21px;
            background: rgba(240, 249, 252, 0.82);
            text-align: center;
        }

        .restaurant-menu-empty-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 58px;
            margin-bottom: 13px;
            border-radius: 18px;
            background: linear-gradient(135deg, #075985, #18b9c7);
            color: #ffffff;
            font-size: 27px;
        }

        .restaurant-menu-empty-title {
            margin: 0 0 6px;
            color: var(--restaurant-menu-text);
            font-size: 17px;
            font-weight: 800;
        }

        .restaurant-menu-empty-description {
            margin: 0;
            color: var(--restaurant-menu-muted);
            font-size: 11px;
        }

        /* =====================================================
               PAGINATION
            ====================================================== */

        .restaurant-menu-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin: 24px 0 35px;
        }

        .restaurant-menu-page-number {
            padding: 9px 12px;
            border: 1px solid var(--restaurant-menu-border);
            border-radius: 11px;
            background: var(--restaurant-menu-surface);
            color: var(--restaurant-menu-muted);
            font-size: 10px;
            font-weight: 800;
        }

        /* =====================================================
               DINING PACKAGES
            ====================================================== */

        .restaurant-menu-package-section {
            margin-top: 34px;
        }

        .restaurant-menu-package-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .restaurant-menu-package {
            position: relative;
            min-height: 235px;
            padding: 23px;
            overflow: hidden;
            border-radius: 22px;
            background:
                radial-gradient(circle at 100% 0,
                    rgba(103, 232, 249, 0.26),
                    transparent 33%),
                linear-gradient(140deg,
                    #071827,
                    #0a3c58 57%,
                    #087ea4);
            color: #ffffff;
            box-shadow: 0 18px 38px rgba(5, 53, 78, 0.20);
        }

        .restaurant-menu-package::after {
            position: absolute;
            right: -54px;
            bottom: -69px;
            width: 175px;
            height: 175px;
            border: 27px solid rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            content: "";
        }

        .restaurant-menu-package-content {
            position: relative;
            z-index: 2;
            display: flex;
            min-height: 189px;
            flex-direction: column;
        }

        .restaurant-menu-package-label {
            align-self: flex-start;
            margin-bottom: 14px;
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

        .restaurant-menu-package-name {
            margin: 0 0 8px;
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
        }

        .restaurant-menu-package-description {
            display: -webkit-box;
            margin: 0 0 16px;
            overflow: hidden;
            color: rgba(255, 255, 255, 0.68);
            font-size: 11px;
            line-height: 1.7;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }

        .restaurant-menu-package-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-top: auto;
        }

        .restaurant-menu-package-price {
            display: block;
            color: #ffffff;
            font-size: 18px;
            font-weight: 900;
        }

        .restaurant-menu-package-guests {
            display: block;
            margin-top: 3px;
            color: #a5f3fc;
            font-size: 9px;
            font-weight: 700;
        }

        .restaurant-menu-package-link {
            position: relative;
            z-index: 3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 12px;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.10);
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
            transition: 0.2s ease;
        }

        .restaurant-menu-package-link:hover {
            background: #ffffff;
            color: #075985;
            text-decoration: none;
        }

        /* =====================================================
               DISCOVER PACKAGES LINK
            ====================================================== */

        .restaurant-menu-package-header {
            align-items: flex-start;
        }

        .restaurant-menu-package-header-content {
            min-width: 0;
        }

        .restaurant-menu-package-discover {
            display: inline-flex;
            align-items: center;
            flex: 0 0 auto;
            gap: 5px;
            margin-top: 3px;
            padding: 6px 0;
            border-radius: 10px;
            color: var(--restaurant-menu-primary);
            font-size: 11px;
            font-weight: 800;
            line-height: 1.2;
            text-decoration: none;
            white-space: nowrap;
            transition:
                color 0.2s ease,
                transform 0.2s ease;
        }

        .restaurant-menu-package-discover:hover,
        .restaurant-menu-package-discover:focus {
            color: var(--restaurant-menu-secondary);
            text-decoration: none;
            transform: translateX(2px);
        }

        .restaurant-menu-package-discover:focus-visible {
            outline: 3px solid rgba(24, 185, 199, 0.22);
            outline-offset: 3px;
        }

        .restaurant-menu-package-discover svg {
            width: 15px;
            height: 15px;
            flex: 0 0 auto;
            transition: transform 0.2s ease;
        }

        .restaurant-menu-package-discover:hover svg {
            transform: translateX(3px);
        }

        .restaurant-menu-package-discover-mobile {
            display: none;
        }

        /* =====================================================
               RESPONSIVE
            ====================================================== */

        @media (max-width: 991px) {
            .restaurant-menu-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .restaurant-menu-page {
                padding-top: 14px;
            }

            .restaurant-menu-hero {
                min-height: auto;
                padding: 27px 22px;
                border-radius: 23px;
            }

            .restaurant-menu-filter-wrapper {
                top: 5px;
            }

            .restaurant-menu-package-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575px) {
            .restaurant-menu-package-header {
                align-items: flex-start;
                gap: 10px;
            }

            .restaurant-menu-package-discover {
                margin-top: 1px;
                padding-top: 4px;
                font-size: 10px;
            }

            .restaurant-menu-package-discover-desktop {
                display: none;
            }

            .restaurant-menu-package-discover-mobile {
                display: inline;
            }

            .restaurant-menu-grid {
                grid-template-columns: 1fr;
            }

            .restaurant-menu-card {
                display: grid;
                grid-template-columns: 120px minmax(0, 1fr);
            }

            .restaurant-menu-photo-wrapper {
                height: 100%;
                min-height: 220px;
            }

            .restaurant-menu-body {
                min-height: 220px;
                padding: 13px;
            }

            .restaurant-menu-name {
                min-height: auto;
                font-size: 14px;
            }

            .restaurant-menu-description {
                min-height: 32px;
                font-size: 9px;
            }

            .restaurant-menu-price-row {
                display: block;
            }

            .restaurant-menu-available {
                margin-top: 7px;
            }

            .restaurant-menu-actions {
                grid-template-columns: 1fr;
            }

            .restaurant-menu-actions .hotel-btn {
                min-height: 35px;
                padding: 8px 9px;
                font-size: 10px;
            }
        }

        @media (max-width: 390px) {
            .restaurant-menu-hero {
                padding: 24px 18px;
            }

            .restaurant-menu-hero-features {
                display: grid;
            }

            .restaurant-menu-card {
                display: block;
            }

            .restaurant-menu-photo-wrapper {
                height: 175px;
                min-height: 0;
            }

            .restaurant-menu-body {
                min-height: 0;
            }

            .restaurant-menu-actions {
                grid-template-columns: minmax(0, 0.85fr) minmax(0, 1.15fr);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-menu-page">
            @if (session('success'))
                <div class="restaurant-menu-alert" role="alert">
                    <span class="restaurant-menu-alert-icon">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- =====================================================
                 HERO
            ====================================================== --}}

            <section class="restaurant-menu-hero">
                <div class="restaurant-menu-hero-content">
                    <span class="restaurant-menu-hero-label">
                        <span class="restaurant-menu-hero-dot"></span>
                        Kayu Manis Restaurant · Ayaka Suites
                    </span>

                    <h1 class="restaurant-menu-title">
                        Signature Dining
                    </h1>

                    <p class="restaurant-menu-hero-description">
                        Discover chef-crafted cuisine, artisan coffee, refreshing
                        beverages, and exclusive packages for a memorable dining
                        experience.
                    </p>

                    <div class="restaurant-menu-hero-features">
                        <span class="restaurant-menu-hero-feature">
                            ✦ Chef-curated menu
                        </span>

                        <span class="restaurant-menu-hero-feature">
                            ◉ Premium ingredients
                        </span>

                        <span class="restaurant-menu-hero-feature">
                            ♢ Luxury hotel service
                        </span>
                    </div>
                </div>
            </section>

            {{-- =====================================================
                 FILTER
            ====================================================== --}}

            <nav class="restaurant-menu-filter-wrapper" aria-label="Menu filters">
                <ul class="restaurant-menu-filter">
                    <li class="restaurant-menu-filter-item">
                        <a class="restaurant-menu-filter-link {{ !request()->hasAny(['type', 'beverage_type', 'alcohol']) ? 'is-active' : '' }}"
                            href="{{ route('menus.index') }}">
                            All
                        </a>
                    </li>

                    <li class="restaurant-menu-filter-item">
                        <a class="restaurant-menu-filter-link {{ request('type') === 'food' ? 'is-active' : '' }}"
                            href="{{ route('menus.index', ['type' => 'food']) }}">
                            Food
                        </a>
                    </li>

                    <li class="restaurant-menu-filter-item">
                        <a class="restaurant-menu-filter-link {{ request('type') === 'beverage' ? 'is-active' : '' }}"
                            href="{{ route('menus.index', ['type' => 'beverage']) }}">
                            Beverages
                        </a>
                    </li>

                    <li class="restaurant-menu-filter-item">
                        <a class="restaurant-menu-filter-link {{ request('beverage_type') === 'coffee' ? 'is-active' : '' }}"
                            href="{{ route('menus.index', ['beverage_type' => 'coffee']) }}">
                            Coffee
                        </a>
                    </li>

                    <li class="restaurant-menu-filter-item">
                        <a class="restaurant-menu-filter-link {{ request('beverage_type') === 'non_coffee' && request('alcohol') === 'no' ? 'is-active' : '' }}"
                            href="{{ route('menus.index', [
                                'beverage_type' => 'non_coffee',
                                'alcohol' => 'no',
                            ]) }}">
                            Non Coffee
                        </a>
                    </li>

                    <li class="restaurant-menu-filter-item">
                        <a class="restaurant-menu-filter-link {{ request('alcohol') === 'yes' ? 'is-active' : '' }}"
                            href="{{ route('menus.index', ['alcohol' => 'yes']) }}">
                            Alcohol
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- =====================================================
                 À LA CARTE
            ====================================================== --}}

            <section>
                <div class="restaurant-menu-section-header">
                    <div>
                        <span class="restaurant-menu-section-label">
                            Explore the menu
                        </span>

                        <h2 class="restaurant-menu-heading">
                            À La Carte
                        </h2>

                        <p class="restaurant-menu-section-description">
                            Choose your preferred food or beverage.
                        </p>
                    </div>
                </div>

                <div class="restaurant-menu-grid">
                    @forelse ($items as $item)
                        <article class="restaurant-menu-card">
                            <div class="restaurant-menu-photo-wrapper">
                                @if ($item->image)
                                    <div class="restaurant-menu-photo"
                                        style="background-image: url('{{ asset($item->image) }}');"></div>
                                @else
                                    <div class="restaurant-menu-photo-empty">
                                        ♨
                                    </div>
                                @endif

                                <span class="restaurant-menu-category-badge">
                                    {{ $item->category->name }}
                                </span>

                                @if ($item->is_alcoholic)
                                    <span class="restaurant-menu-alcohol-badge">
                                        Alcohol
                                    </span>
                                @endif
                            </div>

                            <div class="restaurant-menu-body">
                                <h3 class="restaurant-menu-name">
                                    {{ $item->name }}
                                </h3>

                                <p class="restaurant-menu-description">
                                    {{ $item->description ?: 'Prepared with premium ingredients and our signature hotel presentation.' }}
                                </p>

                                <div class="restaurant-menu-price-row">
                                    <div>
                                        <span class="restaurant-menu-price-label">
                                            Price
                                        </span>

                                        <span class="restaurant-menu-price">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <span class="restaurant-menu-available">
                                        Available
                                    </span>
                                </div>

                                <div class="restaurant-menu-actions">
                                    <a class="hotel-btn hotel-btn-detail hotel-btn-sm"
                                        href="{{ route('menus.show', $item) }}">
                                        Details
                                    </a>

                                    <form class="restaurant-menu-add-form" action="{{ route('cart.store') }}"
                                        method="POST">
                                        @csrf

                                        <input type="hidden" name="item_type" value="single">

                                        <input type="hidden" name="reference_id" value="{{ $item->id }}">

                                        <input type="hidden" name="quantity" value="1">

                                        <button class="hotel-btn hotel-btn-add hotel-btn-sm restaurant-menu-add-button"
                                            type="submit">
                                            <span class="restaurant-menu-add-text">
                                                + Add
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="restaurant-menu-empty">
                            <span class="restaurant-menu-empty-icon">
                                ♨
                            </span>

                            <h3 class="restaurant-menu-empty-title">
                                No menu is available
                            </h3>

                            <p class="restaurant-menu-empty-description">
                                Please select another menu category.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if ($items->previousPageUrl() || $items->nextPageUrl())
                    <nav class="restaurant-menu-pagination" aria-label="Menu pagination">
                        @if ($items->previousPageUrl())
                            <a class="hotel-btn hotel-btn-neutral hotel-btn-sm" href="{{ $items->previousPageUrl() }}">
                                Previous
                            </a>
                        @endif

                        <span class="restaurant-menu-page-number">
                            Page {{ $items->currentPage() }}
                        </span>

                        @if ($items->nextPageUrl())
                            <a class="hotel-btn hotel-btn-create hotel-btn-sm" href="{{ $items->nextPageUrl() }}">
                                Next
                            </a>
                        @endif
                    </nav>
                @endif
            </section>

            {{-- =====================================================
                 DINING PACKAGES
            ====================================================== --}}

            @if ($packages->isNotEmpty())
                <section class="restaurant-menu-package-section">
                    <div class="restaurant-menu-section-header restaurant-menu-package-header">
                        <div class="restaurant-menu-package-header-content">
                            <span class="restaurant-menu-section-label">
                                Exclusive experiences
                            </span>

                            <h2 class="restaurant-menu-heading">
                                Dining Packages
                            </h2>

                            <p class="restaurant-menu-section-description">
                                Curated dining experiences for celebrations and
                                memorable occasions.
                            </p>
                        </div>

                        <a class="restaurant-menu-package-discover" href="#" aria-label="View all dining packages">
                            <span class="restaurant-menu-package-discover-desktop">
                                Discover more
                            </span>

                            <span class="restaurant-menu-package-discover-mobile">
                                View all
                            </span>

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="restaurant-menu-package-grid">
                        @foreach ($packages as $package)
                            <article class="restaurant-menu-package">
                                <div class="restaurant-menu-package-content">
                                    <span class="restaurant-menu-package-label">
                                        Exclusive Package
                                    </span>

                                    <h3 class="restaurant-menu-package-name">
                                        {{ $package->name }}
                                    </h3>

                                    <p class="restaurant-menu-package-description">
                                        {{ $package->description }}
                                    </p>

                                    <div class="restaurant-menu-package-footer">
                                        <div>
                                            <strong class="restaurant-menu-package-price">
                                                Rp {{ number_format($package->package_price, 0, ',', '.') }}
                                            </strong>

                                            <span class="restaurant-menu-package-guests">
                                                For {{ $package->serving_count }} guests
                                            </span>
                                        </div>

                                        <a class="restaurant-menu-package-link"
                                            href="{{ route('packages.show', $package) }}">
                                            View package →
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var forms = document.querySelectorAll(
                '.restaurant-menu-add-form'
            );

            forms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    var button = form.querySelector(
                        '.restaurant-menu-add-button'
                    );

                    var text = form.querySelector(
                        '.restaurant-menu-add-text'
                    );

                    if (!button) {
                        return;
                    }

                    button.disabled = true;

                    if (text) {
                        text.textContent = 'Adding...';
                    }
                });
            });
        });
    </script>
@endpush
