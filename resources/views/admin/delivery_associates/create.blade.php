@extends('layouts.admin')

@section('title')
Add Delivery Associate
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Delivery Associate</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.delivery_associates.index') }}">Delivery Associates</a>
                        </li>
                        <li class="breadcrumb-item active">Add Delivery Associate
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle waves-effect waves-light"
                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="feather icon-settings"></i>
                </button>
            </div>
        </div>
    </div> --}}
</div>

<div class="content-body">

    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Delivery Associate</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.delivery_associates.store') }}"
                                id="user_form" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon" class="mb-1">Profile Pic <span class="text-danger h6">*</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="profile_pic" class="dropify"
                                                    id="profile_pic" required>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon" class="mb-1">Vehicle Document <span class="text-danger h6">*</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="vechicle_doc" class="dropify"
                                                    id="vechicle_doc" required>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="first_name" class="mb-1">First Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="first_name"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                id="first_name" placeholder="First Name..."
                                                value="{{ old('first_name') }}" required
                                               >
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="last_name" class="mb-1">Last Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="last_name"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                id="last_name" placeholder="Last Name..."
                                                value="{{ old('last_name') }}" required
                                               >
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="email" class="mb-1">Email <span class="text-danger h6">*</span></label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                placeholder="Email..."
                                                value="{{ old('email') }}" required>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="email" class="mb-1">Password <span class="text-danger h6">*</span></label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                placeholder="password..."
                                                value="{{ old('password') }}" required>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="mobile_number" class="mb-1">Mobile No <span class="text-danger h6">*</span></label>
                                            <input type="number" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" placeholder="Mobile No..." value="{{ old('mobile_number') }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="mobile_number" class="mb-1">Vehicle Number <span class="text-danger h6">*</span></label>
                                            <input type="text" name="vehicle_number"
                                                class="form-control @error('vehicle_number') is-invalid @enderror"
                                                id="vehicle_number" placeholder="Vehicle Number..."
                                                value="{{ old('vehicle_number') }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="mobile_number" class="mb-1">License Number <span class="text-danger h6">*</span></label>
                                            <input type="text" name="license_number"
                                                class="form-control @error('license_number') is-invalid @enderror"
                                                id="license_number" placeholder="License Number..."
                                                value="{{ old('license_number') }}" required
                                               >
                                        </fieldset>
                                    </div>
                                    
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Status</label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="status">
                                                <option value="1">Active</option>
                                                <option value="0">InActive</option>
                                            </select>
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
<script src="{{ URL::asset('assets/admin/app-assets/dropify/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('assets/admin/app-assets/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    $('.dropify').dropify();
</script>
@endsection