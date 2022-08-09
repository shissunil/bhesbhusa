<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>

        @yield('title',config('app.name', 'Laravel'))

    </title>

    <link rel="apple-touch-icon" href="{{ asset('assets/admin/app-assets/images/logo/logo.png') }}">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/admin/app-assets/images/logo/logo.png') }}">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/vendors/css/vendors.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/vendors/css/charts/apexcharts.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/bootstrap.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/bootstrap-extended.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/colors.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/components.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/themes/dark-layout.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/themes/semi-dark-layout.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/core/colors/palette-gradient.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/pages/dashboard-ecommerce.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/pages/card-analytics.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/assets/css/style.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/assets/izitoast/css/iziToast.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/vendors/css/forms/select/select2.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/assets/css/custom.css') }}">

    @yield('head')

    <style type="text/css">
        .custom-select-sm{
            padding-right: 18px !important;
        }
        table span {
            display:none; 
        }
    </style>
</head>





