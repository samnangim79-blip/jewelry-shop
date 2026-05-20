<!doctype html>
<html lang="{{ app()->getLocale() }}" class="minimal-theme" data-locale="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/backend') }}/assets/images/favicon-32x32.png" type="image/png" />
    <link href="{{ asset('assets/backend') }}/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/style.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/backend/assets/plugins/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('assets/backend') }}/assets/css/dark-theme.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/light-theme.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <title>{{ __('messages.login') }} - Jewelry Shop</title>
</head>

<body class="bg-light">
    <div class="wrapper">
        <div class="section-authentication-cover">
            <div>
                <div class="row g-0">
                    <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">
                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                            <img src="{{ asset('assets/backend') }}/assets/images/login-images/login-cover.svg" class="img-fluid auth-img-cover-login" width="650" alt="login illustration" onerror="this.style.display='none'" />
                        </div>
                    </div>
                    <div class="col-12 col-xl-5 col-xxl-4 d-flex align-items-center justify-content-center">
                        <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                            <div class="card-body p-sm-5">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/backend') }}/assets/js/jquery.min.js"></script>
    <script src="{{ asset('assets/backend') }}/assets/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    @flasher_render
</body>

</html>
