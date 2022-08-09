@extends('layouts.admin')

@section('title')
Add Category
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Category</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.category.index') }}">Category</a>
                        </li>
                        <li class="breadcrumb-item active">Add Category
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
                        <h4 class="card-title">Add Category</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.category.store') }}" id="category_form"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Super Sub Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="supersub_cat_id">
                                                <option value="">Select Super Sub Category </option>
                                                {{-- @if(count($superCategory)>0) --}}
                                                @foreach ($superSubCategory as $Category)
                                                <option value="{{ $Category->id }}">{{ $Category->supersub_cat_name }}</option>
                                                @endforeach
                                                {{-- @endif --}}
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Category Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Category Name..." value="{{ old('name') }}">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="basicInputFile" class="mb-1">Category Image <span class="text-danger h6">*</span></label>
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
                                            <label class="mb-1 d-block">Category Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input" value="1" id="customSwitch1" {{ old('status') ? 'checked' : '' }}>
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
        $("#category_form").validate({
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
                image:{
                    required:true,
                },
            },

            messages: {
                name: {
                    required: "Category Name Required",
                },
                image: {
                    required: "Category Image Required",
                },
            },
            submitHandler: function(form) {
                form.submit();
            }   
        });
    });
</script>
@endsection