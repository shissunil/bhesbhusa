@extends('layouts.admin')



@section('title')

Add Sub Admin

@endsection



@section('content')



<div class="content-header row">



    <div class="content-header-left col-md-9 col-12 mb-2">

        <div class="row breadcrumbs-top">

            <div class="col-12">

                <h2 class="content-header-title float-left mb-0">Add Sub Admin</h2>

                <div class="breadcrumb-wrapper col-12">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>

                        </li>

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.sub_admins.index') }}">Sub Admin</a>

                        </li>

                        <li class="breadcrumb-item active">Add Sub Admin

                        </li>

                    </ol>

                </div>

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

                        <h4 class="card-title">Add Sub Admin</h4>

                    </div>

                    <div class="card-content">



                        <div class="card-body">



                            <form method="post" action="{{ route('admin.sub_admins.store') }}" id="sub_admin_form"
                                enctype="multipart/form-data">



                                @csrf



                                <div class="row">



                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="name" class="mb-1">Name <span class="text-danger h6">*</span></label>

                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="name" placeholder="Name..."
                                                value="{{ old('name') }}">

                                        </fieldset>

                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="email" class="mb-1">Email <span class="text-danger h6">*</span></label>

                                            <input type="text" name="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                placeholder="Email..." value="{{ old('email') }}">

                                        </fieldset>

                                    </div>



                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="password" class="mb-1">Password <span class="text-danger h6">*</span></label>

                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password" placeholder="Password..." value="{{ old('password') }}">

                                        </fieldset>

                                    </div>



                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="role" class="mb-1">Select Role <span class="text-danger h6">*</span></label>

                                            <select name="role_id" class="form-control">

                                                @if(count($roles)>0)

                                                @foreach($roles as $role)

                                                <option value="{{ $role->id }}">{{ $role->name }}</option>

                                                @endforeach

                                                @endif

                                            </select>

                                        </fieldset>

                                    </div>



                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label class="mb-1 d-block">Status</label>

                                            <div class="custom-control custom-switch custom-control-inline">

                                                <input type="checkbox" name="status" class="custom-control-input"
                                                    value="1" id="customSwitch1" {{ old('status') ? 'checked' : '' }}>

                                                <label class="custom-control-label" for="customSwitch1">

                                                </label>

                                                <span class="switch-label"></span>

                                            </div>

                                        </fieldset>

                                    </div>



                                    <div class="col-md-12">

                                        @if(count($permissions)>0)



                                        @foreach($permissions as $permission)



                                        <div class="form-group border p-2">

                                            <label class="mb-1 d-block">

                                                <strong>{{ preg_replace('/(?<!\ )[A-Z]/', ' $0'
                                                        ,str_replace('Controller','',last(explode("\\",$permission->

                                                        controller)))) }}</strong>

                                            </label>

                                            <hr>

                                            @php

                                            $routes = array_filter(explode(",",$permission->route_names));

                                            $rmethod = array_filter(explode(",",$permission->route_methods));

                                            $pids = array_filter(explode(",",$permission->pids));



                                            $labels = [

                                            'index' => 'List',

                                            'sub_admins' => 'List',

                                            'create' => 'Add',

                                            'store' => 'Save',

                                            'send' => 'Send',

                                            'edit' => 'Edit',

                                            'update' => 'Update',

                                            'destroy' => 'Delete',

                                            'ongoingBooking' => 'Ongoing Booking',

                                            'pastBooking' => 'Past Booking',

                                            'upcomingBooking' => 'Upcoming Booking',

                                            ];



                                            @endphp



                                            @foreach($routes as $k=>$route)



                                            <input type="checkbox" name="permissions[]" value="{{ $pids[$k] }}" />

                                            <span class="p-1 font-weight-bold">{{
                                                (array_key_exists($rmethod[$k],$labels))?$labels[$rmethod[$k]]:$rmethod[$k]
                                                }}</span>

                                            @endforeach

                                        </div>



                                        @endforeach



                                        @endif

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

        $("#sub_admin_form").validate({

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

                name:{

                    required:true,

                },               

                email:{

                    required:true,

                    email:true,

                },

                password:{

                    required:true,

                },

                role:{

                    required:true,

                },

            },



            messages: {

                name: {

                    required: "Name Required",

                },               

                email: {

                    required: "Email address Required",

                },

                password: {

                    required: "Password Required",

                },

                role: {

                    required: "Select Role",

                },

            },

            submitHandler: function(form) {

                form.submit();

            }   

        });

    });

</script>

@endsection