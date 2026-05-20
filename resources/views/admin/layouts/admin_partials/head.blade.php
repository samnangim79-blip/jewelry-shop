<!doctype html>
<html lang="{{ app()->getLocale() }}" class="minimal-theme" data-locale="{{ app()->getLocale() }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/backend') }}/assets/images/favicon-32x32.png" type="image/png" />

    <!--plugins-->
    <link href="{{ asset('assets/backend') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/backend') }}/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/style.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/backend/assets/plugins/bootstrap-icons/font/bootstrap-icons.css') }}">

    <!-- loader -->
    <link href="{{ asset('assets/backend') }}/assets/css/pace.min.css" rel="stylesheet" />

    <!-- Theme Styles -->
    <link href="{{ asset('assets/backend') }}/assets/css/dark-theme.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/light-theme.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/semi-dark.css" rel="stylesheet" />
    <link href="{{ asset('assets/backend') }}/assets/css/header-colors.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <title>@yield('pageTitle', __('messages.dashboard')) - Jewelry Shop</title>

    @stack('styles')
</head>
