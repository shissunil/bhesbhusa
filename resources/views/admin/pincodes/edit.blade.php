@extends('layouts.admin')

@section('title')
Edit Pincode
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Pincode</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.pincode.index') }}">Pincode Master</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Pincode
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
                        <h4 class="card-title">Edit Pincode</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.pincode.update',$pincode->id) }}" id="pincode_form"
                                enctype="multipart/form-data">

                                @csrf

                                @method('PUT')

                                <div class="row">

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select State <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="state_id" id="state_id">
                                                <option value="">Select State</option>
                                                @if(count($states)>0)
                                                @foreach ($states as $state)
                                                <option value="{{ $state->id }}" {{ $pincode->state_id==$state->id ?
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
                                                @if(count($cities)>0)
                                                @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" {{ $pincode->city_id==$city->id ?
                                                    'selected' : '' }} >{{ $city->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="pincode" class="mb-1">Pincode <span class="text-danger h6">*</span></label>
                                            <input type="text" name="pincode"
                                                class="form-control @error('pincode') is-invalid @enderror" id="pincode"
                                                placeholder="Pincode..." value="{{ old('pincode') ? old('pincode') : $pincode->pincode }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label class="mb-1 d-block">Pincode Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input"
                                                    value="1" id="customSwitch1" {{ (old('status') ||  $pincode->status ) ? 'checked' : '' }}>
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
        $("#pincode_form").validate({
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
                pincode:{
                    required:true,
                },
                state_id:{
                    required:true,
                },
                city_id:{
                    required:true,
                },
            },

            messages: {
                pincode: {
                    required: "Pincode Required",
                },
                state_id:{
                    required:"Select State",
                },
                city_id:{
                    required:"Select City",
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
                    }
                });
            }
        });
    });
</script>
@endsection