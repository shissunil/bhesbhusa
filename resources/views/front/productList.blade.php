@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

                @if ($bradcumbData->super_category_data)

                    <li class="breadcrumb-item"><a
                            href="#">{{ ucwords($bradcumbData->super_category_data->supercategory_name) }}</a>
                    </li>

                @endif

                @if ($bradcumbData->super_sub_category_data)

                    <li class="breadcrumb-item"><a
                            href="#">{{ ucwords($bradcumbData->super_sub_category_data->supersub_cat_name) }}</a>
                    </li>

                @endif

                @if ($bradcumbData->category_data)

                    <li class="breadcrumb-item"><a href="#">{{ ucwords($bradcumbData->category_data->name) }}</a>
                    </li>

                @endif

                @if ($bradcumbData)

                    <li class="breadcrumb-item active">{{ ucwords($bradcumbData->name) }}</li>

                @endif

            </ol>

            <div class="page-title">
                <h1>{{ ucwords($bradcumbData->name) ?? '' }}</h1><span> {{ $productList->total() }} items</span>
            </div>

        </div>

    </section>

    <section class="shop-list-view">

        <div class="inner-shop-view">

            <div class="shop-listing">

                <div class="sidebar-main">

                    <h4 class="filter-title">Filters</h4>

                    <x-filter :categoryId="$bradcumbData->category_data->id??0" :subCategoryId="$subCategoryId" />

                </div>

                <div class="shop-product-list-view">

                    <div class="sort-option">

                        <div class="title-bar">Category :
                            {{ ucwords($bradcumbData->super_category_data->supercategory_name ?? '') }}</div>

                        <div class="sort-by">

                            <select name="sortby" class="form-control sort_by" onchange="filterProducts()">

                                <option value="2">Price - high to low</option>

                                <option value="4">Price - low to high</option>

                                <option value="1">New</option>

                                <option value="3">Popularity</option>

                            </select>

                        </div>

                    </div>

                    <div class="listof-product" id="product_list">

                        <ul class="product">

                            @if (count($productList) > 0)

                                @foreach ($productList as $product)

                                    <li>

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
                                                            <input type="checkbox"
                                                                value="{{ $product->favorite == 1 ? 0 : 1 }}"
                                                                data-id="{{ $product->id }}" name="checkvalue"
                                                                class="wishlist_check"
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

                                    </li>

                                @endforeach

                            @endif

                        </ul>

                        <div>
                            @if (count($productList) > 0)
                                {{ $productList->links('vendor.pagination.default') }}
                            @endif
                        </div>

                    </div>

                </div>

            </div>


        </div>

    </section>

@endsection

@section('footer')

    <script>
        function filterProducts() {

            var sub_category_id = [];
            $('.sub_category:checked').each(function() {
                sub_category_id.push($(this).val());
            });
            sub_category_id = sub_category_id.join(",");

            var color_id = [];
            $('.color_id:checked').each(function() {
                color_id.push($(this).val());
            });
            color_id = color_id.join(",");

            var brand_id = [];
            $('.brand_id:checked').each(function() {
                brand_id.push($(this).val());
            });
            brand_id = brand_id.join(",");

            var priceRange = $("#priceRange").val();
            var price = priceRange.split(" - ");
            var min_price = price[0];
            var max_price = price[1];

            var sort = $(".sort_by").val();

            var keyword = $(".search_keyword").val();

            setTimeout(() => {

                $.ajax({
                    url: "{{ route('searchFilterProductList') }}",
                    method: 'POST',
                    data: {
                        sub_category_id: sub_category_id,
                        color_id: color_id,
                        brand_id: brand_id,
                        min_price: min_price,
                        max_price: max_price,
                        sort: sort,
                        keyword: keyword,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // console.log(response);
                        $("#product_list").html(response);
                    }
                });

            }, 1000);
        }

       
    </script>

@endsection
