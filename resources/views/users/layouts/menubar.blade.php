<div class="footer-nav-area" id="footerNav">
    <div class="container h-100 px-0">
        <div class="suha-footer-nav h-100">
            <ul class="h-100 d-flex align-items-center justify-content-between ps-0">

                {{-- Home --}}
                <li class="{{ request()->is('/') ? 'active' : '' }}">
                    <a href="{{ url('/') }}">
                        <i class="lni lni-home"></i>
                        Home
                    </a>
                </li>

                {{-- Support --}}
                <li class="{{ request()->is('support*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="lni lni-life-ring"></i>
                        Support
                    </a>
                </li>

                {{-- Cart --}}
                <li class="{{ request()->is('cart*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="lni lni-shopping-basket"></i>
                        Cart
                    </a>
                </li>

                {{-- Pages --}}
                <li class="{{ request()->is('pages*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="lni lni-heart"></i>
                        Pages
                    </a>
                </li>

                {{-- Settings --}}
                <li class="{{ request()->is('setting*') ? 'active' : '' }}">
                    <a href="{{ route('setting') }}">
                        <i class="lni lni-cog"></i>
                        Settings
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
