@extends('layouts.admin')



@section('title')

Profile

@endsection



@section('content')



<div class="content-header row">



    <div class="content-header-left col-md-9 col-12 mb-2">

        <div class="row breadcrumbs-top">

            <div class="col-12">

                <h2 class="content-header-title float-left mb-0">Profile</h2>

                <div class="breadcrumb-wrapper col-12">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>

                        </li>

                        <li class="breadcrumb-item active">Profile

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

                        <h4 class="card-title">Edit Profile</h4>

                    </div>

                    <div class="card-content">



                        <div class="card-body">



                            <form method="post" action="{{ route('admin.profile') }}" id="profile_form">



                                @csrf



                                <div class="row">



                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="name" class="mb-1">Name</label>

                                            <input type="text" name="name"

                                                class="form-control @error('name') is-invalid @enderror" id="name"

                                                placeholder="Name..." value="{{ Auth()->user()->name }}">

                                        </fieldset>

                                    </div>


                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="email" class="mb-1">Email</label>

                                            <input type="text" name="email"

                                                class="form-control @error('email') is-invalid @enderror" id="email"

                                                placeholder="Email Address..." value="{{ Auth()->user()->email }}">

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

        $("#profile_form").validate({

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

                email : {

                    required:true,

                    email:true,

                },

                name:{

                    required:true,

                },

            },

            messages: {

                email: {

                    required: "Email Address Required",

                },

                name: {

                    required: "Name Required",

                },

            },

            submitHandler: function(form) {

                form.submit();

            }   

        });

    });

</script>

@endsection