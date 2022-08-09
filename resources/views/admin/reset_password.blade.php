<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/admin/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/admin/app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/admin/app-assets/css/themes/semi-dark-layout.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/admin/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/admin/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css/pages/authentication.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/assets/izitoast/css/iziToast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/assets/css/custom.css') }}">
</head>

<body
    class="vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page"
    data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="row flexbox-container">
                    <div class="col-xl-8 col-11 d-flex justify-content-center">
                        <div class="card bg-authentication rounded-0 mb-0">
                            <div class="row m-0">
                                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                                    <img src="{{ asset('assets/admin/app-assets/images/pages/login.png') }}"
                                        alt="branding logo">
                                </div>
                                <div class="col-lg-6 col-12 p-0">
                                    <div class="card rounded-0 mb-0 px-2">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="mb-0">Reset Password</h4>
                                            </div>
                                        </div>

                                        <div class="card-content">
                                            <div class="card-body pt-1">

                                                <form method="POST" action="{{ route('admin.reset-password.submit') }}"
                                                    id="forgot_pwd_form">

                                                    @csrf

                                                    <fieldset class="form-label-group position-relative has-icon-left">
                                                        <input type="text"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            id="user-name" placeholder="Email address" name="email"
                                                            value="{{ $email }}" readonly />
                                                        <div class="form-control-position">
                                                            <i class="feather icon-mail"></i>
                                                        </div>
                                                        <label for="user-name">Email Address</label>
                                                    </fieldset>

                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror


                                                    <fieldset class="form-label-group position-relative has-icon-left">

                                                        <input type="password" name="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            id="password" placeholder="New Password...">

                                                        <div class="form-control-position">
                                                            <i class="feather icon-lock"></i>
                                                        </div>

                                                        <label for="password">Password</label>

                                                    </fieldset>

                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror

                                                    <fieldset class="form-label-group position-relative has-icon-left">
                                                       
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                                            id="password_confirmation"
                                                            placeholder="Confirm Password...">

                                                            <div class="form-control-position">
                                                                <i class="feather icon-lock"></i>
                                                            </div>
    
                                                            <label for="password_confirmation">Confirm Password</label>

                                                    </fieldset>



                                                    <button type="submit"
                                                        class="btn btn-primary text-center btn-inline">Submit</button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/admin/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/admin/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('assets/admin/app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('assets/admin/app-assets/js/scripts/components.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/izitoast/js/iziToast.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("#forgot_pwd_form").validate({
                debug: true,
                errorClass: 'error',
                validClass: 'success',
                errorElement: 'span',
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                    $(element).parents().find('.feather').addClass("text-danger");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).parents(".error").removeClass("error");
                    $(element).removeClass("is-invalid");
                    $(element).parents().find('.feather').removeClass("text-danger");
                },
                rules:{
                    email : {
                        required:true,
                        email:true,
                    },
                    password:{
                        required:true,
                    },
                    password_confirmation:{
                        required:true,
                        equalTo : '[name="password"]'
                    },
                },
                messages: {
                    email: {
                        required: "Email Address Required",
                    },
                    password: {
                        required: "Password Required",
                    },
                    password_confirmation: {
                        required: "Confirm Password Required",
                        equalTo : "Confirm Password Does Not Match",
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }   
            });
        });
    </script>

    @include('flash')

</body>

</html>