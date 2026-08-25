@extends('users.layouts.main')

@push('style')
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-home-page" id="restaurantHomePage">
            {{-- =====================================================
                 HERO
            ====================================================== --}}

            <section class="restaurant-home-hero">
                <img class="restaurant-home-hero-image" src="{{ asset('users/img/bg-img/1.jpg') }}"
                    alt="Luxury dining experience at {{ config('app.name') }}">

                <div class="restaurant-home-hero-overlay"></div>
                <div class="restaurant-home-hero-glow restaurant-home-hero-glow-one"></div>
                <div class="restaurant-home-hero-glow restaurant-home-hero-glow-two"></div>

                <div class="restaurant-home-hero-content">
                    <div class="restaurant-home-hero-badge">
                        <span class="restaurant-home-hero-badge-dot"></span>
                        Kayu Manis Restaurant · Ayaka Suites
                    </div>

                    <h1 class="restaurant-home-hero-title">
                        Asian warmth,
                        <span>Western elegance.</span>
                    </h1>

                    <p class="restaurant-home-hero-description">
                        Discover delightful Asian and Western cuisine, thoughtfully prepared
                        by our professional chefs for a memorable dining experience in the
                        heart of Jakarta.
                    </p>

                    <div class="restaurant-home-hero-actions">
                        <a class="hotel-btn hotel-btn-add hotel-btn-lg restaurant-home-hero-button"
                            href="{{ route('menus.index') }}">
                            <svg class="restaurant-home-button-icon" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M4 11h16"></path>
                                <path d="M12 3v3"></path>
                                <path d="M6 11a6 6 0 0 1 12 0"></path>
                                <path d="M3 17h18"></path>
                            </svg>

                            Explore menu
                        </a>

                        <a class="hotel-btn hotel-btn-lg restaurant-home-hero-secondary restaurant-home-hero-button"
                            href="{{ route('reservations.create') }}">
                            <svg class="restaurant-home-button-icon" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M3 11h18"></path>
                            </svg>

                            Reserve a table
                        </a>
                    </div>

                    <div class="restaurant-home-hero-information">
                        <span class="restaurant-home-hero-information-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 7v5l3 2"></path>
                            </svg>

                            Open daily, 06:30–23:00
                        </span>

                        <span class="restaurant-home-hero-information-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="2"></circle>
                            </svg>

                            Lobby Level · Signature Restaurant
                        </span>

                        <span class="restaurant-home-hero-information-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5"></path>
                            </svg>

                            Guest and public access
                        </span>
                    </div>
                </div>
            </section>

            {{-- =====================================================
                 QUICK ACCESS
            ====================================================== --}}

            <div class="restaurant-home-quick-wrapper">
                <nav class="restaurant-home-quick-grid" aria-label="Quick access">
                    <a class="restaurant-home-quick-item" href="{{ route('menus.index') }}">
                        <span class="restaurant-home-quick-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 11h16"></path>
                                <path d="M12 3v3"></path>
                                <path d="M6 11a6 6 0 0 1 12 0"></path>
                                <path d="M3 17h18"></path>
                            </svg>
                        </span>

                        <span class="restaurant-home-quick-content">
                            <strong class="restaurant-home-quick-title">Order menu</strong>
                            <span class="restaurant-home-quick-text">Discover all dishes</span>
                        </span>
                    </a>

                    <a class="restaurant-home-quick-item" href="{{ route('reservations.create') }}">
                        <span class="restaurant-home-quick-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M3 11h18"></path>
                            </svg>
                        </span>

                        <span class="restaurant-home-quick-content">
                            <strong class="restaurant-home-quick-title">Reservation</strong>
                            <span class="restaurant-home-quick-text">Reserve your table</span>
                        </span>
                    </a>

                    <a class="restaurant-home-quick-item" href="{{ route('cart.index') }}">
                        <span class="restaurant-home-quick-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="9" cy="20" r="1"></circle>
                                <circle cx="19" cy="20" r="1"></circle>
                                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.6L22 8H6"></path>
                            </svg>
                        </span>

                        <span class="restaurant-home-quick-content">
                            <strong class="restaurant-home-quick-title">Your cart</strong>
                            <span class="restaurant-home-quick-text">Review selected items</span>
                        </span>
                    </a>

                    <a class="restaurant-home-quick-item" href="{{ url('/camera') }}">
                        <span class="restaurant-home-quick-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                                <path d="M14 14h3v3h-3z"></path>
                                <path d="M18 18h3v3h-3z"></path>
                                <path d="M18 14h3"></path>
                                <path d="M14 18v3"></path>
                            </svg>
                        </span>

                        <span class="restaurant-home-quick-content">
                            <strong class="restaurant-home-quick-title">Scan QR</strong>
                            <span class="restaurant-home-quick-text">Open table menu</span>
                        </span>
                    </a>
                </nav>
            </div>

            {{-- =====================================================
                 MENU CATEGORIES
            ====================================================== --}}

            <section class="restaurant-home-section restaurant-home-reveal">
                <header class="restaurant-home-section-heading">
                    <div>
                        <span class="restaurant-home-section-kicker">
                            Explore our cuisine
                        </span>

                        <h2 class="restaurant-home-section-title">
                            What would you like today?
                        </h2>

                        <p class="restaurant-home-section-description">
                            From signature meals to handcrafted beverages, discover
                            something created for every dining moment.
                        </p>
                    </div>

                    <a class="restaurant-home-section-link" href="{{ route('menus.index') }}">
                        View all

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </a>
                </header>

                <div class="restaurant-home-category-grid">
                    <a class="restaurant-home-category" href="{{ route('menus.index') }}">
                        <span class="restaurant-home-category-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 11h16"></path>
                                <path d="M12 3v3"></path>
                                <path d="M6 11a6 6 0 0 1 12 0"></path>
                                <path d="M3 17h18"></path>
                            </svg>
                        </span>

                        <strong class="restaurant-home-category-title">All menu</strong>
                        <span class="restaurant-home-category-text">Complete collection</span>
                    </a>

                    <a class="restaurant-home-category" href="{{ route('menus.index', ['type' => 'food']) }}">
                        <span class="restaurant-home-category-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 11h18"></path>
                                <path d="M5 11a7 7 0 0 1 14 0"></path>
                                <path d="M12 4V2"></path>
                                <path d="M4 16h16"></path>
                                <path d="M6 20h12"></path>
                            </svg>
                        </span>

                        <strong class="restaurant-home-category-title">Food</strong>
                        <span class="restaurant-home-category-text">Signature cuisine</span>
                    </a>

                    <a class="restaurant-home-category" href="{{ route('menus.index', ['type' => 'beverage']) }}">
                        <span class="restaurant-home-category-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M8 2h8"></path>
                                <path d="M9 2v5l-3 4v9a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-9l-3-4V2"></path>
                                <path d="M6 13h12"></path>
                            </svg>
                        </span>

                        <strong class="restaurant-home-category-title">Beverages</strong>
                        <span class="restaurant-home-category-text">Refreshing creations</span>
                    </a>

                    <a class="restaurant-home-category" href="{{ route('menus.index', ['beverage_type' => 'coffee']) }}">
                        <span class="restaurant-home-category-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 8h12v7a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5Z"></path>
                                <path d="M16 10h2a3 3 0 0 1 0 6h-2"></path>
                                <path d="M8 4v1"></path>
                                <path d="M12 4v1"></path>
                            </svg>
                        </span>

                        <strong class="restaurant-home-category-title">Coffee</strong>
                        <span class="restaurant-home-category-text">Artisan roasted</span>
                    </a>

                    <a class="restaurant-home-category"
                        href="{{ route('menus.index', [
                            'beverage_type' => 'non_coffee',
                            'alcohol' => 'no',
                        ]) }}">
                        <span class="restaurant-home-category-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M8 3h8"></path>
                                <path d="M9 3 7 21h10L15 3"></path>
                                <path d="M8 11h8"></path>
                                <path d="m16 6 3-3"></path>
                            </svg>
                        </span>

                        <strong class="restaurant-home-category-title">Non Coffee</strong>
                        <span class="restaurant-home-category-text">Fresh and delightful</span>
                    </a>

                    <a class="restaurant-home-category" href="{{ route('menus.index', ['alcohol' => 'yes']) }}">
                        <span class="restaurant-home-category-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M8 2h8l-1 7a3 3 0 0 1-6 0Z"></path>
                                <path d="M12 12v7"></path>
                                <path d="M8 22h8"></path>
                                <path d="M9 6h6"></path>
                            </svg>
                        </span>

                        <strong class="restaurant-home-category-title">Alcohol</strong>
                        <span class="restaurant-home-category-text">Premium collection</span>
                    </a>
                </div>
            </section>

            {{-- =====================================================
                 CHEF RECOMMENDATIONS
            ====================================================== --}}

            <section class="restaurant-home-section restaurant-home-reveal">
                <header class="restaurant-home-section-heading">
                    <div>
                        <span class="restaurant-home-section-kicker">
                            Curated by our chef
                        </span>

                        <h2 class="restaurant-home-section-title">
                            Signature recommendations
                        </h2>

                        <p class="restaurant-home-section-description">
                            A curated selection inspired by refined techniques,
                            premium ingredients, and memorable flavours.
                        </p>
                    </div>

                    <a class="restaurant-home-section-link" href="{{ route('menus.index', ['type' => 'food']) }}">
                        Discover more

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </a>
                </header>

                <div class="restaurant-home-menu-grid">
                    <article class="restaurant-home-menu-card">
                        <a class="restaurant-home-menu-visual" href="{{ route('menus.index', ['type' => 'food']) }}">
                            <img class="restaurant-home-menu-image" src="{{ asset('users/img/restaurant/menu/5.png') }}"
                                alt="Wagyu tenderloin" loading="lazy">

                            <span class="restaurant-home-menu-badge">
                                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z"></path>
                                </svg>

                                Chef’s choice
                            </span>
                        </a>

                        <div class="restaurant-home-menu-body">
                            <div class="restaurant-home-menu-meta">
                                <span class="restaurant-home-menu-category">
                                    Main course
                                </span>

                                <span class="restaurant-home-menu-rating">
                                    <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z">
                                        </path>
                                    </svg>
                                    4.9
                                </span>
                            </div>

                            <h3 class="restaurant-home-menu-name">
                                Wagyu Tenderloin
                            </h3>

                            <p class="restaurant-home-menu-description">
                                Premium wagyu served with seasonal vegetables
                                and our signature sauce.
                            </p>

                            <div class="restaurant-home-menu-footer">
                                <span class="restaurant-home-menu-price">
                                    Premium selection
                                </span>

                                <a class="restaurant-home-menu-arrow"
                                    href="{{ route('menus.index', ['type' => 'food']) }}"
                                    aria-label="View Wagyu Tenderloin menu">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="restaurant-home-menu-card">
                        <a class="restaurant-home-menu-visual" href="{{ route('menus.index', ['type' => 'food']) }}">
                            <img class="restaurant-home-menu-image" src="{{ asset('users/img/restaurant/menu/8.png') }}"
                                alt="Lobster Thermidor" loading="lazy">

                            <span class="restaurant-home-menu-badge">
                                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z"></path>
                                </svg>

                                Signature
                            </span>
                        </a>

                        <div class="restaurant-home-menu-body">
                            <div class="restaurant-home-menu-meta">
                                <span class="restaurant-home-menu-category">
                                    Seafood
                                </span>

                                <span class="restaurant-home-menu-rating">
                                    <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z">
                                        </path>
                                    </svg>
                                    4.8
                                </span>
                            </div>

                            <h3 class="restaurant-home-menu-name">
                                Lobster Thermidor
                            </h3>

                            <p class="restaurant-home-menu-description">
                                Tender lobster finished with a rich, elegant
                                Thermidor sauce.
                            </p>

                            <div class="restaurant-home-menu-footer">
                                <span class="restaurant-home-menu-price">
                                    Ocean selection
                                </span>

                                <a class="restaurant-home-menu-arrow"
                                    href="{{ route('menus.index', ['type' => 'food']) }}" aria-label="View seafood menu">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="restaurant-home-menu-card">
                        <a class="restaurant-home-menu-visual" href="{{ route('menus.index', ['type' => 'food']) }}">
                            <img class="restaurant-home-menu-image" src="{{ asset('users/img/restaurant/menu/9.png') }}"
                                alt="Pan-seared salmon" loading="lazy">

                            <span class="restaurant-home-menu-badge">
                                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z"></path>
                                </svg>

                                Guest favourite
                            </span>
                        </a>

                        <div class="restaurant-home-menu-body">
                            <div class="restaurant-home-menu-meta">
                                <span class="restaurant-home-menu-category">
                                    Chef special
                                </span>

                                <span class="restaurant-home-menu-rating">
                                    <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z">
                                        </path>
                                    </svg>
                                    4.9
                                </span>
                            </div>

                            <h3 class="restaurant-home-menu-name">
                                Pan-Seared Salmon
                            </h3>

                            <p class="restaurant-home-menu-description">
                                Perfectly seared salmon paired with fresh herbs
                                and seasonal garnishes.
                            </p>

                            <div class="restaurant-home-menu-footer">
                                <span class="restaurant-home-menu-price">
                                    Freshly prepared
                                </span>

                                <a class="restaurant-home-menu-arrow"
                                    href="{{ route('menus.index', ['type' => 'food']) }}" aria-label="View salmon menu">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="restaurant-home-menu-card">
                        <a class="restaurant-home-menu-visual"
                            href="{{ route('menus.index', ['beverage_type' => 'coffee']) }}">
                            <img class="restaurant-home-menu-image" src="{{ asset('users/img/restaurant/menu/6.png') }}"
                                alt="Artisan beverage" loading="lazy">

                            <span class="restaurant-home-menu-badge">
                                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z"></path>
                                </svg>

                                Artisan
                            </span>
                        </a>

                        <div class="restaurant-home-menu-body">
                            <div class="restaurant-home-menu-meta">
                                <span class="restaurant-home-menu-category">
                                    Beverage
                                </span>

                                <span class="restaurant-home-menu-rating">
                                    <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z">
                                        </path>
                                    </svg>
                                    4.7
                                </span>
                            </div>

                            <h3 class="restaurant-home-menu-name">
                                Artisan Coffee
                            </h3>

                            <p class="restaurant-home-menu-description">
                                Carefully selected beans crafted into a balanced,
                                aromatic cup.
                            </p>

                            <div class="restaurant-home-menu-footer">
                                <span class="restaurant-home-menu-price">
                                    Barista crafted
                                </span>

                                <a class="restaurant-home-menu-arrow"
                                    href="{{ route('menus.index', ['beverage_type' => 'coffee']) }}"
                                    aria-label="View coffee menu">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            {{-- =====================================================
                 FIVE-STAR EXPERIENCE
            ====================================================== --}}

            <section class="restaurant-home-section restaurant-home-reveal">
                <div class="restaurant-home-experience">
                    <div class="restaurant-home-experience-visual">
                        <img class="restaurant-home-experience-image"
                            src="{{ asset('users/img/restaurant/fine-dining.jpg') }}"
                            alt="Five-star restaurant dining room" loading="lazy">
                    </div>

                    <div class="restaurant-home-experience-content">
                        <div class="restaurant-home-experience-inner">
                            <span class="restaurant-home-experience-kicker">
                                More than a meal
                            </span>

                            <h2 class="restaurant-home-experience-title">
                                A five-star dining experience
                            </h2>

                            <p class="restaurant-home-experience-description">
                                Every detail is thoughtfully prepared—from our
                                ingredients and table setting to the personal service
                                you receive throughout your dining experience.
                            </p>

                            <ul class="restaurant-home-experience-list">
                                <li class="restaurant-home-experience-item">
                                    <span class="restaurant-home-experience-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </span>

                                    Premium and carefully sourced ingredients
                                </li>

                                <li class="restaurant-home-experience-item">
                                    <span class="restaurant-home-experience-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </span>

                                    Personal service from our restaurant team
                                </li>

                                <li class="restaurant-home-experience-item">
                                    <span class="restaurant-home-experience-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </span>

                                    Elegant atmosphere for every special occasion
                                </li>
                            </ul>

                            <a class="hotel-btn hotel-btn-add hotel-btn-lg hotel-btn-mobile-full"
                                href="{{ route('reservations.create') }}">
                                <svg class="restaurant-home-button-icon" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                    <path d="M16 3v4"></path>
                                    <path d="M8 3v4"></path>
                                    <path d="M3 11h18"></path>
                                </svg>

                                Reserve your table
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- =====================================================
                 DINING PACKAGES
            ====================================================== --}}

            <section class="restaurant-home-section restaurant-home-reveal">
                <header class="restaurant-home-section-heading">
                    <div>
                        <span class="restaurant-home-section-kicker">
                            Curated experiences
                        </span>

                        <h2 class="restaurant-home-section-title">
                            Dining packages
                        </h2>

                        <p class="restaurant-home-section-description">
                            Thoughtfully arranged dining experiences for romantic
                            moments, family gatherings, and celebrations.
                        </p>
                    </div>

                    <a class="restaurant-home-section-link" href="{{ route('menus.index') }}">
                        View packages

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                    </a>
                </header>

                <div class="restaurant-home-package-grid">
                    <article class="restaurant-home-package">
                        <img class="restaurant-home-package-image" src="{{ asset('users/img/restaurant/menu/1.png') }}"
                            alt="Romantic dinner package" loading="lazy">

                        <div class="restaurant-home-package-overlay"></div>

                        <div class="restaurant-home-package-content">
                            <span class="restaurant-home-package-label">
                                For two
                            </span>

                            <h3 class="restaurant-home-package-title">
                                Romantic Dinner
                            </h3>

                            <p class="restaurant-home-package-text">
                                An intimate culinary experience designed for
                                an unforgettable evening together.
                            </p>

                            <div class="restaurant-home-package-footer">
                                <span class="restaurant-home-package-price">
                                    Curated for couples
                                </span>

                                <a class="restaurant-home-package-link" href="{{ route('menus.index') }}">
                                    Explore

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="restaurant-home-package">
                        <img class="restaurant-home-package-image" src="{{ asset('users/img/restaurant/menu/2.png') }}"
                            alt="Family dining package" loading="lazy">

                        <div class="restaurant-home-package-overlay"></div>

                        <div class="restaurant-home-package-content">
                            <span class="restaurant-home-package-label">
                                Family experience
                            </span>

                            <h3 class="restaurant-home-package-title">
                                Family Celebration
                            </h3>

                            <p class="restaurant-home-package-text">
                                A generous selection created for sharing memorable
                                moments with the people you love.
                            </p>

                            <div class="restaurant-home-package-footer">
                                <span class="restaurant-home-package-price">
                                    Perfect for sharing
                                </span>

                                <a class="restaurant-home-package-link" href="{{ route('menus.index') }}">
                                    Explore

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="restaurant-home-package">
                        <img class="restaurant-home-package-image" src="{{ asset('users/img/restaurant/menu/3.png') }}"
                            alt="Chef tasting dining package" loading="lazy">

                        <div class="restaurant-home-package-overlay"></div>

                        <div class="restaurant-home-package-content">
                            <span class="restaurant-home-package-label">
                                Chef’s journey
                            </span>

                            <h3 class="restaurant-home-package-title">
                                Tasting Experience
                            </h3>

                            <p class="restaurant-home-package-text">
                                Explore a sequence of signature courses selected
                                by our culinary team.
                            </p>

                            <div class="restaurant-home-package-footer">
                                <span class="restaurant-home-package-price">
                                    A culinary journey
                                </span>

                                <a class="restaurant-home-package-link" href="{{ route('menus.index') }}">
                                    Explore

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            {{-- =====================================================
                 SERVICE HIGHLIGHTS
            ====================================================== --}}

            <section class="restaurant-home-section restaurant-home-reveal">
                <header class="restaurant-home-section-heading">
                    <div>
                        <span class="restaurant-home-section-kicker">
                            Our promise
                        </span>

                        <h2 class="restaurant-home-section-title">
                            Crafted around your experience
                        </h2>
                    </div>
                </header>

                <div class="restaurant-home-service-grid">
                    <article class="restaurant-home-service">
                        <span class="restaurant-home-service-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m12 2 3.1 6.3L22 9.3l-5 4.9 1.2 6.8-6.2-3.2L5.8 21 7 14.2 2 9.3l6.9-1Z"></path>
                            </svg>
                        </span>

                        <h3 class="restaurant-home-service-title">
                            Premium quality
                        </h3>

                        <p class="restaurant-home-service-text">
                            Carefully selected ingredients prepared to
                            five-star hospitality standards.
                        </p>
                    </article>

                    <article class="restaurant-home-service">
                        <span class="restaurant-home-service-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="8" r="4"></circle>
                                <path d="M4 21a8 8 0 0 1 16 0"></path>
                                <path d="m17 11 2 2 4-4"></path>
                            </svg>
                        </span>

                        <h3 class="restaurant-home-service-title">
                            Personal service
                        </h3>

                        <p class="restaurant-home-service-text">
                            Thoughtful assistance from reservation through
                            the end of your dining experience.
                        </p>
                    </article>

                    <article class="restaurant-home-service">
                        <span class="restaurant-home-service-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M3 11h18"></path>
                            </svg>
                        </span>

                        <h3 class="restaurant-home-service-title">
                            Easy reservation
                        </h3>

                        <p class="restaurant-home-service-text">
                            Reserve an available table quickly as a guest
                            or registered user.
                        </p>
                    </article>

                    <article class="restaurant-home-service">
                        <span class="restaurant-home-service-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="2"></circle>
                            </svg>
                        </span>

                        <h3 class="restaurant-home-service-title">
                            Elegant atmosphere
                        </h3>

                        <p class="restaurant-home-service-text">
                            A refined setting designed for everyday dining
                            and meaningful celebrations.
                        </p>
                    </article>
                </div>
            </section>

            {{-- =====================================================
                 FINAL CALL TO ACTION
            ====================================================== --}}

            <section class="restaurant-home-section restaurant-home-reveal">
                <div class="restaurant-home-cta">
                    <div class="restaurant-home-cta-content">
                        <span class="restaurant-home-cta-kicker">
                            Your table awaits
                        </span>

                        <h2 class="restaurant-home-cta-title">
                            Make your next dining moment exceptional
                        </h2>

                        <p class="restaurant-home-cta-text">
                            Reserve a table for an elegant restaurant experience,
                            or explore our menu and order your favourite dishes now.
                        </p>
                    </div>

                    <div class="restaurant-home-cta-actions">
                        <a class="hotel-btn hotel-btn-add hotel-btn-lg" href="{{ route('reservations.create') }}">
                            <svg class="restaurant-home-button-icon" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M3 11h18"></path>
                            </svg>

                            Reserve now
                        </a>

                        <a class="hotel-btn hotel-btn-lg restaurant-home-cta-secondary"
                            href="{{ route('menus.index') }}">
                            View menu
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const homePage = document.getElementById('restaurantHomePage');
            const revealElements = document.querySelectorAll(
                '.restaurant-home-reveal'
            );

            if (!homePage || !revealElements.length) {
                return;
            }

            if (
                window.matchMedia &&
                window.matchMedia('(prefers-reduced-motion: reduce)').matches
            ) {
                revealElements.forEach(function(element) {
                    element.classList.add('is-visible');
                });

                return;
            }

            homePage.classList.add('is-animation-ready');

            if (!('IntersectionObserver' in window)) {
                revealElements.forEach(function(element) {
                    element.classList.add('is-visible');
                });

                return;
            }

            const revealObserver = new IntersectionObserver(
                function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.12,
                    rootMargin: '0px 0px -35px 0px'
                }
            );

            revealElements.forEach(function(element) {
                revealObserver.observe(element);
            });
        });
    </script>
@endpush
