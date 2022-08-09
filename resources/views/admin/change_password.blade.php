@extends('layouts.admin')

@section('title')
Change Password
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Change Password</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Change Password
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle waves-effect waves-light"
                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="feather icon-settings"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="content-body">

    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.change-password') }}" id="password_form">

                                @csrf

                                <div class="row">

                                    <div class="col-xl-12 col-md-12 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="cpassword" class="mb-1">Current Password</label>
                                            <input type="password" name="cpassword"
                                                class="form-control @error('cpassword') is-invalid @enderror" id="cpassword"
                                                placeholder="Current password..." />
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-12 col-md-12 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="password" class="mb-1">New Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                placeholder="New Password...">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-12 col-md-12 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="password_confirmation" class="mb-1">Confirm Password</label>
                                            <input type="password" name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation"
                                                placeholder="Confirm Password...">
                                        </fieldset>
                                    </div>

                                </div>

                                <button type="submit"
                                    class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">
                                    Submit
                                </button>

                                <form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@section('footer')

<script>
    $(document).ready(function(){
        $("#password_form").validate({
            debug: true,
            errorClass: 'error',
            validClass: 'success',
            errorElement: 'span',
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents(".error").removeClass("error");
                $(element).removeClass("is-invalid");
            },
            rules:{
                cpassword : {
                    required:true,
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
                cpassword: {
                    required: "Current Password Required",
                },
                password: {
                    required: "New Password Required",
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
@endsection