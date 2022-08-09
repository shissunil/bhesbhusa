@extends('layouts.admin')
@section('title')
Edit Category
@endsection
@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
@php
    $subCategoryIdArr = array_filter(explode(',',$offer->sub_category_id));
@endphp
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Super Category</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('superCategoryList') }}">SuperCategory</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Category
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
                        <h4 class="card-title">Edit Super Category</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('updateOffer',$offerData->id) }}"
                                id="category_form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">Offer Name</label>
                                        <input type="text" class="form-control" id="offer_name" name="offer_name" value="{{ $offerData->offer_name }}" placeholder="offer_name ...">
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">Offer Code</label>
                                        <input type="text" class="form-control" id="offer_code" name="offer_code" value="{{ $offerData->offer_code }}" placeholder="Offer Code...">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <fieldset class="form-group">
                                        <label for="category_icon">Offer Description</label>
                                        <div class="custom-file">
                                            <textarea class="form-control" name="offer_description" id="offer_description" placeholder="Offer Description">{{ $offerData->offer_description }}</textarea>
                                        </div>
                                    </fieldset>
                                </div>
                                 <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label class="mb-1 d-block">Offer Status</label>
                                        <select class="form-control" id="status" name="offer_status">
                                            <option value="1" {{ ($offerData->offer_status == '1') ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ ($offerData->offer_status == '0') ? 'selected' : '' }}>InActive</option>
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <fieldset class="form-group">
                                        <label for="category_icon">Start Date</label>
                                        <div class="custom-file">
                                            <input type="date" name="start_date" id="start_date" value="{{ $offerData->start_date }}" class="form-control ">
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <fieldset class="form-group">
                                        <label for="category_icon">End Date</label>
                                        <div class="custom-file">
                                            <input type="date" name="end_date" id="end_date" value="{{ $offerData->end_date }}" class="form-control ">
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">Offer Apply on</label>
                                        <select class="select2 form-control select2-hidden-accessible"
                                            name="is_global" id="is_global" required>
                                            <option>--SELECT--</option>
                                            <option value="1" {{ ($offer->isglobal == '1') ? 'selected' : '' }}>Global</option>
                                            <option value="0" {{ ($offer->isglobal == '1') ? 'selected' : '' }}>Sub Category</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1 hidden" id="sub_category_id">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">Select Sub Category</label>
                                        <select class="select2 form-control select2-hidden-accessible"
                                            name="sub_category_id[]"  multiple="multiple">
                                            @if(count($subCategoryList)>0)
                                                @foreach ($subCategoryList as $subCategory)
                                                    <option value="{{ $subCategory->id }}" {{ in_array($subCategory->id,$subCategoryIdArr)?'selected':'' }}>{{ ucwords($subCategory->name) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1 hidden" id="total_amount">
                                    <fieldset class="form-group">
                                        <label for="end_date" class="mb-1">Minimum Amount</label>
                                        <input type="number" name="total_amount"
                                            class="form-control @error('total_amount') is-invalid @enderror"
                                            placeholder="EX : purchase 10000 NR then 10% off" value="{{ $offer->total_amount }}">
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="end_date" class="mb-1">Offer Discount (%)</label>
                                        <input type="number" name="offer_discount"
                                            class="form-control @error('offer_discount') is-invalid @enderror" id="offer_discount"
                                            placeholder="ex: 10%" value="{{ $offer->offer_discount }}">
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="end_date" class="mb-1">Offer How many user use</label>
                                        <input type="number" name="total_use"
                                            class="form-control @error('total_use') is-invalid @enderror" id="total_use"
                                            placeholder="ex: 10%" value="{{ $offer->total_use }}" required>
                                    </fieldset>
                                </div>
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
        // $('#categoryListTable').DataTable();
        $('.dropify').dropify();
        // $('.nav-item').removeClass('active');
        // $('.categorylist').addClass('active');
    } );
    </script>
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
    
            },
    
    
    
            messages: {
    
                name: {
    
                    required: "Category Name Required",
    
                },
    
            },
    
            submitHandler: function(form) {
    
                form.submit();
    
            }   
    
        });
    
    });
    
</script>
@endsection