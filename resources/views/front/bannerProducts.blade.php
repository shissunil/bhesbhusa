@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <div class="page-title">
                <h1> items</span>
            </div>

        </div>

    </section>

    <section class="shop-list-view">

        <div class="inner-shop-view">

            <div class="shop-listing">

                <div class="sidebar-main">

                    <h4 class="filter-title">Filters</h4>

                    {{-- <x-filter :categoryId="$bradcumbData->category_data->id??0" :subCategoryId="$subCategoryId" /> --}}
                    <div class="side-bar-filtter">   

                        <div class="panel">       

                            <div class="panel-head" data-toggle="collapse" href="#categories" role="button" aria-expanded="true"
                                aria-controls="price-rang">Categories</div>

                            <div class="collapse show" id="categories">

                                <div class="panel-body custom-scroll-div">

                                    <ul>

                                        @if (count($data['subCategoryList']) > 0)

                                            @foreach ($data['subCategoryList'] as $subCategory)

                                                <li>

                                                    <div class="common-check">

                                                        <label class="checkbox">
                                                            <div>{{ ucwords($subCategory->name) }}</div>
                                                            <div class="number">
                                                                {{-- (59163) --}}
                                                            </div>
                                                            <input type="checkbox" class="sub_category" name="subCategoryId[]" value="{{ $subCategory->id }}" onchange="filterProducts()" {{ (isset($subCategoryId) && ($subCategoryId==$subCategory->id)) ? 'checked' : ''}} >
                                                            <span class="checkmark"></span>
                                                        </label>

                                                    </div>

                                                </li>

                                            @endforeach

                                        @endif

                                    </ul>

                                </div>

                            </div>

                        </div>

                        <div class="panel">

                            <div class="panel-head" data-toggle="collapse" href="#price-rang" role="button" aria-expanded="true"
                                aria-controls="price-rang">Price Range</div>

                            <div class="collapse show" id="price-rang">

                                <div class="panel-body custom-scroll-div">

                                    <div class="slider-box">

                                        <label for="priceRange">Price Range:</label>

                                        <input type="text" id="priceRange" readonly>

                                        <div id="price-range" class="slider"></div>

                                    </div>

                                    {{-- <h5>12,825 products found</h5> --}}

                                </div>

                            </div>

                        </div>

                        <div class="panel">

                            <div class="panel-head" data-toggle="collapse" href="#color" role="button" aria-expanded="false"
                                aria-controls="color">Color</div>

                            <div class="collapse show" id="color">

                                <div class="panel-body custom-scroll-div">

                                    <ul>

                                        @if (count($data['colorList']) > 0)

                                            @foreach ($data['colorList'] as $color)

                                                <li>

                                                    <div class="common-check">

                                                        <label class="checkbox">
                                                            <div class="color">
                                                                <span style="background: {{ $color->code }};"></span>
                                                                {{ ucwords($color->color) }}
                                                            </div>
                                                            <div class="number">
                                                                {{-- (100) --}}
                                                            </div>
                                                            <input type="checkbox" name="colorId[]" value="{{ $color->id }}" class="color_id" onchange="filterProducts()">
                                                            <span class="checkmark"></span>
                                                        </label>

                                                    </div>

                                                </li>

                                            @endforeach

                                        @endif

                                    </ul>

                                </div>

                            </div>

                        </div>

                        <div class="panel">

                            <div class="panel-head" data-toggle="collapse" href="#brand" role="button" aria-expanded="false"
                                aria-controls="brand">Brand</div>

                            <div class="collapse show" id="brand">

                                <div class="panel-body custom-scroll-div">

                                    {{-- <h5>Top Picks</h5> --}}

                                    <ul>

                                        @if (count($data['brandList']) > 0)

                                            @foreach ($data['brandList'] as $brand)

                                                <li>

                                                    <div class="common-check">

                                                        <label class="checkbox">
                                                            <div>{{ ucwords($brand->name) }}</div>
                                                            <div class="number">
                                                                {{-- (100) --}}
                                                            </div>
                                                            <input type="checkbox" name="brandId[]" value="{{ $brand->id }}" class="brand_id" onchange="filterProducts()">
                                                            <span class="checkmark"></span>
                                                        </label>

                                                    </div>

                                                </li>

                                            @endforeach

                                        @endif

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="shop-product-list-view">

                    <div class="sort-option">

                        <div class="title-bar">Category :</div>

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
                            @php
                                $productIds = '';
                            @endphp
                            @if (count($productList) > 0)

                                @foreach ($productList as $product)
                                    @php
                                        $productIds .= $product->id.',';
                                    @endphp
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
                            <input type="hidden" name="banner_id" id="banner_id" value="{{ $bannerId }}">
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
            
            var banner_id = $("#banner_id").val();

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
                        banner_id: banner_id,
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
    <script>
        /* ======== Price Of Range  ===========*/
        $(function() {
            $("#price-range").slider({
                step: 1,
                range: true,
                min: {{ $data['priceRange']['minPrice'] ?? 0 }},
                max: {{ $data['priceRange']['maxPrice'] ?? 0 }},
                values: [{{ $data['priceRange']['minPrice'] ?? 0 }},
                    {{ $data['priceRange']['maxPrice'] ?? 0 }}
                ],
                slide: function(event, ui) {
                    $("#priceRange").val(ui.values[0] + " - " + ui.values[1]);                
                }
            });
            $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values",1));
            

        });
    </script>
@endsection
