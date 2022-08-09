@extends('layouts.admin')

@section('title')
Edit Product
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Product</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.product.index') }}">Product Master</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Product
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
                        <h4 class="card-title">Edit Product</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.product.image.update',$productImageData->id) }}" id="city_form"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon" class="mb-1">Product Image <span class="text-danger h6">*</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="product_image" class="dropify" id="product_image" data-default-file="{{ URL::asset('uploads/product/'.$productImageData->product_image) }}">
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Color <span class="text-danger h6">*</span></label>
                                            <select class="form-control" name="color_id">
                                                <option value="">Select Color</option>
                                                @if(count($colorList)>0)
                                                @foreach ($colorList as $color)
                                                <option value="{{ $color->id }}" {{ ($productImageData->color_id == $color->id) ? 'selected' : '' }} >{{ ucwords($color->color) }}</option>
                                                @endforeach
                                                @endif
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
<script src="{{ URL::asset('assets/admin/app-assets/ckeditor/ckeditor.js') }}"></script>
<script>
    $(document).ready(function(){
        // CKEDITOR.replace( 'description' );
        $('.dropify').dropify();
    });
</script>
@endsection