<ul class="product">

    @if (count($productList) > 0)

        @foreach ($productList as $product)

            <li>

                <div class="product-box">

                    <a href="{{ route('productDetails', Crypt::encrypt($product->id)) }}">

                        <div class="pro-img">

                            <div class="no-img">
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
                                    <input type="checkbox" value="{{ $product->favorite == 1 ? 0 : 1 }}"
                                        data-id="{{ $product->id }}" name="checkvalue" class="wishlist_check"
                                        {{ $product->favorite == 1 ? 'checked' : '' }}
                                        onclick="addRemoveWishlist({{ $product->id }},{{ $product->favorite == 1 ? 0 : 1 }})">
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

    @else

        <li>
            <p class="text-center"> No products found.</p>
        </li>

    @endif
    <input type="hidden" name="banner_id" id="banner_id" value="{{ $bannerId }}">
</ul>

@if (count($productList) > 0)
    <div>
        {{ $productList->links('vendor.pagination.default') }}
    </div>
@endif


<script>
    $(document).on('click', ".pagination a", function(e) {
        e.preventDefault();
        var targetEl = e.target;
        // console.log(e.target);
        $('li.page-number').removeClass('active');
        // $(targetEl).parent('li').addClass('active');

        var page = $(targetEl).attr('href').split('page=')[1];
        console.log(page);

        filterProducts(page);

        // setTimeout(() => {
        //     filterProducts(page);
        // }, 1000);


    });

    function filterProducts(page) {

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
                _token: "{{ csrf_token() }}",
                page: page
            },
            success: function(response) {
                // console.log(response);
                $("#product_list").html(response);
            }
        });


    }
</script>
