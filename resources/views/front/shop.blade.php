@extends('front.layout.main')

@section('section')

<section class="inner-page-banner">

    <div class="container-fluid"> 

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="#">Home</a></li>

        <li class="breadcrumb-item"><a href="#">Clothing</a></li>

        <li class="breadcrumb-item"><a href="#">Men Clothing</a></li>

        <li class="breadcrumb-item"><a href="#">Tshirts</a></li>

        <li class="breadcrumb-item active">Men Skinny Fit Tshirt</li>

      </ol> 

      <div class="page-title"><h1>Personal Care</h1><span> 34258 items</span></div>

    </div>

</section>

<section class="shop-list-view">

  <div class="inner-shop-view">        

    <div class="shop-listing">

      <div class="sidebar-main">

          <h4 class="filter-title">Filters</h4>

          <div class="side-bar-filtter">

            <div class="panel">

              <div class="panel-head" data-toggle="collapse" href="#categories" role="button" aria-expanded="true" aria-controls="price-rang">Categories</div>

              <div class="collapse show" id="categories">

                <div class="panel-body custom-scroll-div">

                  <ul>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Tshirts</div><div class="number">(59163)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Belts</div><div class="number">(65)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Blazers</div><div class="number">(310)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Boxers</div><div class="number">(55)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Caps</div><div class="number">(1)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Casual Shoes</div><div class="number">(571)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Coats</div><div class="number">(1)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Cufflinks</div><div class="number">(7)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Flip Flops</div><div class="number">(47)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Formal Shoes</div><div class="number">(250)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Jackets</div><div class="number">(599)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Jeans</div><div class="number">(1594)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>



                  </ul>

                </div>

              </div>

            </div> 

            <div class="panel">

              <div class="panel-head" data-toggle="collapse" href="#price-rang" role="button" aria-expanded="true" aria-controls="price-rang">Price Range</div>

              <div class="collapse show" id="price-rang">

                <div class="panel-body custom-scroll-div">

                  <div class="slider-box">                        

                    <label for="priceRange">Price Range:</label>

                    <input type="text" id="priceRange" readonly>                        

                    <div id="price-range" class="slider"></div>

                  </div>

                  <h5>12,825 products found</h5>

                </div>

              </div>

            </div>  

            <div class="panel">

              <div class="panel-head" data-toggle="collapse" href="#color" role="button" aria-expanded="false" aria-controls="color">Color</div>

              <div class="collapse show" id="color">

                <div class="panel-body custom-scroll-div">

                  <ul>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #000;"></span>Black</div><div class="number">(100)</div><input type="checkbox" checked="checked"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #;"></span>Teal</div><div class="number">(100)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #ff0000;"></span>Red</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #e2e2e2;"></span>Light Gray</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #666;"></span>Drak Gray</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #fff;"></span>White</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #87CEEB;"></span>Sky Blue</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #FFFF00;"></span>Yellow</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #FFC0CB;"></span>Pink</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #d1edf2;"></span>Pale Blue</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div class="color"><span style="background: #00008B;"></span>Dark Blue</div><div class="number">(20)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                  </ul>

                </div>

              </div>

            </div>

            <div class="panel">

              <div class="panel-head" data-toggle="collapse" href="#brand" role="button" aria-expanded="false" aria-controls="brand">Brand</div>

              <div class="collapse show" id="brand">

                <div class="panel-body custom-scroll-div">

                  <h5>Top Picks</h5>

                  <ul>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>H&M</div><div class="number">(100)</div><input type="checkbox" checked="checked"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Tommy Hilfiger</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Nike</div><div class="number">(40)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Puma</div><div class="number">(35)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Adidas</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>                        

                  </ul>

                  <h5>More Option</h5>

                  <ul>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Jack & Jones</div><div class="number">(100)</div><input type="checkbox" checked="checked"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Spyker</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Fling Machine</div><div class="number">(40)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>Levi's</div><div class="number">(35)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>

                    <li>

                      <div class="common-check">

                          <label class="checkbox"><div>United Color of Benetton</div><div class="number">(50)</div><input type="checkbox"><span class="checkmark"></span></label>

                       </div>

                    </li>                        

                  </ul>

                </div>

              </div>

            </div> 

          </div>    

      </div>

      <div class="shop-product-list-view">

          <div class="sort-option">

              <div class="title-bar">Category: Men</div>

              <div class="sort-by">

                <select name="sortby" class="form-control">

                  <option value="">Price - high to low</option>

                  <option value="">Price - low to high</option>

                  <option value="">New</option>

                  <option value="">Popularity</option>

                </select>

              </div>

          </div>

          <div class="listof-product">

            <ul class="product">

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <div class="no-img"><img src="{{ asset('assets/front/images/no-img.png') }}" alt=""></div>

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <img src="{{ asset('assets/front/images/product1.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img"><img src="{{ asset('assets/front/images/product2.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img"><img src="{{ asset('assets/front/images/product1.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <img src="{{ asset('assets/front/images/product2.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <img src="{{ asset('assets/front/images/product1.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>  

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <img src="{{ asset('assets/front/images/product2.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <div class="no-img"><img src="{{ asset('assets/front/images/no-img.png') }}" alt=""></div>

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <img src="{{ asset('assets/front/images/product1.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img"><img src="{{ asset('assets/front/images/product2.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <div class="no-img"><img src="{{ asset('assets/front/images/no-img.png') }}" alt=""></div>

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img">

                          <img src="{{ asset('assets/front/images/product1.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>

                <li>

                    <div class="product-box">

                      <a href="#">

                        <div class="pro-img"><img src="{{ asset('assets/front/images/product2.png') }}" alt="">

                          <div class="star-rating">4.5<i class="far fa-star"></i></div>

                        </div>

                      </a>

                      <div class="details">

                        <div class="top-bar">

                          <div class="brand">Puma</div>

                          <div class="favourite-mark">

                            <label><input type="checkbox" name="checkvalue" class="checkvalue"><span></span></label>

                          </div>                

                        </div>

                        <a href="#"><p>Men Skinny Fit Tshirt</p></a>

                        <div class="bottom-price">

                          <div class="price">899 NR<span>1,099 NR</span></div>

                          <div class="off-rate">20% Off</div>

                        </div>

                      </div>

                    </div>

                </li>                     

            </ul>

            <div class="custom-pagination">

                <ul class="pagination">

                  <li class="page-number prev"><a href="#">Prev</a></li>

                  <li class="page-number"><a href="#">1</a></li>

                  <li class="page-number active"><a href="#">2</a></li>

                  <li class="page-number"><a href="#">3</a></li>

                  <li class="page-number"><a href="#">4</a></li>

                  <li class="page-number"><a href="#">5</a></li>

                  <li class="page-number prev"><a href="#">Next</a></li>

                </ul>

            </div>

          </div>

      </div>

    </div>
    

  </div>  

</section>

@endsection