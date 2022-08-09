@extends('layouts.admin')
@section('title')
Edit City
@endsection
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit City</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.city.index') }}">City Master</a>
                        </li>
                        <li class="breadcrumb-item active">Edit City
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrum-right">
        
            <div class="dropdown">
        
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle waves-effect waves-light"
                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
                    <i class="feather icon-settings"></i>
        
                </button>
        
            </div>
        
        </div>
        
        </div> -->
</div>
<div class="content-body">
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit City</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.city.update',$city->id) }}" id="city_form"
                                enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">Select State <span class="text-danger h6">*</span></label>
                                        <select class="select2 form-control select2-hidden-accessible"
                                            name="state_id">
                                            <option value="">Select State</option>
                                            @if(count($states)>0)
                                            @foreach ($states as $state)
                                            <option value="{{ $state->id }}" {{ (old('state_id')||$city->
                                            state_id==$state->id) ? 'selected' : '' }} >{{ $state->name }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">City Name <span class="text-danger h6">*</span></label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            placeholder="City Name..."
                                            value="{{ old('name') ? old('name')  : $city->name }}">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">Estimate Delivery Day <span class="text-danger h6">*</span></label>
                                        <input type="number" name="delivery_days"
                                            class="form-control @error('delivery_days') is-invalid @enderror"
                                            id="delivery_days" placeholder="City Name..."
                                            value="{{ old('delivery_days') ? old('delivery_days')  : $city->delivery_days }}">
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1" id="shipping_charge_div">
                                    <fieldset class="form-group">
                                        <label for="shipping_charge" class="mb-1">Shipping Charge <span class="text-danger h6">*</span></label>
                                        <input type="number" name="shipping_charge"
                                            class="form-control @error('shipping_charge') is-invalid @enderror"
                                            id="shipping_charge" placeholder="Shipping Charge..."
                                            value="{{ old('shipping_charge') ? old('shipping_charge')  : $city->shipping_charge }}">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label class="mb-1 d-block">Free Shipping</label>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" name="is_free" class="custom-control-input" value="1" id="free_shipping" {{ (old('is_free') || $city->is_free) ?
                                            'checked' : '' }}>
                                            <label class="custom-control-label" for="free_shipping">
                                            </label>
                                            <span class="switch-label"></span>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label class="mb-1 d-block">City Status</label>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" name="status" class="custom-control-input"
                                            value="1" id="customSwitch1" {{ (old('status') || $city->status) ?
                                            'checked' : '' }}>
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
@section('footer')
<script>
    $(document).ready(function(){
    
        $("#city_form").validate({
    
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
    
                state_id:{
    
                    required:true,
    
                },
    
                
    
            },
    
    
    
            messages: {
    
                name: {
    
                    required: "Sub Category Name Required",
    
                },
    
                state_id:{
    
                    required:"Select State",
    
                },
    
                
    
            },
    
            submitHandler: function(form) {
    
                form.submit();
    
            }   
    
        });
        $('#city_form').on('change', '#free_shipping', function() {

            if($(this).is(":checked"))
            {
                $('#shipping_charge_div').addClass('hidden');
            }
            else
            {
                $('#shipping_charge_div').removeClass('hidden');
            }
        });
        $( "#free_shipping" ).trigger( "change" );
    });
    
</script>
@endsection