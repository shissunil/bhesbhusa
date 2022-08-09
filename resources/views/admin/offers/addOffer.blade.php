@extends('layouts.admin')
@section('title')
Add offer
@endsection
@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Offer</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('superCategoryList') }}">Offers</a>
                        </li>
                        <li class="breadcrumb-item active">Add offer
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
                        <h4 class="card-title">Add offer</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('saveOffer') }}" id="category_form"
                                enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">Offer Name</label>
                                        <input type="text" class="form-control" id="offer_name" name="offer_name" placeholder="offer_name ...">
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">Offer Code</label>
                                        <input type="text" class="form-control" id="offer_code" name="offer_code" placeholder="Offer Code...">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <fieldset class="form-group">
                                        <label for="category_icon">Offer Description</label>
                                        <div class="custom-file">
                                            <textarea class="form-control" name="offer_description" id="offer_description" placeholder="Offer Description"></textarea>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="status">Offer Status</label>
                                        <select class="form-control" id="offer_status" name="offer_status">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <fieldset class="form-group">
                                        <label for="category_icon">Start Date</label>
                                        <div class="custom-file">
                                            <input type="date" name="start_date" id="start_date" class="form-control ">
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <fieldset class="form-group">
                                        <label for="category_icon">End Date</label>
                                        <div class="custom-file">
                                            <input type="date" name="end_date" id="end_date" class="form-control ">
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">
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