@extends('layouts.admin')



@section('title')

Trending In Women

@endsection

@section('content')
@php
    $productArr = array_filter(explode(',',$trendingInWomen->product_id));
    // dd($productArr);
@endphp
<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">

        <div class="row breadcrumbs-top">

            <div class="col-12">

                <h2 class="content-header-title float-left mb-0">Trending In Women</h2>

                <div class="breadcrumb-wrapper col-12">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>

                        </li>

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.trendingInWomen.index') }}">Trending In Women</a>

                        </li>

                        <li class="breadcrumb-item active">Edit Trending In Women

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

                        <h4 class="card-title">Edit Trending In Women</h4>

                    </div>

                    <div class="card-content">



                        <div class="card-body">



                            <form method="post" action="{{ route('admin.trendingInWomen.update',$trendingInWomen->id) }}" id="banner_form" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    {{-- <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="name" class="mb-1">Select Offer <span class="text-danger h6">*</span></label>

                                            <select class="select2 form-control select2-hidden-accessible" name="offer_id">

                                                <option value="">Select Offer</option>

                                                @if(count($offers)>0)

                                                @foreach ($offers as $offer)

                                                <option value="{{ $offer->id }}" {{ old('offer_id')==$offer->id  ? 'selected' : '' }} >{{ $offer->offer_name }}</option>

                                                @endforeach

                                                @endif

                                            </select>

                                        </fieldset>

                                    </div>  --}}                                   



                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="basicInputFile" class="mb-1">Image <span class="text-danger h6">*</span></label>

                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror"
                                                    id="inputGroupFile01" accept="image/*">
                                                <label class="custom-file-label" for="inputGroupFile01">Choose
                                                    file </label>
                                            </div>
                                        </fieldset>
                                    </div>  
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        @if($trendingInWomen->image!='')
                                        <img src="{{ asset('uploads/banners/'.$trendingInWomen->image) }}"
                                            class="img-thumbnail" height="100" width="100" />
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Category </label>
                                            <select class="select2 form-control" name="category_id" id="category_id" onchange="categoryId(this)">
                                                <option value="">--SELECT--</option>
                                                @if (count($categorylist) > 0)
                                                    @foreach ($categorylist as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Sub Category </label>
                                            <select class="select2 form-control" name="sub_category_id" id="sub_category_id">
                                                <option value="">--SELECT--</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Brand </label>
                                            <select class="select2 form-control" name="brand_id" id="brand_id">
                                                <option value="">--SELECT--</option>
                                                @if (count($brandList) > 0)
                                                    @foreach ($brandList as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Color </label>
                                            <select class="select2 form-control" name="color_id" id="color_id">
                                                <option value="">--SELECT--</option>
                                                @if (count($colorList) > 0)
                                                    @foreach ($colorList as $color)
                                                        <option value="{{ $color->id }}">{{ $color->color }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Product </label>
                                            <select class="select2 form-control select2-hidden-accessible fav_clr"
                                                name="product_id[]" id="product_id" multiple="multiple" >
                                                @if(count($productList)>0)
                                                <option value="-1" class="select_all">SELECT ALL</option>
                                                @foreach ($productList as $product)
                                                <option value="{{ $product->id }}" {{ in_array($product->id,$productArr) ? 'selected' : '' }}>{{ ucwords($product->product_name) }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label class="mb-1 d-block">Banner Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input"
                                                    value="1" id="customSwitch1" {{ old('status') ||
                                                    $trendingInWomen->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customSwitch1">
                                                </label>
                                                <span class="switch-label"></span>
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



<script>

    $(document).ready(function(){

        $("#banner_form").validate({

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
                banner_image:{

                    required:true,
                },

            },
            messages: {
                banner_image: {

                    required: "Offer Image Required",

                },

            },

            submitHandler: function(form) {

                form.submit();

            }   

        });

    });
    function categoryId(objs)
    {
        var categoryId = $(objs).val();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.get_sub_category') }}",
            data: {"_token":"{{ csrf_token() }}","category_id":categoryId},
            cache: false,
            success: function(result)
            {
                $('#sub_category_id').html(result);
                //alert(result);
                // var obj = JSON.parse(result);
                // $('#ClientID').val(obj.ClientID);
                // $('#ClientName').val(obj.ClientName);
                
                return true;
            }
        });
    }
    function productId(objs)
    {
        var productId = $(objs).val();
        console.log($(objs).is(':selected'));
        if(productId[0] == '-1')
        {
            $('#product_id > option').prop('selected',true);
        }

    }
    $('.fav_clr').on("select2:select", function (e){

        var data = e.params.data.text;
        console.log(e.params.data.selected);
        console.log(e);
        if(data=='SELECT ALL' && e.params.data.selected == true)
        {
            console.log('if');
            $(".fav_clr > option").prop("selected","selected");
            $(".fav_clr").trigger("change");
        }
        if(data=='SELECT ALL' && e.params.data.selected == false)
        {
            console.log('error');
            $(".fav_clr > option").prop("selected",false);
            // $(".fav_clr").trigger("change");
        }
    });
    $('#banner_form').on('change','#category_id,#sub_category_id,#brand_id,#color_id',function(){

        var category_id = $('#category_id').val();
        var sub_category_id = $('#sub_category_id').val();
        var brand_id = $('#brand_id').val();
        var color_id = $('#color_id').val();

        $.ajax({
            type: "POST",
            url: "{{ route('admin.get_product') }}",
            data: {"_token":"{{ csrf_token() }}","category_id":categoryId, "sub_category_id":sub_category_id,"brand_id":brand_id,"color_id":color_id},
            cache: false,
            success: function(result)
            {
                $('#product_id').html(result);
                //alert(result);
                // var obj = JSON.parse(result);
                // $('#ClientID').val(obj.ClientID);
                // $('#ClientName').val(obj.ClientName);
                
                return true;
            }
        });
    });
</script>

@endsection