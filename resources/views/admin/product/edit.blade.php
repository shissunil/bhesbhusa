@extends('layouts.admin')

@section('title')
Edit Product
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
@php
    $colorArr = array_filter(explode(',',$productData->color_id));
    $sizeArr = array_filter(explode(',',$productData->size_id));
@endphp
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
                            <form method="post" action="{{ route('admin.product.update',$productData->id) }}" id="city_form"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="category_icon" class="mb-1">Product Main Image <span class="text-danger h6">*</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="product_image" class="dropify" id="product_image" data-default-file="{{ URL::asset('uploads/product/'.$productData->product_image) }}">
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1 mt-3">
                                        <fieldset class="form-group">
                                            <a href="{{ route('admin.product.image.index',$productData->id) }}" class="btn btn-primary"><i class="fa fa-pencil"></i>Edit Color And Other images</a>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Product Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="product_name"
                                                class="form-control @error('product_name') is-invalid @enderror" id="product_name"
                                                placeholder="Product Name..." value="{{ $productData->product_name }}">
                                        </fieldset>
                                    </div>
                                    {{-- <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Brand Name</label>
                                            <input type="text" name="brand_name"
                                                class="form-control @error('brand_name') is-invalid @enderror" id="brand_name"
                                                placeholder="Brand Name..." value="{{ $productData->brand_name }}">
                                        </fieldset>
                                    </div> --}}
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Brand <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible" name="brand_id">
                                                <option value="">Select Brand</option>
                                                @if(count($brandList)>0)
                                                @foreach ($brandList as $brand)
                                                <option value="{{ $brand->id }}" {{ ($productData->brand_id == $brand->id) ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Super Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible" name="super_cat_id" id="super_cat_id">
                                                <option value="">Select Super Category</option>
                                                @if(count($superCategoryList)>0)
                                                @foreach ($superCategoryList as $superCategory)
                                                <option value="{{ $superCategory->id }}" {{ ($productData->super_cat_id == $superCategory->id) ? 'selected' : '' }}>{{ $superCategory->supercategory_name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Super Sub Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible" name="super_sub_cat_id" id="super_sub_cat_id">
                                                <option value="">Select Super Sub Category</option>
                                                @if(count($superSubCategoryList)>0)
                                                @foreach ($superSubCategoryList as $superSubCategory)
                                                <option value="{{ $superSubCategory->id }}" {{ ($productData->super_sub_cat_id == $superSubCategory->id) ? 'selected' :'' }}>{{ $superSubCategory->supersub_cat_name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible" name="category_id" id="category_id">
                                                <option value="">Select Category</option>
                                                @if(count($categoryList)>0)
                                                @foreach ($categoryList as $category)
                                                <option value="{{ $category->id }}" {{ ($productData->category_id == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Sub Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible" name="sub_category_id" id="sub_category_id">
                                                <option value="">Select Sub Category</option>
                                                @if(count($subCategoryList)>0)
                                                @foreach ($subCategoryList as $subCategory)
                                                <option value="{{ $subCategory->id }}" {{ ($productData->sub_category_id == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Price <span class="text-danger h6">*</span></label>
                                            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" id="price"
                                                placeholder="Price..." value="{{ $productData->price }}" onkeypress="validate()">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Product Quantity <span class="text-danger h6">*</span></label>
                                            <input type="text" name="quantity"
                                                class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                                placeholder="Quantity..." value="{{ $productData->quantity }}" min="1" onkeypress="validate()">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Color <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible" name="color_id[]" multiple="multiple">
                                                <option value="">Select Color</option>
                                                @if(count($colorList)>0)
                                                @foreach ($colorList as $color)
                                                <option value="{{ $color->id }}" {{ in_array($color->id,$colorArr)?'selected':'' }} >{{ ucwords($color->color) }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Size <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible" name="size_id[]" multiple="multiple">
                                                <option value="">Select Size</option>
                                                @if (count($sizeList) > 0)
                                                    @foreach ($sizeList as $size)
                                                        <option value="{{ $size->id }}" {{ in_array($size->id,$sizeArr)?'selected':'' }} >{{ ucwords($size->size) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Product Status</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="product_status">
                                                <option value="1" {{ ($productData->product_status == '1') ? 'selected' :'' }}>Active</option>
                                                <option value="0" {{ ($productData->product_status == '0') ? 'selected' : '' }}>InActive</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Sold By</label>
                                            <input type="text" name="sold_by"
                                                class="form-control @error('sold_by') is-invalid @enderror"
                                                id="sold_by" placeholder="Sold by..." value="{{ $productData->sold_by }}">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-12">
                                        <fieldset class="form-group">
                                            <label for="roundText" class="mb-1">Product Description <span class="text-danger h6">*</span></label>
                                            <textarea  class="form-control" name="description" id="description">{{ $productData->description }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Fit</label>
                                            <textarea  class="form-control" name="fit" rows="1">{{ $productData->fit }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Pattern</label>
                                            <textarea  class="form-control" name="pattern" rows="1">{{ $productData->pattern }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Wash</label>
                                            <textarea  class="form-control" name="wash" rows="1">{{ $productData->wash }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Color</label>
                                            <textarea  class="form-control" name="color" rows="1">{{ $productData->color }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Neck/Collar</label>
                                            <textarea  class="form-control" name="neck_collar" rows="1">{{ $productData->neck_collar }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Model Fit</label>
                                            <textarea  class="form-control" name="model_fit" rows="1">{{ $productData->model_fit }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Sleeve</label>
                                            <textarea  class="form-control" name="sleeve" rows="1">{{ $productData->sleeve }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Fabric</label>
                                            <textarea  class="form-control" name="fabric" rows="1">{{ $productData->fabric }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Product ID</label>
                                            <textarea  class="form-control" name="productid" rows="1">{{ $productData->productid }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Product Discount</label>
                                            <input type="text" name="discount"
                                                class="form-control @error('discount') is-invalid @enderror" id="discount"
                                                placeholder="Discount in (%)" value="{{ $productData->discount }}" min="1" onkeypress="validate()">
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


        $('#super_cat_id').on('change',function(){
            var superCatId =  this.value;
            $.ajax({
                type: "POST",
                url: "{{ route('admin.get_super_sub_category') }}",
                data: {_token:"{{ csrf_token() }}", super_cat_id:superCatId},
                cache: false,
                success: function(result)
                {
                    $('#super_sub_cat_id').html(result);
                }
            });
        });

        $('#super_sub_cat_id').on('change',function(){
            var superSubCatId =  this.value;
            $.ajax({
                type: "POST",
                url: "{{ route('admin.get_category') }}",
                data: {_token:"{{ csrf_token() }}", super_sub_cat_id:superSubCatId},
                cache: false,
                success: function(result)
                {
                    $('#category_id').html(result);
                }
            });
        });
        $('#category_id').on('change',function(){
            var categoryId =  this.value;
            $.ajax({
                type: "POST",
                url: "{{ route('admin.get_sub_category') }}",
                data: {_token:"{{ csrf_token() }}", category_id:categoryId},
                cache: false,
                success: function(result)
                {
                    $('#sub_category_id').html(result);
                }
            });
        });
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