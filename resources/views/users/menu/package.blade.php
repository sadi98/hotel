@extends('users.layouts.main')

@push('style')
@endpush

@section('content')
    <div class="container">
        <main class="restaurant-package-page">
            <div class="restaurant-package-layout">

                {{-- Package information --}}
                <article class="restaurant-package-panel">
                    <div class="restaurant-package-content">
                        <span class="restaurant-package-label">
                            Exclusive Dining Package
                        </span>

                        <h1 class="restaurant-package-name">
                            {{ $menuPackage->name }}
                        </h1>

                        <p class="restaurant-package-description">
                            {{ $menuPackage->description }}
                        </p>

                        <span class="restaurant-package-guests">
                            ◉ Designed for
                            {{ $menuPackage->serving_count }} guests
                        </span>

                        <div class="restaurant-package-price-card">
                            <div>
                                <span class="restaurant-package-price-label">
                                    Package price
                                </span>

                                <del class="restaurant-package-normal-price">
                                    Rp
                                    {{ number_format($menuPackage->normal_price, 0, ',', '.') }}
                                </del>

                                <h2 class="restaurant-package-saving">
                                    Rp
                                    {{ number_format($menuPackage->package_price, 0, ',', '.') }}
                                </h2>
                            </div>
                        </div>

                        <h2 class="restaurant-package-includes-title">
                            What's included
                        </h2>

                        <ul class="restaurant-package-list">
                            @foreach ($menuPackage->items as $component)
                                <li class="restaurant-package-list-item">
                                    <span>
                                        {{ $component->menuItem->name }}
                                    </span>

                                    <span class="restaurant-package-item-quantity">
                                        {{ $component->quantity }}×
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </article>

                {{-- Order form --}}
                <aside class="restaurant-package-order-card">
                    <span class="restaurant-package-order-label">
                        Add to your order
                    </span>

                    <h2 class="restaurant-package-order-title">
                        Order this package
                    </h2>

                    <p class="restaurant-package-order-description">
                        Set the required quantity and include a note for our
                        restaurant team if necessary.
                    </p>

                    <form class="restaurant-package-form" action="{{ route('cart.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="item_type" value="package">

                        <input type="hidden" name="reference_id" value="{{ $menuPackage->id }}">

                        <div class="restaurant-package-field">
                            <label class="restaurant-package-field-label" for="restaurant-package-quantity">
                                Package quantity
                            </label>

                            <input id="restaurant-package-quantity" class="restaurant-package-input" type="number"
                                name="quantity" min="{{ $menuPackage->minimum_order }}"
                                value="{{ $menuPackage->minimum_order }}" required>

                            <span class="restaurant-package-minimum-note">
                                Minimum order:
                                {{ $menuPackage->minimum_order }} package
                            </span>
                        </div>

                        <div class="restaurant-package-field">
                            <label class="restaurant-package-field-label" for="restaurant-package-note">
                                Special note
                            </label>

                            <textarea id="restaurant-package-note" class="restaurant-package-input" name="note" maxlength="500"
                                placeholder="Example: allergy information or serving request"></textarea>
                        </div>

                        <div class="restaurant-package-actions">
                            <button class="hotel-btn hotel-btn-add hotel-btn-w-100 restaurant-package-submit"
                                type="submit">
                                <span class="restaurant-package-submit-text">
                                    Add package to cart
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

                            <p class="restaurant-package-action-note">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 11v5"></path>
                                    <path d="M12 8h.01"></path>
                                </svg>

                                You can review your selected items in the cart
                                before checkout.
                            </p>
                        </div>
                    </form>
                </aside>
            </div>
        </main>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector(
                '.restaurant-package-form'
            );

            if (!form) {
                return;
            }

            form.addEventListener('submit', function() {
                var button = form.querySelector(
                    '.restaurant-package-submit'
                );

                var text = form.querySelector(
                    '.restaurant-package-submit-text'
                );

                if (!button) {
                    return;
                }

                button.disabled = true;

                if (text) {
                    text.textContent = 'Adding package...';
                }
            });
        });
    </script>
@endpush
