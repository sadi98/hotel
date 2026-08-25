<div class="offcanvas offcanvas-start suha-offcanvas-wrap" tabindex="-1" id="suhaOffcanvas"
    aria-labelledby="suhaOffcanvasLabel">
    <!-- Close button-->
    <button class="btn-close btn-close-white text-reset" type="button" data-bs-dismiss="offcanvas"
        aria-label="Close"></button>
    <!-- Offcanvas body-->
    <div class="offcanvas-body">
        @guest
            <!-- Sidenav Profile-->
            <div class="sidenav-profile">
                <div class="user-profile"><img src="{{ asset('users/img/bg-img/9.jpg') }}" alt=""></div>
                <div class="user-info">
                    <h6 class="user-name mb-1">Guest</h6>
                </div>
            </div>
            <!-- Sidenav Nav-->
            <ul class="sidenav-nav ps-0">
                @if (Route::has('login'))
                    <li><a href="{{ route('login') }}"><i class="lni lni-enter"></i> Sign in</a></li>
                @endif

                @if (Route::has('register'))
                    <li><a href="{{ route('register') }}"><i class="lni lni-circle-plus"></i> Sign up</a></li>
                @endif
            </ul>
        @else
            <!-- Sidenav Profile-->
            <div class="sidenav-profile">
                <div class="user-profile">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/user/profile/' . Auth::user()->avatar) }}" alt="">
                    @else
                        <img src="{{ asset('users/img/bg-img/9.jpg') }}" alt="">
                    @endif
                </div>
                <div class="user-info">
                    <h6 class="user-name mb-1">{{ Auth::user()->name }}</h6>
                    <p class="available-balance">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <!-- Sidenav Nav-->
            <ul class="sidenav-nav ps-0">
                <li><a href="{{ route('profile') }}"><i class="lni lni-user"></i>My Profile</a></li>
                <li><a href="{{ route('setting') }}"><i class="lni lni-cog"></i>Settings</a></li>
                <li><a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="lni lni-power-switch"></i>Sign Out</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </ul>
        @endguest
    </div>
</div>
