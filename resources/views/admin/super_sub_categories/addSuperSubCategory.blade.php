@extends('layouts.admin')
@section('title')
Add Category
@endsection
@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add superSub Category</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('superCategoryList') }}">SuperCategory</a>
                        </li>
                        <li class="breadcrumb-item active">Add Category
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
                        <h4 class="card-title">Add Category</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('savesuperSubCategory') }}" id="category_form"
                                enctype="multipart/form-data">
                            @csrf
                            <div  class="row">
                            <div class="col-xl-6 col-md-6 col-12 mb-1">
                                <fieldset class="form-group">
                                    <label for="category_icon">SuperSub Category Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="supersub_cat_image" class="dropify" id="supersub_cat_image">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-md-6 col-12 mb-1">
                                <fieldset class="form-group">
                                    <label for="name" class="mb-1">Select SuperSubCategory</label>
                                    <select class="select2 form-control select2-hidden-accessible" name="super_category_id">
                                        <option value="">Select SuperSubCategory</option>
                                        {{-- @if(count($superCategory)>0) --}}
                                        @foreach ($superCategory as $Category)
                                        <option value="{{ $Category->id }}">{{ $Category->supercategory_name }}</option>
                                        @endforeach
                                        {{-- @endif --}}
                                    </select>
                                </fieldset>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="name" class="mb-1">SuperSub Category Name</label>
                                        <input type="text" class="form-control" id="supersub_cat_name"name="supersub_cat_name" placeholder="Super Category Name...">
                                    </fieldset>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="status">SuperSub Category Status</label>
                                        <select class="form-control" id="supersub_status" name="supersub_status">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
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
        // $('#categoryListTable').DataTable();
        $('.dropify').dropify();
        // $('.nav-item').removeClass('active');
        // $('.categorylist').addClass('active');
    } );
    </script>
@endsection