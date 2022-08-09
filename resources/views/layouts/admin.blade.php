<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">

@include('layouts.admin-inc.head')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static " data-open="click"
    data-menu="vertical-menu-modern" data-col="2-columns">

    @include('layouts.admin-inc.header')
    @include('layouts.admin-inc.sidebar')

    <div class="app-content content">

        <div class="content-overlay"></div>

        <div class="header-navbar-shadow"></div>

        <div class="content-wrapper">                            
                @yield('content')
        </div>

    </div>

    @include('layouts.admin-inc.footer')
    
</body>

</html>