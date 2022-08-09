<!DOCTYPE html>

<html lang="en">

<head>

   <title>

      @yield('title',config('app.name', 'BheshBhusa'))

    </title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <meta name="description" content=""/>

    <meta name="keywords" content=""/>

    <meta name="author" content="BheshBhusa">

    <!-- Favicon -->

    <link rel="icon" href="{{ asset('assets/front/images/favicon.png') }}" type="image/png">

    <link rel="apple-touch-icon" href="{{ asset('assets/front/apple-touch-icon.png') }}">

    <link rel="shortcut icon" href="{{ asset('assets/front/images/favicon.png') }}" type="image/x-icon">

    <!-- font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="{{ asset('assets/front/css/bootstrap.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

    <!-- Jquery UI Css Slider -->

    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">

    <!-- slick Slider -->

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">

    <!-- Custom CSS -->

    <link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">

    <!-- Responsive CSS -->

    <link href="{{ asset('assets/front/css/responsive.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/izitoast/css/iziToast.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/sweetalert/sweetalert2.min.css') }}">


    @yield('head')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">

</head>

<body>

@include('front.layout.header')

<div class="main-wapper">

    @yield('section')

</div>

@include('front.layout.footer')

@yield('footer')

</body>

</html>

