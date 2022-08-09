@extends('front.layout.main')

@section('section')

    <style>
        .color-radio input:checked~.checkmark {
            border: 2px solid #04c6d4 !important;
            box-shadow: inset 0px 0px 0px 3px #fff;
        }

    </style>

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

                @if ($bradcumbData->super_category_data)

                    <li class="breadcrumb-item">
                        <a href="#">{{ ucwords($bradcumbData->super_category_data->supercategory_name) }}</a>
                    </li>

                @endif

                @if ($bradcumbData->super_sub_category_data)

                    <li class="breadcrumb-item">
                        <a href="#">{{ ucwords($bradcumbData->super_sub_category_data->supersub_cat_name) }}</a>
                    </li>

                @endif

                @if ($bradcumbData->category_data)

                    <li class="breadcrumb-item">
                        <a href="#">{{ ucwords($bradcumbData->category_data->name) }}</a>
                    </li>

                @endif

                @if ($bradcumbData)

                    <li class="breadcrumb-item">
                        <a href="#">{{ ucwords($bradcumbData->name) }}</a>
                    </li>

                @endif

                <li class="breadcrumb-item active">{{ ucwords($productDetail->product_name) }}</li>

            </ol>

        </div>

    </section>

    <section class="product-detail">

        <div class="container">

            <div class="row">

                <div class="col-xl-6 col-lg-6 col-md-12">

                    <div class="product-slider">

                        <div class="slider-inner">

                            <div class="product-thumbmail">

                                <div class="item">
                                    <div class="img">
                                        <img src="{{ $productDetail->product_image != '' ? $productDetail->product_image : asset('assets/front/images/no-img.png') }}"
                                            alt="">
                                    </div>
                                </div>

                                @if (count($productDetail->product_images) > 0)

                                    @foreach ($productDetail->product_images as $productImage)

                                        <div class="item">
                                            <div class="img">
                                                <img src="{{ $productImage->product_image != '' ? $productImage->product_image : asset('assets/front/images/no-img.png') }}"
                                                    alt="">
                                            </div>
                                        </div>

                                    @endforeach

                                @endif

                            </div>

                            <div class="product-main">

                                <div class="item" id="product-item-img">
                                    <div class="img" id="product-img">
                                        <img src="{{ $productDetail->product_image != '' ? $productDetail->product_image : asset('assets/front/images/no-img.png') }}"
                                            alt="" >
                                    </div>
                                </div>

                                @if (count($productDetail->product_images) > 0)

                                    @foreach ($productDetail->product_images as $productImage)

                                        <div class="item">
                                            <div class="img">
                                                <img src="{{ $productImage->product_image != '' ? $productImage->product_image : asset('assets/front/images/no-img.png') }}"
                                                    alt="">
                                            </div>
                                        </div>

                                    @endforeach

                                @endif

                            </div>

                        </div>

                        <div class="button-list">

                            <button class="btn theme-btn btn_add_to_cart"><i class="fas fa-shopping-bag"></i>Add To
                                Bag</button>

                            <button class="btn border-btn" onclick="addRemoveWishlist({{ $productDetail->id }},1)"><i
                                    class="far fa-heart"></i>Wishlist</button>

                        </div>

                    </div>

                </div>

                <div class="col-xl-6 col-lg-6 col-md-12">

                    <div class="product-content">

                        <h2><span>{{ ucwords($productDetail->brand_name) }}</span>{{ ucwords($productDetail->product_name) }}
                        </h2>

                        <div class="price-main">

                            <div class="price">
                                @if ($productDetail->discount_price != '')
                                    {{ $productDetail->discount_price }}
                                    <span>{{ $productDetail->price }}</span>
                                @else
                                    {{ $productDetail->price }}
                                @endif
                            </div>

                            <div class="offer">{{ $productDetail->discount }}</div>

                        </div>

                        <p>{{ $productDetail->description }}</p>

                        <form action="{{ route('checkPincode') }}" method="post" id="pincode_form">

                            @csrf

                            <div class="select-color">

                                <h4 class="title">Color</h4>

                                <ul>

                                    @if (count($productDetail->colorList) > 0)


                                        @foreach ($productDetail->colorList as $productColor)

                                            <li>

                                                <div class="color-box">

                                                    <div>{{ ucwords($productColor->color) }}</div>

                                                    <label class="color-radio">

                                                        <input type="radio" name="product_color_id" value="{{ $productColor->id }}"
                                                            @if(Session::has('product_color_id')) 
                                                            {{ Session::get('product_color_id')==$productColor->id ? 'checked' : '' }}
                                                            @endif
                                                             onchange="changeImage(this)">

                                                        <span class="checkmark"
                                                            style="background: {{ $productColor->code }}; 
                                                            @if ($productColor->code == '#FFFFFF') border:2px solid lightgrey; @endif ">
                                                        </span>
                                                    </label>

                                                </div>

                                            </li>

                                        @endforeach

                                    @endif

                                </ul>

                            </div>



                            <div class="select-size">

                                <div class="size-title">
                                    <h4 class="title">Select Size</h4>
                                </div>

                                <ul>

                                    @if (count($productDetail->sizeList) > 0)


                                        @foreach ($productDetail->sizeList as $productSize)

                                            <li>

                                                <label class="size-radio">

                                                    <input type="radio" name="product_size_id"
                                                        value="{{ $productSize->id }}"
                                                        @if(Session::has('product_size_id')) 
                                                            {{ Session::get('product_size_id')==$productSize->id ? 'checked' : '' }}
                                                        @endif
                                                        >

                                                    <span class="checkmark">{{ $productSize->size }}</span>

                                                </label>

                                            </li>

                                        @endforeach

                                    @endif

                                </ul>

                            </div>



                        <div class="delivery-option">

                            <h4 class="title">Select Quantity</h4>

                            <div class="form-group">

                                @php
                                    $quantity = 1;                                    
                                @endphp

                                <input 
                                type="number" min="1" 
                                max="{{ $productDetail->quantity - (int)$productDetail->used_quantity }}" 
                                name="qty" value="{{ Session::get('qty') ?? $quantity }}"
                                class="form-control w-50" placeholder="Quantity" required>

                            </div>


                            <h4 class="title">Delivery Options</h4>                            

                            <div class="form-group">

                                @php
                                    $pincode = '';
                                    if ($userAddress) {
                                        $pincode = $userAddress->pincode;
                                    }
                                    if(Session::has('pincode')){
                                        $pincode = Session::get('pincode');
                                    }
                                @endphp



                                <input type="text" value="{{ Session::get('pincode') ?? $pincode }}" name="pincode" id="pincode" onkeypress="validate()" class="form-control" placeholder="Pincode" required />

                                {{-- <button type="submit" class="btn theme-btn">Check</button> --}}
                                <button type="button" class="btn theme-btn check_pincode">Check</button>

                            </div>

                            

                            <p><b>Delivery By:</b>
                                {{ Session::has('pincode_data') ? Session::get('pincode_data')->delivery_days : $productDetail->delivery_days }}
                            </p>

                        </div>

                        </form>

                    </div>



                    <div class="feature-list">

                        <h3>Features</h3>

                        <ul>

                            <li>
                                <div class="left">Fit</div>
                                <div class="right">{{ $productDetail->fit }}</div>
                            </li>

                            <li>
                                <div class="left">Pattern</div>
                                <div class="right">{{ $productDetail->pattern }}</div>
                            </li>

                            <li>
                                <div class="left">Wash</div>
                                <div class="right">{{ $productDetail->wash }}</div>
                            </li>

                            <li>
                                <div class="left">Color</div>
                                <div class="right">{{ $productDetail->color }}</div>
                            </li>

                            <li>
                                <div class="left">Neck/Collar</div>
                                <div class="right">{{ $productDetail->neck_collar }}</div>
                            </li>

                            <li>
                                <div class="left">Model Fit</div>
                                <div class="right">{{ $productDetail->model_fit }}</div>
                            </li>

                            <li>
                                <div class="left">Sleeve</div>
                                <div class="right">{{ $productDetail->sleeve }}</div>
                            </li>

                            <li>
                                <div class="left">Fabric</div>
                                <div class="right">{{ $productDetail->fabric }}</div>
                            </li>

                            <li>
                                <div class="left">Product ID</div>
                                <div class="right">{{ $productDetail->productid }}</div>
                            </li>

                        </ul>

                    </div>



                    <div class="rating-review">

                        <h3>Rating & Reviews</h3>

                        <div class="custom-rating">

                            <div class="box">

                                <div class="total-counter">
                                    <h2>{{ $productDetail->rating }}<i class="far fa-star"></i>
                                        <span>
                                            {{-- 52839 --}}
                                        </span>
                                    </h2>
                                </div>

                                <div class="starwise">

                                    <ul>

                                        <li>
                                            <div class="no">5</div>
                                            <div class="prograss"><span style="width:{{ ($reviewArray) ? $reviewArray['five_star'].'%' : '0' }}"></span></div>{{ ($reviewArray) ? $reviewArray['five_star'].'%' : '0' }}
                                        </li>

                                        <li>
                                            <div class="no">4</div>
                                            <div class="prograss"><span style="width:{{ ($reviewArray) ? $reviewArray['four_star'].'%' : '0' }}"></span></div>{{ ($reviewArray) ? $reviewArray['four_star'].'%' : '0' }}
                                        </li>

                                        <li>
                                            <div class="no">3</div>
                                            <div class="prograss"><span style="width:{{ ($reviewArray) ? $reviewArray['three_star'].'%' : '0' }}"></span></div>{{ ($reviewArray) ? $reviewArray['three_star'].'%' : '0' }}
                                        </li>

                                        <li>
                                            <div class="no">2</div>
                                            <div class="prograss"><span style="width:{{ ($reviewArray) ? $reviewArray['two_star'].'%' : '0' }}"></span></div>{{ ($reviewArray) ? $reviewArray['two_star'].'%' : '0' }}
                                        </li>

                                        <li>
                                            <div class="no">1</div>
                                            <div class="prograss"><span style="width:{{ ($reviewArray) ? $reviewArray['one_star'].'%' : '0' }}"></span></div>{{ ($reviewArray) ? $reviewArray['one_star'].'%' : '0' }}
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                        <ul>

                            <li>

                                <div class="box">

                                    <h4>Customer Reviews ({{ ($reviewArray) ? $reviewArray['total_count'] : '0' }})</h4>

                                    {{-- <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh
                                        euismod tincidunt ut laoreet dolore</p>

                                    <div class="name-time">John Martins | 30 april, 2021</div> --}}

                                </div>

                            </li>
                            @if ($reviewArray && count($reviewArray['review_detail']) > 0)
                                @foreach ($reviewArray['review_detail'] as $review)
                                    <li>

                                        <div class="box">

                                            <p>{{ $review->message }}</p>

                                            <div class="name-time">{{ $review->customer_name }} | {{ $review->review_date }}</div>

                                        </div>

                                    </li>
                                @endforeach
                                {{ $reviewArray['review_detail']->links('vendor.pagination.default') }}
                            @endif
                        </ul>

                    </div>

                </div>

            </div>

        </div>

        <form action="{{ route('addToCart') }}" method="post" id="addToCartForm">

            @csrf

        </form>

    </section>

    @if (count($similiarProducts) > 0)

        <section class="new-arrival">

            <div class="container">

                <h2 class="page-title">Similar <span>Products</span></h2>

                <div class="product-listing">

                    @foreach ($similiarProducts as $product)

                        <div class="item">

                            <div class="product-box">

                                <a href="{{ route('productDetails', Crypt::encrypt($product->id)) }}">

                                    <div class="pro-img">

                                        <div class="img">
                                            <img src="{{ $product->product_image != '' ? $product->product_image : asset('assets/front/images/no-img.png') }}"
                                                alt="{{ $product->product_name }}">
                                        </div>

                                        <div class="star-rating">
                                            {{ $product->rating }}
                                            <i class="far fa-star"></i>
                                        </div>

                                    </div>

                                </a>

                                <div class="details">

                                    <div class="top-bar">

                                        <div class="brand">{{ ucwords($product->brand_name) }}</div>

                                        <div class="favourite-mark">

                                            <label>
                                                <input type="checkbox" name="checkvalue" class="checkvalue"
                                                    {{ $product->favorite == 1 ? 'checked' : '' }}>
                                                <span></span>
                                            </label>

                                        </div>

                                    </div>

                                    <a href="{{ route('productDetails', Crypt::encrypt($product->id)) }}">
                                        <p>{{ $product->product_name }}</p>
                                    </a>

                                    <div class="bottom-price">

                                        <div class="price">
                                            @if ($product->discount_price != '')
                                                {{ $product->discount_price }}
                                                <span>{{ $product->price }}</span>
                                            @else
                                                {{ $product->price }}
                                            @endif
                                        </div>

                                        <div class="off-rate">{{ $product->discount }}</div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </section>

    @endif

@endsection

@section('footer')

    <script>

        

        $(document).ready(function() {

            $(".btn_add_to_cart").click(function() {
                var color = $('[name="product_color_id"]:checked').val() ?? '';
                var size = $('[name="product_size_id"]:checked').val() ?? '';
                var pincode = $('[name="pincode"]').val();
                var qtyInput = $('[name="qty"]');
                var qty = qtyInput.val();
                var minQty = parseInt(qtyInput.attr('min'));
                var maxQty = parseInt({{ $productDetail->quantity }});
                var product_id = {{ $productDetail->id }};
                if (color == '') {
                    showError('Select any color');
                    return false;
                } else if (size == '') {
                    showError('Select any size');
                    return false;
                } else if (pincode == '') {
                    showError('Enter pincode');
                    return false;
                } else if (qty == '') {
                    showError('Enter quantity');
                    return false;
                } else if (qty < minQty) {
                    showError('Minimum quantity : ' + minQty);
                    return false;
                } else if (qty > maxQty) {
                    showError('Maximum quantity : ' + maxQty);
                    return false;
                } else {
                    var form = $("#addToCartForm");
                    var colorInput = $('<input name="color" type="hidden" value="' + color + '">');
                    var sizeInput = $('<input name="size" type="hidden" value="' + size + '">');
                    var pincodeInput = $('<input name="pincode" type="hidden" value="' + pincode + '">');
                    var quantityInput = $('<input name="qty" type="hidden" value="' + qty + '">');
                    var pIdInput = $('<input name="product_id" type="hidden" value="' + product_id + '">');
                    form.append(colorInput);
                    form.append(sizeInput);
                    form.append(pincodeInput);
                    form.append(quantityInput);
                    form.append(pIdInput);
                    form.submit();
                }

            });
            $('.check_pincode').click(function(){
                // var form_data = $('#pincode_form').serialize();
                var pincode = $('#pincode').val();
                $.ajax({
                    url: " {{ route('checkPincode') }} ",
                    type: 'POST',
                    data: { "_token":"{{ csrf_token() }}", pincode:pincode },
                    success: function(result)
                    {
                        if (result == '1')
                        {
                            iziToast.success({
                                title: 'Product Available for this City',
                                position: "topRight",
                            });
                        }
                        else
                        {
                            iziToast.error({
                                title: 'Product not available for this City',
                                position: "topRight",
                            });
                        }
                        
                    }
                });
            });

        });

        function changeImage(objs)
        {
            var colorId = $(objs).val();
            // alert(colorId);
            var productId = {{ $productDetail->id }}
            $.ajax({
                url: " {{ route('colorWiseProduct') }} ",
                type: 'POST',
                data: { "_token":"{{ csrf_token() }}", color_id:colorId, product_id:productId },
                success: function(result)
                {
                    if (result.product_image != '')
                    {
                        var url = "{{ asset('uploads/product/') }}/"+result.product_image;
                        $('#product-img > img').attr('src',url);
                    }
                    else
                    {
                        // iziToast.error({
                        //     title: 'Product Image not found',
                        //     position: "topRight",
                        // });
                    }
                }
            });
        }

        $("#pincode_form").validate({
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
            rules: {
                pincode: {
                    required: true,
                    digits:true,
                    minlength:5,
                    maxlength:5,
                },
            },
            messages: {
                pincode: {
                    required: "Pincode Required",
                    maxlength: "pincode must be no more than 5 characters",
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    </script>

@endsection
