<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <div class="sidenav-menu-heading">
                    Core
                </div>

                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="nav-link-icon">
                        <i data-feather="activity"></i>
                    </div>

                    Dashboard
                </a>

                <div class="sidenav-menu-heading">
                    Restaurant
                </div>

                @php
                    $masterOpen = request()->routeIs(
                        'management.categories.*',
                        'management.menu-items.*',
                        'management.menu-packages.*',
                        'management.tables.*',
                    );

                    $operationOpen = request()->routeIs(
                        'management.orders.*',
                        'management.reservations.*',
                        'management.payments.*',
                    );

                    $customerOpen = request()->routeIs('management.customers.*', 'management.staff-accounts.*');
                @endphp

                <a class="nav-link {{ $masterOpen ? '' : 'collapsed' }}" href="javascript:void(0);"
                    data-bs-toggle="collapse" data-bs-target="#collapseMasterRestaurant"
                    aria-expanded="{{ $masterOpen ? 'true' : 'false' }}" aria-controls="collapseMasterRestaurant">
                    <div class="nav-link-icon">
                        <i data-feather="book-open"></i>
                    </div>

                    Master Data

                    <div class="sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>

                <div class="collapse {{ $masterOpen ? 'show' : '' }}" id="collapseMasterRestaurant"
                    data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('management.categories.*') ? 'active' : '' }}"
                            href="{{ route('management.categories.index') }}">
                            Kategori Menu
                        </a>

                        <a class="nav-link {{ request()->routeIs('management.menu-items.*') ? 'active' : '' }}"
                            href="{{ route('management.menu-items.index') }}">
                            Daftar Menu
                        </a>

                        <a class="nav-link {{ request()->routeIs('management.menu-packages.*') ? 'active' : '' }}"
                            href="{{ route('management.menu-packages.index') }}">
                            Paket Menu
                        </a>

                        <a class="nav-link {{ request()->routeIs('management.tables.*') ? 'active' : '' }}"
                            href="{{ route('management.tables.index') }}">
                            Meja Restoran
                        </a>
                    </nav>
                </div>

                <a class="nav-link {{ $operationOpen ? '' : 'collapsed' }}" href="javascript:void(0);"
                    data-bs-toggle="collapse" data-bs-target="#collapseRestaurantOperations"
                    aria-expanded="{{ $operationOpen ? 'true' : 'false' }}"
                    aria-controls="collapseRestaurantOperations">
                    <div class="nav-link-icon">
                        <i data-feather="shopping-bag"></i>
                    </div>

                    Operasional

                    <div class="sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>

                <div class="collapse {{ $operationOpen ? 'show' : '' }}" id="collapseRestaurantOperations"
                    data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('management.orders.*') ? 'active' : '' }}"
                            href="{{ route('management.orders.index') }}">
                            Pesanan
                        </a>

                        <a class="nav-link {{ request()->routeIs('management.reservations.*') ? 'active' : '' }}"
                            href="{{ route('management.reservations.index') }}">
                            Reservasi
                        </a>

                        <a class="nav-link {{ request()->routeIs('management.payments.*') ? 'active' : '' }}"
                            href="{{ route('management.payments.index') }}">
                            Pembayaran
                        </a>
                    </nav>
                </div>

                <a class="nav-link {{ request()->routeIs('management.restaurant-settings.*') ? 'active' : '' }}"
                    href="{{ route('management.restaurant-settings.edit') }}">
                    <div class="nav-link-icon">
                        <i data-feather="settings"></i>
                    </div>

                    Pengaturan Restoran
                </a>

                <div class="sidenav-menu-heading">
                    Management
                </div>

                <a class="nav-link {{ $customerOpen ? '' : 'collapsed' }}" href="javascript:void(0);"
                    data-bs-toggle="collapse" data-bs-target="#collapseUserManagement"
                    aria-expanded="{{ $customerOpen ? 'true' : 'false' }}" aria-controls="collapseUserManagement">
                    <div class="nav-link-icon">
                        <i data-feather="users"></i>
                    </div>

                    Pengguna

                    <div class="sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>

                <div class="collapse {{ $customerOpen ? 'show' : '' }}" id="collapseUserManagement"
                    data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('management.customers.*') ? 'active' : '' }}"
                            href="{{ route('management.customers.index') }}">
                            Data Customer
                        </a>

                        @if (Auth::user()->role === 'admin')
                            <a class="nav-link {{ request()->routeIs('management.staff-accounts.*') ? 'active' : '' }}"
                                href="{{ route('management.staff-accounts.index') }}">
                                Akun Staff
                            </a>
                        @endif
                    </nav>
                </div>

                <a class="nav-link {{ request()->routeIs('management.payment-webhooks.*') ? 'active' : '' }}"
                    href="{{ route('management.payment-webhooks.index') }}">
                    <div class="nav-link-icon">
                        <i data-feather="server"></i>
                    </div>

                    Log Webhook
                </a>

                <a class="nav-link {{ request()->routeIs('management.profile*') ? 'active' : '' }}"
                    href="{{ route('management.profile') }}">
                    <div class="nav-link-icon">
                        <i data-feather="user"></i>
                    </div>

                    Profil Saya
                </a>
            </div>
        </div>

        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">
                    Logged in as:
                </div>

                <div class="sidenav-footer-title">
                    {{ Auth::user()->name }}
                </div>
            </div>
        </div>
    </nav>
</div>
