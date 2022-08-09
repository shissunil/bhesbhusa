<!-- Preloader  -->

<div class="loader bg-dark">

    <div class="loader-inner ball-scale-ripple-multiple ball-scale-ripple-multiple-color">

        <img src="{{ asset('assets/front/images/loader.svg') }}" alt="">

    </div>

</div>

<style>
  .counter {
    position: absolute;
    top: -11px;
    left: auto;
    z-index: 2;
    padding: 4px 8px;
    margin-left: -11px;
    font-size: 10px;
    color: #fff;
    background-color: #000000;
    border-radius: 50%;
}
</style>

<!-- /End Preloader  -->

<header>

    <nav class="navbar navbar-expand-lg navbar-light">

        <a class="navbar-brand" href="{{ route('index') }}"><img
                src="{{ asset('assets/front/images/bb-header.svg') }}" alt=""></a>

        <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

      </button> -->



        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav mr-auto">

                @php
                    $parentCategory = getCategoryData();
                @endphp

                @if (count($parentCategory) > 0)

                    @foreach ($parentCategory as $mainCategory)

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $mainCategory->supercategory_name }}
                            </a>

                            <div class="megamenu">

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                    <div class="colume-list">

                                        @if ($mainCategory->superSubCategoryList)

                                            @foreach ($mainCategory->superSubCategoryList as $childCategory)

                                                <div class="col">

                                                    <ul class="cat-list">

                                                        <li class="title"><a
                                                                href="#">{{ $childCategory->supersub_cat_name }}</a>
                                                        </li>

                                                        @if ($childCategory->categoryList)

                                                            @foreach ($childCategory->categoryList as $category)

                                                                <li><a class="font-weight-bold" href="#">
                                                                        {{ $category->name }}</a></li>

                                                                @if ($category->subCategoryList)

                                                                    @foreach ($category->subCategoryList as $subCategory)

                                                                        <li><a
                                                                                href="{{ route('productList', Crypt::encrypt($subCategory->id)) }}">{{ $subCategory->name }}</a>
                                                                        </li>

                                                                    @endforeach

                                                                @endif
                                                                <li><hr></li>
                                                            @endforeach

                                                        @endif
                                                    </ul>

                                                </div>

                                            @endforeach

                                        @endif

                                        {{-- <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Bottomwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li class="title"><a href="#">Innerwear & Sleepwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div> 

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Footwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li ><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li ><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div> --}}

                                    </div>

                                </div>

                            </div>

                        </li>

                    @endforeach

                @endif

                {{-- <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

              Women

            </a>

            <div class="megamenu">

              <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <div class="colume-list">

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Bottomwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li class="title"><a href="{{ route('shop') }}">Innerwear & Sleepwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div> 

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Footwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li ><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                </div>

              </div>

            </div>

          </li>

          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

              Kids

            </a>  

            <div class="megamenu">

              <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <div class="colume-list">

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li ><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="{{ route('shop') }}">Bottomwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li class="title"><a href="{{ route('shop') }}">Innerwear & Sleepwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div> 

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Footwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                </div>

              </div>

            </div>

          </li> 

          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

              Home & Living

            </a> 

            <div class="megamenu">

              <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <div class="colume-list">

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Bottomwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li class="title"><a href="{{ route('shop') }}">Innerwear & Sleepwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div> 

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Footwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li ><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                </div>

              </div>

            </div> 

          </li> 

          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

              Beauty

            </a>

            <div class="megamenu">

              <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <div class="colume-list">

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Bottomwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li class="title"><a href="{{ route('shop') }}">Innerwear & Sleepwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div> 

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Footwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                      <li class="title"><a href="{{ route('shop') }}">Plus Size</a></li>  

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="#">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                  <div class="col">

                    <ul class="cat-list">

                      <li class="title"><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li>

                      <li ><a href="{{ route('shop') }}">Topwear</a></li>

                      <li ><a href="{{ route('shop') }}">T-Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Casual Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Formal Shirts</a></li>

                      <li ><a href="{{ route('shop') }}">Sweatshirts</a></li> 

                      <div class="desktop-hrLine"></div>

                      <li class="title"><a href="{{ route('shop') }}">Indian & Festive Wear</a></li>                      

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                      <li ><a href="{{ route('shop') }}">Sherwanis</a></li>  

                      <li ><a href="{{ route('shop') }}">Kurtas & Kurta Sets</a></li>                      

                    </ul>

                  </div>

                </div>

              </div>

            </div>  

          </li> --}}

            </ul>

        </div>

        <div class="search-view collapse" id="page-search">

            <form action="{{ route('searchProduct') }}" method="get">

                <input type="text" class="form-control form-control-light search_keyword" name="keyword"
                    placeholder="Search Here..." value="{{ $_GET['keyword'] ?? '' }}" required>

                <button class="btn" type="submit"><img src="{{ asset('assets/front/images/search.svg') }}"
                        alt=""></button>

            </form>

        </div>

        <div class="icon-menu ml-auto">

            <div class="icon-list">

                <ul>

                    <li class="search-icon">
                      <a href="#page-search" data-toggle="collapse" title="">
                        <img src="{{ asset('assets/front/images/search.svg') }}" alt="">
                      </a>
                    </li>

                    <li class="notification">
                      <a href="{{ route('notifications') }}" title="">
                        <img src="{{ asset('assets/front/images/notification.svg') }}" alt="">
                        <!-- <span></span> -->
                      </a>
                    </li>

                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">

                            <img src="{{ asset('assets/front/images/profile-icon.svg') }}" alt=""><span
                                class="text">Profile</span>

                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenu1">

                            @auth

                                <div class="login"><a href="{{ route('logout') }}" class="btn theme-btn"
                                        title="">Logout</a></div>

                            @else

                                <div class="login"><a href="{{ route('login') }}" class="btn theme-btn"
                                        title="">Login</a></div>

                            @endauth

                            <ul class="menu">

                                <li><a href="{{ route('wishlist') }}" title=""><span><img
                                                src="{{ asset('assets/front/images/fav.svg') }}"
                                                alt=""></span>Wishlist</a></li>

                                <li><a href="{{ route('profile') }}" title=""><span><img
                                                src="{{ asset('assets/front/images/profile-icon.svg') }}"
                                                alt=""></span>My Profile</a></li>

                                <li><a href="{{ route('my_orders') }}" title=""><span><img
                                                src="{{ asset('assets/front/images/bag.svg') }}"
                                                alt=""></span>Orders</a></li>

                                <li><a href="{{ route('notifications') }}" title=""><span><img
                                                src="{{ asset('assets/front/images/notification.svg') }}"
                                                alt=""></span>Notification</a></li>

                            </ul>

                        </div>

                    </li>

                    <li>
                      <a href="{{ route('wishlist') }}" title="Wishlist">
                        <img src="{{ asset('assets/front/images/fav.svg') }}" alt="">
                        <span class="text">Wishlist</span>
                      </a>
                    </li>

                    <li>
                      @php
                        $cartCount = 0;
                        if(Auth::user()){
                          $cartCount = cartCount(Auth::user()->id);
                        }
                      @endphp
                        <a href="{{ route('cart') }}" title="Cart">
                            <img src="{{ asset('assets/front/images/bag.svg') }}" alt="Cart">                            
                            <span class="text">Bag</span>                            
                        </a>
                        <span class="counter">{{ $cartCount }}</span>
                    </li>

                </ul>

            </div>

        </div>

    </nav>

</header>
