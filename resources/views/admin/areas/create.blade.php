@extends('layouts.admin')

@section('title')
Add Area
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Area</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.area.index') }}">Area Master</a>
                        </li>
                        <li class="breadcrumb-item active">Add Area
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
                        <h4 class="card-title">Add Area</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.area.store') }}" id="area_form"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="row">

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select State <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="state_id" id="state_id">
                                                <option value="">Select State</option>
                                                @if(count($states)>0)
                                                @foreach ($states as $state)
                                                <option value="{{ $state->id }}" {{ old('state_id')==$state->id ?
                                                    'selected' : '' }} >{{ $state->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select City <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="city_id" id="city_id">
                                                <option value="">Select City</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Pincode <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="pincode_id" id="pincode_id">
                                                <option value="">Select Pincode</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="area" class="mb-1">Area <span class="text-danger h6">*</span></label>
                                            <input type="text" name="area"
                                                class="form-control @error('area') is-invalid @enderror" id="area"
                                                placeholder="Area..." value="{{ old('area') }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label class="mb-1 d-block">Area Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input"
                                                    value="1" id="customSwitch1" {{ old('status') ? 'checked' : '' }}>
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
        $("#area_form").validate({
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
            
                state_id:{
                    required:true,
                },
                city_id:{
                    required:true,
                },
                pincode_id:{
                    required:true,
                },
                area:{
                    required:true,
                },
            },

            messages: {
               
                state_id:{
                    required:"Select State",
                },
                city_id:{
                    required:"Select City",
                },
                pincode_id:{
                    required:"Select Pincode",
                },
                area:{
                    required:"Area Required",
                },
                
            },
            submitHandler: function(form) {
                form.submit();
            }   
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("#state_id").change(function(){
            var state_id = $(this).val();
            if(state_id!=''){
                $.ajax({
                    url : "{{ route('admin.get_city') }}",
                    method:'POST',
                    data:{state_id:state_id,_token:"{{ csrf_token() }}"},
                    success:function(res){
                        $('#city_id').html(res);
                        $("#pincode_id").html("<option value=''>Select Pincode</option>");
                    }
                });
            }
        });

        $("#city_id").change(function(){
            var city_id = $(this).val();
            if(city_id!=''){
                $.ajax({
                    url : "{{ route('admin.get_pincode') }}",
                    method:'POST',
                    data:{city_id:city_id,_token:"{{ csrf_token() }}"},
                    success:function(res){
                        $('#pincode_id').html(res);
                    }
                });
            }
        });
    });
</script>
@endsection