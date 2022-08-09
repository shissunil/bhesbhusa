@extends('layouts.admin')

@section('title')
Edit Sub Category
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Sub Category</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.category.index') }}">Sub Category</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Sub Category
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
                        <h4 class="card-title">Edit Sub Category</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.sub-category.update',$sub_category->id) }}" id="sub_category_form"
                                enctype="multipart/form-data">

                                @csrf

                                @method('PUT')

                                <div class="row">

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible" name="category_id">
                                                <option value="">Select Category</option>
                                                @if(count($categories)>0)
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id || $sub_category->category_id==$category->id  ? 'selected' : '' }} >{{ $category->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Sub Category Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Sub Category Name..." value="{{ old('name') ? old('name') : $sub_category->name }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="basicInputFile" class="mb-1">Sub Category Image <span class="text-danger h6">*</span></label>
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
                                            <label class="mb-1 d-block">Sub Category Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input"
                                                    value="1" id="customSwitch1" {{ old('status') || $sub_category->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customSwitch1">
                                                </label>
                                                <span class="switch-label"></span>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        @if($sub_category->image!='')
                                        <img src="{{ asset('uploads/sub_category/'.$sub_category->image) }}"
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
        $("#sub_category_form").validate({
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
                category_id:{
                    required:true,
                },
                // image:{
                //     required:true,
                // },
            },

            messages: {
                name: {
                    required: "Sub Category Name Required",
                },
                category_id:{
                    required:"Select Category",
                },
                // image: {
                //     required: "Sub Category Image Required",
                // },
            },
            submitHandler: function(form) {
                form.submit();
            }   
        });
    });
</script>
@endsection