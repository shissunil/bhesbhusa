@extends('layouts.admin')

@section('title')
Edit Delivery Associate
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Delivery Associate</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.delivery_associates.index') }}">Delivery Associates</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Delivery Associate
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
                        <h4 class="card-title">Edit Delivery Associate</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.delivery_associates.update',$user->id) }}"
                                id="user_form" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon" class="mb-1">Profile Pic <span class="text-danger h6">*</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="profile_pic" class="dropify"
                                                    id="profile_pic" data-default-file="{{ URL::asset('uploads/delivery_associates/'.$user->profile_pic) }}">
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon" class="mb-1">Vehicle Document <span class="text-danger h6">*</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="vechicle_doc" class="dropify"
                                                    id="vechicle_doc" data-default-file="{{ URL::asset('uploads/driver_vehicle_doc/'.$user->vechicle_doc) }}">
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="first_name" class="mb-1">First Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="first_name"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                id="first_name" placeholder="First Name..."
                                                value="{{ old('first_name') ? old('first_name')  : $user->first_name }}" required
                                               >
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="last_name" class="mb-1">Last Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="last_name"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                id="last_name" placeholder="Last Name..."
                                                value="{{ old('last_name') ? old('last_name')  : $user->last_name }}" required
                                               >
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="email" class="mb-1">Email <span class="text-danger h6">*</span></label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                placeholder="Email..."
                                                value="{{ old('email') ? old('email')  : $user->email }}" required>
                                        </fieldset>
                                    </div>

                                    {{-- <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="email" class="mb-1">Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                placeholder="password..."
                                                value="{{ old('password') ? old('password') : $user->password }}" required>
                                        </fieldset>
                                    </div> --}}

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="mobile_number" class="mb-1">Mobile No <span class="text-danger h6">*</span></label>
                                            <input type="number" name="mobile_number"
                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                id="mobile_number" placeholder="Mobile No..."
                                                value="{{ old('mobile_number') ? old('mobile_number')  : $user->mobile_number }}"
                                               required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="mobile_number" class="mb-1">Vehicle Number <span class="text-danger h6">*</span></label>
                                            <input type="text" name="vehicle_number"
                                                class="form-control @error('vehicle_number') is-invalid @enderror"
                                                id="vehicle_number" placeholder="Vehicle Number..."
                                                value="{{ old('vehicle_number') ? old('vehicle_number') : $user->vechicle_number }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="mobile_number" class="mb-1">License Number <span class="text-danger h6">*</span></label>
                                            <input type="text" name="license_number"
                                                class="form-control @error('license_number') is-invalid @enderror"
                                                id="license_number" placeholder="License Number..."
                                                value="{{ old('license_number') ? old('license_number') : $user->license_number }}" required
                                               >
                                        </fieldset>
                                    </div>
                                    
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Status</label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="status">
                                                <option value="1" {{ ($user->status == '1') ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ ($user->status == '0') ? 'selected' : '' }}>InActive</option>
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

    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Assign Orders</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order ID</th>
                                            <th>Order Date</th>
                                            <th>Order Amount</th>
                                            <th>Customer Name</th>
                                            <th>Order Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($assignOrders) > 0)
                                        @foreach ($assignOrders as $booking)
                                        <tr>
                                            <td>{{ $loop->iteration}}</td>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ date('d-m-Y',strtotime($booking->created_at)) }}</td>
                                            <td>{{ $booking->total_amount }}</td>
                                            <td>{{ $booking->full_name }}</td>
                                            <td>
                                                @if($booking->order_status==1)
                                                <p class="badge badge-pill badge-light-primary">Pending</p>
                                                @elseif($booking->order_status==2)
                                                <p class="badge badge-pill badge-light-info">Assigned</p>
                                                @elseif($booking->order_status==3)
                                                <p class="badge badge-pill badge-light-danger">Cancelled</p>
                                                @elseif($booking->order_status==4)
                                                <p class="badge badge-pill badge-light-success">Delivered</p>
                                                @elseif($booking->order_status==5)
                                                <p class="badge badge-pill badge-light-warning">Returned</p>
                                                @elseif($booking->order_status==6)
                                                <p class="badge badge-pill badge-light-info">Out For Service</p>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.booking.detail',$booking->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye fa-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
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