<div class="footer-nav-area" id="footerNav">
    <div class="container h-100 px-0">
        <div class="suha-footer-nav h-100">
            <ul class="h-100 d-flex align-items-center justify-content-between ps-0">

                {{-- Home --}}
                <li class="{{ request()->is('/') ? 'active' : '' }}">
                    <a href="{{ url('/') }}">
                        <i class="lni lni-home"></i>
                        <span>Home</span>
                    </a>
                </li>

                {{-- Menu --}}
                <li class="{{ request()->is('menus*') || request()->is('packages*') ? 'active' : '' }}">
                    <a href="{{ route('menus.index') }}">
                        <i class="lni lni-dinner"></i>
                        <span>Menu</span>
                    </a>
                </li>

                {{-- Camera / QR Scanner --}}
                <li
                    class="footer-camera-item {{ request()->is('camera*') || request()->is('camera*') ? 'active' : '' }}">
                    <a href="{{ route('camera') }}">
                        <span class="footer-camera-circle">
                            <i class="lni lni-camera"></i>
                        </span>

                        <span class="footer-camera-label">
                            Scan QR
                        </span>
                    </a>
                </li>

                {{-- Reservation --}}
                <li class="{{ request()->is('reservations*') ? 'active' : '' }}">
                    <a href="{{ route('reservations.create') }}">
                        <i class="lni lni-calendar"></i>
                        <span>Reservation</span>
                    </a>
                </li>

                {{-- Orders --}}
                <li class="{{ request()->is('orders*') || request()->is('cart*') ? 'active' : '' }}">
                    <a
                        href="{{ auth()->check() && auth()->user()->isUser() ? route('orders.history') : route('cart.index') }}">
                        <i class="lni lni-shopping-basket"></i>
                        <span>Orders</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
