@extends('layouts.admin')

@section('title')
Edit Customer
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Customer</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">Customer Management</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Customer
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
                        <h4 class="card-title">Edit Customer</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.users.update',$user->id) }}" id="user_form"
                                enctype="multipart/form-data">

                                @csrf

                                @method('PUT')

                                <div class="row">                                    

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="first_name" class="mb-1">First Name</label>
                                            <input type="text" name="first_name"
                                                class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                                                placeholder="First Name..." value="{{ old('first_name') ? old('first_name')  : $user->first_name }}" readonly>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="last_name" class="mb-1">Last Name</label>
                                            <input type="text" name="last_name"
                                                class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                                                placeholder="Last Name..." value="{{ old('last_name') ? old('last_name')  : $user->last_name }}" readonly>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="email" class="mb-1">Email</label>
                                            <input type="text" name="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                placeholder="Email..." value="{{ old('email') ? old('email')  : $user->email }}" readonly>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="mobile_number" class="mb-1">Mobile No</label>
                                            <input type="text" name="mobile_number"
                                                class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number"
                                                placeholder="Mobile No..." value="{{ old('mobile_number') ? old('mobile_number')  : $user->mobile_number }}" readonly>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="gender" class="mb-1">Gender</label>
                                            <input type="text" name="gender"
                                                class="form-control @error('gender') is-invalid @enderror" id="gender"
                                                placeholder="Mobile No..." value="{{ old('gender') ? old('gender')  : $user->gender }}" readonly>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="mode_of_registration" class="mb-1">Mode of Registration</label>
                                            <input type="text" name="mode_of_registration"
                                                class="form-control @error('mode_of_registration') is-invalid @enderror" id="mode_of_registration"
                                                placeholder="Mode of Registration..." value="{{ old('mode_of_registration') ? old('mode_of_registration')  : $user->mode_of_registration }}" readonly>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label class="mb-1 d-block">Customer Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input"
                                                    value="1" id="customSwitch1" {{ (old('status') || $user->status) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customSwitch1">
                                                </label>
                                                <span class="switch-label"></span>
                                            </div>
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

