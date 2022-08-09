@extends('layouts.admin')

@section('title')
Add Product
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Product</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('productList') }}">Product Master</a>
                        </li>
                        <li class="breadcrumb-item active">Add Product
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
                        <h4 class="card-title">Add Product</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('saveProduct') }}" id="city_form"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon">Product Main Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="product_image" class="dropify" id="product_image">
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon">Product Select Multiple Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="product_multiple_image[]" class="dropify" id="product_multiple_image" multiple="multiple">
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Product Name</label>
                                            <input type="text" name="product_name"
                                                class="form-control @error('product_name') is-invalid @enderror" id="product_name"
                                                placeholder="Product Name..." value="{{ old('product_name') }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Brand Name</label>
                                            <input type="text" name="brand_name"
                                                class="form-control @error('brand_name') is-invalid @enderror" id="brand_name"
                                                placeholder="Brand Name...">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Super Category</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="super_cat_id">
                                                <option value="">Select Super Category</option>
                                                @if(count($superCategoryList)>0)
                                                @foreach ($superCategoryList as $superCategory)
                                                <option value="{{ $superCategory->id }}" {{ old('super_cat_id') == $superCategory->id ? 'selected' : '' }}>{{ $superCategory->supercategory_name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Super Sub Category</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="super_sub_cat_id">
                                                <option value="">Select Super Sub Category</option>
                                                @if(count($superSubCategoryList)>0)
                                                @foreach ($superSubCategoryList as $superSubCategory)
                                                <option value="{{ $superSubCategory->id }}" >{{ $superSubCategory->supersub_cat_name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Category</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="category_id">
                                                <option value="">Select Category</option>
                                                @if(count($categoryList)>0)
                                                @foreach ($categoryList as $category)
                                                <option value="{{ $category->id }}" >{{ $category->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Sub Category</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="sub_category_id">
                                                <option value="">Select Sub Category</option>
                                                @if(count($subCategoryList)>0)
                                                @foreach ($subCategoryList as $subCategory)
                                                <option value="{{ $subCategory->id }}" >{{ $subCategory->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Price</label>
                                            <input type="number" name="price"
                                                class="form-control @error('price') is-invalid @enderror" id="price"
                                                placeholder="Price...">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Product Quantity</label>
                                            <input type="number" name="quantity"
                                                class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                                placeholder="Quantity...">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Color</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="color_id[]" multiple="multiple">
                                                <option value="">Select Color</option>
                                                <option value="1">Black</option>
                                                <option value="2">Teal</option>
                                                <option value="3">Red</option>
                                                <option value="4">Light Grey</option>
                                                <option value="5">Dark Grey</option>
                                                <option value="6">White</option>
                                                <option value="7">Sky Blue</option>
                                                <option value="8">Yellow</option>
                                                <option value="9">Pink</option>
                                                <option value="10">Pale Blue</option>
                                                <option value="11">Dark Blue</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Size</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="size_id[]" multiple="multiple">
                                                <option value="">Select Size</option>
                                                <option value="1">S</option>
                                                <option value="2">M</option>
                                                <option value="3">L</option>
                                                <option value="4">XL</option>
                                                <option value="5">XXL</option>
                                                <option value="6">XXXL</option>
                                                <option value="7">XS</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Product Status</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="product_status">
                                                <option value="1">Active</option>
                                                <option value="0">InActive</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-12">
                                        <fieldset class="form-group">
                                            <label for="roundText">Product Description</label>
                                            <textarea name="description" id="description"></textarea>
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
        CKEDITOR.replace( 'description' );
        $('.dropify').dropify();
        // $("#city_form").validate({
        //     debug: true,
        //     errorClass: 'error',
        //     validClass: 'success',
        //     errorElement: 'span',
        //     highlight: function(element, errorClass, validClass) {
        //         $(element).addClass("is-invalid");
        //     },
        //     unhighlight: function(element, errorClass, validClass) {
        //         $(element).parents(".error").removeClass("error");
        //         $(element).removeClass("is-invalid");
        //     },
        //     rules:{                
        //         name:{
        //             required:true,
        //         },
        //         state_id:{
        //             required:true,
        //         },
                
        //     },

        //     messages: {
        //         name: {
        //             required: "Sub Category Name Required",
        //         },
        //         state_id:{
        //             required:"Select State",
        //         },
                
        //     },
        //     submitHandler: function(form) {
        //         form.submit();
        //     }   
        // });
    });
</script>
@endsection