@extends('front.layout.main')

@section('section')
    <section class="category-list d-sm-block d-md-none">
        <div class="container-fluid">
            <!-- <h2 class="page-title">Our <span>Categories</span></h2>  -->
            <ul class="five-cat">
                @php
                    $parentCategory = getCategoryData();
                @endphp
                @if (count($parentCategory) > 0)
                    @foreach ($parentCategory as $mainCategory)
                        <li>
                            {{-- <a href="{{ route('categoryProductList', Crypt::encrypt($mainCategory->id)) }}" title="{{ $mainCategory->supercategory_name }}" class="modal-toggle"> --}}
                            <a href="#" title="{{ $mainCategory->supercategory_name }}" class="modal-toggle" data-id="{{ $mainCategory->id }}" data-name="{{ $mainCategory->supercategory_name }}">
                                <div class="cat-box">
                                    <div class="img"><img src="{{ $mainCategory->supercategory_image }}" alt="{{ $mainCategory->supercategory_name }}" /></div>
                                    <h4>{{ $mainCategory->supercategory_name }}</h4>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </section>
    <section class="home-banner">
        <div class="banner-slider">

            @if (count($headerBannerList) > 0)
                @foreach ($headerBannerList as $bannerHeader)
                    <div class="item">
                        <a href="{{ route('bannerProductList', Crypt::encrypt($bannerHeader->id)) }}">
                            <img src="{{ $bannerHeader->banner_image }}" alt="" style="width: 100% !important;">
                        </a>
                    </div>
                @endforeach
            @endif

        </div>

    </section>
    <section class="traiding-list">
        <div class="container-fluid">
            <h2 class="page-title">Best <span>Deals</span></h2>
            <ul class="five-column">
                @if (count($bannerList) > 0)
                    @foreach ($bannerList as $banner)
                        @if ($banner->banner_type == '2')
                            <li>
                                <a href="{{ route('bannerProductList', Crypt::encrypt($banner->id)) }}" title="">
                                    <div class="img"><img src="{{ $banner->banner_image }}" alt="{{ $banner->image }}" style="width: 100%;"></div>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </section>
    <section class="new-arrival">
        <div class="container-fluid">
            <h2 class="page-title">New <span>Arrivals</span></h2>
            <ul class="four-column">
                @if (count($bannerList) > 0)
                    @foreach ($bannerList as $banner)
                        @if ($banner->banner_type == '3')
                            <li>
                                <a href="{{ route('bannerProductList', Crypt::encrypt($banner->id)) }}" title="">
                                    <div class="img"><img src="{{ $banner->banner_image }}" alt="{{ $banner->image }}" style="width: 100%;"></div>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </section>

    <section class="traiding-list">
        <div class="container-fluid">
            <h2 class="page-title">Bhesbhusa <span>Exclusive</span></h2>
            <ul class="five-column">
                @if (count($bannerList) > 0)
                    @foreach ($bannerList as $banner)
                        @if ($banner->banner_type == '4')
                            <li>
                                <a href="{{ route('bannerProductList', Crypt::encrypt($banner->id)) }}" title="">
                                    <div class="img"><img src="{{ $banner->banner_image }}" alt="{{ $banner->image }}" style="width: 100%;"></div>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </section>

    <section class="traiding-list">
        <div class="container-fluid">
            <h2 class="page-title">Trending in <span>Men</span></h2>
            <ul class="five-column">
                @if (count($bannerList) > 0)
                    @foreach ($bannerList as $banner)
                        @if ($banner->banner_type == '5')
                            <li>
                                <a href="{{ route('bannerProductList', Crypt::encrypt($banner->id)) }}" title="">
                                    <div class="img"><img src="{{ $banner->banner_image }}" alt="{{ $banner->image }}" style="width: 100%;"></div>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </section>


    <section class="traiding-list">
        <div class="container-fluid">
            <h2 class="page-title">Trending in <span>Women</span></h2>
            <ul class="five-column">
                @if (count($bannerList) > 0)
                    @foreach ($bannerList as $banner)
                        @if ($banner->banner_type == '6')
                            <li>
                                <a href="{{ route('bannerProductList', Crypt::encrypt($banner->id)) }}" title="">
                                    <div class="img"><img src="{{ $banner->banner_image }}" alt="{{ $banner->image }}" style="width: 100%;"></div>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </section>
    <section class="offer-images">

        <div class="container-fluid">

            <div class="row">

                @if (count($footerBannerList) > 0)

                    @foreach ($footerBannerList as $bannerFooter)

                        <div class="col-xl-6 col-lg-6 col-md-12">

                            <a href="#"><img src="{{ $bannerFooter->banner_image }}" alt=""></a>

                        </div>

                    @endforeach

                @endif

                {{-- <div class="col-xl-6 col-lg-6 col-md-12">

              <a href="#"><img src="{{ asset('assets/front/images/offer2.jpg') }}" alt=""></a>

            </div> --}}

            </div>

        </div>

    </section>


    <!-- Category Modal -->
    <div class="category-modal">
        <div class="modal-wrapper modal-transition">
            <div class="cate-header">
                <button class="modal-close modal-toggle"><i class="fas fa-chevron-left"></i></button>
                <h2 class="modal-heading" id="super-category-title">Women</h2>
            </div>
            <div class="cate-modal-body" id="category-html-modal">
                <div id="category-list">
                    <ul class="list-unstyled">
                        <li>
                            <h3><a href="#">Tasks</a></h3>
                            <ul>
                                <li>
                                    <a href="#">DrillDown (active)</a>
                                    <ul>
                                        <li>
                                            <a href="#">Overdues</a>
                                            <ul>
                                                <li><a href="#">Today's tasks</a></li>
                                                <li><a href="#">Urgent</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="shop.php">Today's tasks</a></li>
                                        <li><a href="#">Urgent</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">Overdues</a>
                                    <ul>
                                        <li><a href="shop.php">Today's tasks</a></li>
                                        <li><a href="shop.php">Urgent</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <h3><a href="#">Tasks</a></h3>
                            <ul>
                                <li>
                                    <a href="#">DrillDown (active)</a>
                                    <ul>
                                        <li><a href="shop.php">Today's tasks</a></li>
                                        <li><a href="shop.php">Urgent</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">Overdues</a>
                                    <ul>
                                        <li><a href="shop.php">Today's tasks</a></li>
                                        <li><a href="shop.php">Urgent</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <h3><a href="#">Favourites</a></h3>
                            <ul>
                                <li><a href="shop.php">Global favs</a></li>
                                <li><a href="shop.php">My favs</a></li>
                                <li><a href="shop.php">Team favs</a></li>
                                <li><a href="shop.php">Settings</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script type="text/javascript">
        $('.modal-toggle').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#super-category-title').text(name);
            console.log(id);
            $.ajax({
                url: "{{ route('filterCategoryData') }}",
                method: 'POST',
                data: {
                    super_cat_id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // console.log(response);
                    $("#category-html-modal").html(response);
                }
            });
            $('.category-modal').toggleClass('is-visible');
        });
    </script>
@endsection