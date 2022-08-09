@extends('layouts.admin')
@section('title')
Add Super Category
@endsection
@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Super Category</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.super-category.index') }}">Super Category</a>
                        </li>
                        <li class="breadcrumb-item active">Add Super Category
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
                        <h4 class="card-title">Add Super Category</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.super-category.store') }}" id="category_form"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">                                    

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon" class="mb-1">Super Category Image <span class="text-danger h6">*</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="supercategory_image" class="dropify"
                                                    id="supercategory_image">
                                            </div>
                                        </fieldset>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Super Category Name <span class="text-danger h6">*</span></label>
                                            <input type="text" class="form-control" id="supercategory_name"
                                                name="supercategory_name" placeholder="Super Category Name...">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="status" class="mb-1">Super Category Status</label>
                                            <select class="form-control" id="supercategory_status"
                                                name="supercategory_status">
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
<!-- END: Page Vendor JS-->
<script type="text/javascript">
    $(document).ready(function() {
            $('.dropify').dropify();
        } );
</script>
{{-- <script>
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
    
</script> --}}
@endsection