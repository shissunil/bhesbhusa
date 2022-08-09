@extends('layouts.admin')

@section('title')
Edit Brand
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Brand</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.brand.index') }}">Brands</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Brand
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
                        <h4 class="card-title">Edit Brand</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.brand.update',$brand->id) }}"
                                id="Brand_form" enctype="multipart/form-data">

                                @csrf

                                @method('PUT')

                                <div class="row">

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Brand Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Brand Name..."
                                                value="{{ old('name') ? old('name') : $brand->name }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="basicInputFile" class="mb-1">Brand Image <span class="text-danger h6">*</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="image"
                                                    class="custom-file-input @error('image') is-invalid @enderror"
                                                    id="inputGroupFile01" accept="image/*">
                                                <label class="custom-file-label" for="inputGroupFile01">Choose
                                                    file</label>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label class="mb-1 d-block">Brand Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input"
                                                    value="1" id="customSwitch1"
                                                    {{ $brand->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customSwitch1">
                                                </label>
                                                <span class="switch-label"></span>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        @if($brand->image!='')
                                        <img src="{{ asset('uploads/brand/'.$brand->image) }}"
                                            class="img-thumbnail" height="100" width="100" />
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
        $("#Brand_form").validate({
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
            },

            messages: {
                name: {
                    required: "Brand Name Required",
                },
            },
            submitHandler: function(form) {
                form.submit();
            }   
        });
    });
</script>
@endsection