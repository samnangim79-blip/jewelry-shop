@include('admin.layouts.admin_partials.head')

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        @include('admin.layouts.admin_partials.header')
        <!--end top header-->

        <!--start sidebar -->
        @include('admin.layouts.admin_partials.left_sidebar')
        <!--end sidebar -->

        <!--start content-->
        <main class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@yield('pageBreadcrumbTitle', __('messages.dashboard'))</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" wire:navigate>
                                    <i class="bx bx-home-alt"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('pageBreadcrumb', __('messages.dashboard'))</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            {{ $slot ?? '' }}

        </main>
        <!--end page main-->

        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
    </div>
    <!--end wrapper-->

    @include('admin.layouts.admin_partials.scripts')

    @livewireScripts
    @flasher_render
</body>

</html>
