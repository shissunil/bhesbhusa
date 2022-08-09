@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="#">Home</a></li>

                <li class="breadcrumb-item active">Whishlist</li>

            </ol>

        </div>

    </section>

    <section class="profile-page">

        <div class="container">

            <div class="row">

                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">

                    @include('front.layout.profile-sidebar')

                </div>

                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">

                    <h3>Wishlist</h3>

                    <div class="white-bg whishlist">

                        @if (count($wishList) > 0)

                            <ul class="listing">

                                @foreach ($wishList as $wishListItem)

                                    <li>

                                        <div class="product-box">

                                            <a href="{{ route('productDetails', Crypt::encrypt($wishListItem->productData->id)) }}">
                                                <div class="pro-img">
                                                    <div class="no-img">
                                                        <img src="{{ $wishListItem->productData->product_image != '' ? $wishListItem->productData->product_image : asset('assets/front/images/no-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="star-rating">{{ $wishListItem->productData->rating }}<i
                                                            class="far fa-star"></i></div>
                                                </div>
                                            </a>

                                            <div class="details">

                                                <div class="top-bar">

                                                    <div class="brand">
                                                        {{ $wishListItem->productData->brand_name }}
                                                    </div>

                                                    <div class="off-rate">
                                                        {{ $wishListItem->productData->discount }}
                                                    </div>

                                                </div>

                                                <a href="{{ route('productDetails', Crypt::encrypt($wishListItem->productData->id)) }}">
                                                    <p>{{ $wishListItem->productData->product_name }}</p>
                                                </a>

                                                <div class="bottom-price">

                                                    <div class="price">

                                                        @if ($wishListItem->productData->discount_price != '')
                                                            {{ $wishListItem->productData->discount_price }}
                                                        @else
                                                            {{ $wishListItem->productData->price }}
                                                        @endif

                                                        @if ($wishListItem->productData->discount_price != '')
                                                            <span>{{ $wishListItem->productData->price }}</span>
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="button-list">

                                                    <a href="javascript:;" data-id="{{ $wishListItem->productData->id }}"
                                                        class="del removeFromWishlisht">
                                                        <img src="{{ asset('assets/front/images/delete.svg') }}" alt="">
                                                    </a>

                                                    <a href="{{ route('productDetails', Crypt::encrypt($wishListItem->productData->id)) }}" class="addtocart btn_add_to_cart">Add To Cart</a>

                                                </div>

                                            </div>

                                        </div>

                                    </li>

                                @endforeach

                            </ul>

                        @else

                            <div class="text-center text-muted">You have no items in wishlist.</div>

                        @endif



                        <div>
                            @if (count($wishList) > 0)
                                {{ $wishList->links('vendor.pagination.default') }}
                            @endif
                        </div>



                    </div>

                </div>

            </div>

        </div>

    </section>


@endsection


@section('footer')

    <script type="text/javascript">
        $(document).ready(function() {
            $(".removeFromWishlisht").click(function() {
                var productId = $(this).data('id');
                var is_favorite = 0;
                var _token = "{{ csrf_token() }}";
                $form = $("<form method='post' action='{{ route('addRemoveWishlist') }}'></form>");
                $form.append('<input type="hidden" name="_token" value="' + _token + '">');
                $form.append('<input type="hidden" name="product_id" value="' + productId + '">');
                $form.append('<input type="hidden" name="is_favorite" value="' + is_favorite + '">');
                // console.log($form);
                $('body').append($form);
                $form.submit();
            });
        });
    </script>

@endsection
