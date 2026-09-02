@php
    $headerIcon = trim($__env->yieldContent('header_icon', 'activity'));
@endphp

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">

            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">

                    <h1 class="page-header-title">
                        <div class="page-header-icon">
                            <i data-feather="{{ $headerIcon }}"></i>
                        </div>

                        @yield('header_title', 'Dashboard')
                    </h1>

                    <div class="page-header-subtitle">
                        @yield('header_subtitle', 'Example dashboard overview and content summary')
                    </div>

                </div>

                {{-- Tombol atau konten tambahan di sebelah kanan header --}}
                @hasSection('header_action')
                    <div class="col-12 col-xl-auto mt-4">
                        @yield('header_action')
                    </div>
                @endif
            </div>

            {{-- Breadcrumb hanya muncul jika halaman mengisinya --}}
            @hasSection('breadcrumbs')
                <nav class="mt-4 rounded" aria-label="breadcrumb">

                    <ol class="breadcrumb px-3 py-2 rounded mb-0">
                        @yield('breadcrumbs')
                    </ol>
                </nav>
            @endif

        </div>
    </div>
</header>
