@extends('front.layout.main')

@section('section')

    <style>
        .main-price {
            color: #9c9c9c;
            font-size: 13px;
            font-weight: normal;
            margin: 0 10px;
            position: relative;
            text-decoration-line: line-through;
        }

        .off-rate {
            color: #05D89E;
            font-size: 14px;
        }

        .cart-wishlist {
            position: absolute;
            right: 35px;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 30px;
        }

        .coupon_desc {
            font-size: 14px;
            /* padding-left: 30px; */
            color: #9c9c9c;
        }

        .coupon_applied {
            color: #04c6d4;
            font-weight: bold;
        }

        .coupon_savings {
            color: #05D89E;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .coupon_info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 5px 12px;
        }

    </style>

    <section class="shoping-cart">

        <div class="container">

            @if (count($data) > 0)

                <form id="msform" method="post">

                    @csrf

                    <!-- progressbar -->

                    <ul id="progressbar">

                        <li class="active" id="account">
                            <div class="img"><i class="fas fa-shopping-bag"></i></div><span>Bag</span>
                        </li>

                        <li id="personal">
                            <div class="img"><i class="fas fa-home"></i></div><span>Address</span>
                        </li>

                        <li id="payment">
                            <div class="img"><i class="far fa-credit-card"></i></div><span>Payment</span>
                        </li>

                    </ul> <!-- fieldsets -->

                    <fieldset id="cartTab">

                        <div class="row">

                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">

                                <div class="cart-head">

                                    <div class="item-select">

                                        <div class="common-check">

                                            <label class="checkbox">
                                                <span id="selected_items">
                                                    0
                                                </span>
                                                /
                                                <span id="total_itms">
                                                    {{ isset($data['cartDetail']) ? count($data['cartDetail']) : 0 }}
                                                </span>
                                                ITEMS
                                                SELECTED
                                                <input type="checkbox" id="select_all_items">
                                                <span class="checkmark"></span>
                                            </label>

                                        </div>

                                    </div>

                                    <div class="remove-whi">

                                        <a href="javascript:;" class="remove_from_cart" title="Remove from cart">REMOVE</a>

                                        <a href="javascript:;" class="move_to_wishlist" title="">MOVE TO WHISHLIST</a>

                                    </div>

                                </div>

                                <div class="bag-product">

                                    @if (count($data['cartDetail']) > 0)

                                        @foreach ($data['cartDetail'] as $cartItem)

                                            <div class="cart-list">

                                                <div class="img">

                                                    <a
                                                        href="{{ route('productDetails', Crypt::encrypt($cartItem->product_id)) }}">
                                                        <img src="{{ $cartItem->productData->product_image != '' ? $cartItem->productData->product_image : asset('assets/front/images/product1.png') }}"
                                                            alt="">
                                                    </a>

                                                    <div class="common-check">

                                                        <label class="checkbox">
                                                            <input type="checkbox" class="cart_item" name="cart_id"
                                                                value="{{ $cartItem->id }}">
                                                            <span class="checkmark"></span>
                                                        </label>

                                                    </div>

                                                </div>

                                                <div class="details">

                                                    <div class="delete">

                                                        <a href="javascript:;" class="removeItem"
                                                            data-id="{{ $cartItem->id }}">
                                                            <i class="far fa-trash-alt"></i>
                                                        </a>

                                                        <div class="favourite-mark cart-wishlist">
                                                            <label>
                                                                <input type="checkbox"
                                                                    value="{{ $cartItem->productData->favorite == 1 ? 0 : 1 }}"
                                                                    data-id="{{ $cartItem->productData->id }}"
                                                                    name="checkvalue" class="wishlist_check"
                                                                    {{ $cartItem->productData->favorite == 1 ? 'checked' : '' }}>
                                                                <span></span>
                                                            </label>
                                                        </div>

                                                    </div>

                                                    <h6>{{ $cartItem->productData->product_name }}</h6>

                                                    <p class="text-muted">Sold by:
                                                        {{ $cartItem->productData->sold_by }}
                                                    </p>

                                                    <div class="size-qty">

                                                        <select style="-webkit-appearance: none;text-indent: 1px;">
                                                            <option>Size: {{ $cartItem->size }}</option>
                                                        </select>

                                                        <select style="-webkit-appearance: none;text-indent: 1px;">
                                                            <option>Qty: {{ $cartItem->qty }}</option>
                                                        </select>

                                                    </div>

                                                    <h6>
                                                        <b>{{ ($cartItem->productData->discount_price) ? $cartItem->productData->discount_price : $cartItem->productData->price }}</b>
                                                        @if ($cartItem->productData->discount_price)
                                                            <span
                                                                class="main-price">{{ $cartItem->productData->price }}</span>
                                                            <span
                                                                class="off-rate">{{ $cartItem->productData->discount }}</span>
                                                        @endif
                                                    </h6>

                                                    <p><span class="text-muted">Delivers by:</span>
                                                        <b>{{ $cartItem->delivery_days }}</b>
                                                    </p>

                                                </div>

                                            </div>

                                        @endforeach

                                    @endif

                                </div>

                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">

                                <div class="cart-summary">

                                    <div class="apply-promo">

                                        <h4>Coupons</h4>


                                        <div class="promo-details">

                                            @if ($data['coupon_data'])

                                                <div class="text">

                                                    <img src="{{ asset('assets/front/images/promo.svg') }}" alt="">

                                                    <span class="coupon_applied">
                                                        {{ $data['coupon_data']['offer_code'] }}
                                                    </span>
                                                    <br>
                                                    <span class="coupon_desc">
                                                        {{ $data['coupon_data']['offer_description'] }}
                                                    </span>
                                                </div>

                                                <div class="button coupon_info">
                                                    <div class="coupon_savings">
                                                        {{ $data['coupon_data']['saved_amount'] }} </div>
                                                    <button type="button" class="btn border-btn" data-toggle="modal"
                                                        data-target="#apply-promo">
                                                        Change Coupon
                                                    </button>
                                                </div>

                                            @else

                                                <div class="text">
                                                    <img src="{{ asset('assets/front/images/promo.svg') }}" alt="">Apply
                                                    Coupons
                                                </div>

                                                <div class="button">
                                                    <button type="button" class="btn border-btn" data-toggle="modal"
                                                        data-target="#apply-promo">
                                                        Apply
                                                    </button>
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                    <div class="priceHeader">PRICE DETAILS ({{ count($data['cartDetail']) }} Items)
                                    </div>

                                    <ul>

                                        <li>
                                            <div class="title text-muted">Cart total</div>
                                            <div class="price"><b>{{ $data['cart_summary']['cart_total'] }}</b>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Product Discount</div>
                                            <div class="price">{{ $data['cart_summary']['product_discount'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Coupon Discount</div>
                                            <div class="price">{{ $data['cart_summary']['coupon_discount'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Shipping charges</div>
                                            <div class="price">{{ $data['cart_summary']['shipping_charge'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title"><b>Total Amount</b></div>
                                            <div class="price">
                                                <b>{{ $data['cart_summary']['payable_amount'] }}</b>
                                            </div>
                                        </li>

                                    </ul>

                                </div>

                                <div class="place-order">

                                    <input type="button" class="next btn theme-btn cart_proceed"
                                        value="Proceed to checkout" />

                                </div>
                                <b>*All prices are inclusive of tax</b>
                            </div>

                        </div>

                    </fieldset>

                    <fieldset id="addressTab">

                        <div class="row">

                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">

                                <div class="cart-head">

                                    <h5>Select Delivery Address</h5>

                                    <a href="#" class="btn border-btn" data-toggle="modal" data-target="#add-new-address"
                                        data-backdrop="static">Add Address</a>

                                </div>

                                @if (count($userAddressList) > 0)

                                    @php
                                        $defaultAddress = $userAddressList->firstWhere('is_default', 1);
                                        $otherAddress = $userAddressList->where('is_default', 0);
                                        
                                    @endphp

                                    @if ($defaultAddress)

                                        <div class="address-title">Default Address</div>

                                        <div class="default-address">

                                            <div class="form-group box-shadow">

                                                <label class="radio-box">

                                                    <b>{{ $defaultAddress->contact_name }}</b>
                                                    <h5>{{ $defaultAddress->save_as == 0 ? 'Home' : 'Work' }}</h5>

                                                    <p>
                                                        {{ $defaultAddress->address . ',' . $defaultAddress->locality . ',' . $defaultAddress->city . ',' . $defaultAddress->state . '-' . $defaultAddress->pincode }}
                                                    </p>

                                                    <p><span class="text-muted">Mobile:
                                                        </span>{{ $defaultAddress->mobile_no }}</p>
                                                    <p><span class="text-muted">Email:
                                                        </span>{{ $defaultAddress->email }}</p>

                                                    <input type="radio" name="address_id" class="address_id"
                                                        value="{{ $defaultAddress->id }}" checked />
                                                    <span class="checkmark"></span>

                                                </label>

                                                <div class="edit-delete">

                                                    <a href="#" class="btn border-btn" data-toggle="modal"
                                                        data-target="#delete-modal" data-backdrop="static">Remove</a>

                                                    <a href="#" class="btn border-btn" data-toggle="modal"
                                                        data-target="#edit-address" data-backdrop="static"
                                                        data-contact_name="{{ $defaultAddress->contact_name }}" ,
                                                        data-mobile_no="{{ $defaultAddress->mobile_no }}" ,
                                                        data-email="{{ $defaultAddress->email }}" ,
                                                        data-save_as="{{ $defaultAddress->save_as }}" ,
                                                        data-address="{{ $defaultAddress->address }}" ,
                                                        data-locality="{{ $defaultAddress->locality }}" ,
                                                        data-city="{{ $defaultAddress->city }}" ,
                                                        data-state="{{ $defaultAddress->state }}" ,
                                                        data-pincode="{{ $defaultAddress->pincode }}" ,
                                                        data-address_id="{{ $defaultAddress->id }}" ,
                                                        data-is_default="{{ $defaultAddress->is_default }}">Edit</a>

                                                </div>

                                            </div>

                                        </div>

                                    @endif

                                    @if (count($otherAddress) > 0)

                                        <div class="address-title">Other Address</div>

                                        @foreach ($otherAddress as $otherAddress)

                                            <div class="default-address">

                                                <div class="form-group">

                                                    <label class="radio-box">

                                                        <b>{{ $otherAddress->contact_name }}</b>
                                                        <h5>{{ $otherAddress->save_as == 0 ? 'Home' : 'Work' }}</h5>

                                                        <p>
                                                            {{ $otherAddress->address . ',' . $otherAddress->locality . ',' . $otherAddress->city . ',' . $otherAddress->state . '-' . $otherAddress->pincode }}
                                                        </p>

                                                        <p><span class="text-muted">Mobile:
                                                            </span>{{ $otherAddress->mobile_no }}</p>
                                                        <p><span class="text-muted">Email:
                                                            </span>{{ $otherAddress->email }}
                                                        </p>

                                                        <input type="radio" name="address_id" class="address_id"
                                                            value="{{ $otherAddress->id }}">
                                                        <span class="checkmark"></span>

                                                    </label>

                                                    <div class="edit-delete">

                                                        <a href="#" class="btn border-btn" data-toggle="modal"
                                                            data-target="#delete-modal" data-backdrop="static">Remove</a>

                                                        <a href="#" class="btn border-btn" data-toggle="modal"
                                                            data-target="#edit-address" data-backdrop="static"
                                                            data-contact_name="{{ $otherAddress->contact_name }}" ,
                                                            data-mobile_no="{{ $otherAddress->mobile_no }}" ,
                                                            data-email="{{ $otherAddress->email }}" ,
                                                            data-save_as="{{ $otherAddress->save_as }}" ,
                                                            data-address="{{ $otherAddress->address }}" ,
                                                            data-locality="{{ $otherAddress->locality }}" ,
                                                            data-city="{{ $otherAddress->city }}" ,
                                                            data-state="{{ $otherAddress->state }}" ,
                                                            data-pincode="{{ $otherAddress->pincode }}" ,
                                                            data-address_id="{{ $otherAddress->id }}" ,
                                                            data-is_default="{{ $otherAddress->is_default }}" ,>Edit</a>

                                                    </div>

                                                </div>

                                            </div>

                                        @endforeach

                                    @endif

                                @else

                                    <div class="text-center text-muted">
                                        You have no address available.
                                    </div>

                                @endif

                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">

                                <div class="cart-summary">

                                    <div class="apply-promo">

                                        <h4>Coupons</h4>


                                        <div class="promo-details">

                                            @if ($data['coupon_data'])

                                                <div class="text">

                                                    <img src="{{ asset('assets/front/images/promo.svg') }}" alt="">

                                                    <span class="coupon_applied">
                                                        {{ $data['coupon_data']['offer_code'] }}
                                                    </span>
                                                    <br>
                                                    <span class="coupon_desc">
                                                        {{ $data['coupon_data']['offer_description'] }}
                                                    </span>
                                                </div>

                                                <div class="button coupon_info">
                                                    <div class="coupon_savings">
                                                        {{ $data['coupon_data']['saved_amount'] }} </div>
                                                    <button type="button" class="btn border-btn" data-toggle="modal"
                                                        data-target="#apply-promo">
                                                        Change Coupon
                                                    </button>
                                                </div>

                                            @else

                                                <div class="text">
                                                    <img src="{{ asset('assets/front/images/promo.svg') }}" alt="">Apply
                                                    Coupons
                                                </div>

                                                <div class="button">
                                                    <button type="button" class="btn border-btn" data-toggle="modal"
                                                        data-target="#apply-promo">
                                                        Apply
                                                    </button>
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                    <div class="priceHeader">PRICE DETAILS ({{ count($data['cartDetail']) }} Items)
                                    </div>

                                    <ul>

                                        <li>
                                            <div class="title text-muted">Cart total</div>
                                            <div class="price"><b>{{ $data['cart_summary']['cart_total'] }}</b>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Product Discount</div>
                                            <div class="price">{{ $data['cart_summary']['product_discount'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Coupon Discount</div>
                                            <div class="price">{{ $data['cart_summary']['coupon_discount'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Shipping charges</div>
                                            <div class="price">{{ $data['cart_summary']['shipping_charge'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title"><b>Total Amount</b></div>
                                            <div class="price">
                                                <b>{{ $data['cart_summary']['payable_amount'] }}</b>
                                            </div>
                                        </li>

                                    </ul>

                                </div>

                                <div class="place-order">

                                    <input type="button" class="theme-btn btn proceed_to_checkout"
                                        value="Proceed to checkout" />

                                </div>

                            </div>

                        </div>

                    </fieldset>

                    <fieldset id="paymentTab">

                        <div class="row">

                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">

                                <div class="cart-head">

                                    <h5>Choose Payment Mode</h5>

                                </div>

                                <div class="payment-option">

                                    <div class="box">

                                        <label class="radio-box">

                                            <input type="radio" name="payment_type" class="payment_type" value="1">
                                            <span class="checkmark"></span>

                                            <div class="text">
                                                <img src="{{ asset('assets/front/images/online-payment.svg') }}" alt="">
                                                Online
                                            </div>

                                        </label>

                                    </div>

                                    <div class="box">

                                        <label class="radio-box">

                                            <input type="radio" name="payment_type" class="payment_type" value="2">
                                            <span class="checkmark"></span>

                                            <div class="text">
                                                <img src="{{ asset('assets/front/images/pay-cash.svg') }}" alt="">
                                                Pay with Cash
                                            </div>

                                        </label>

                                    </div>

                                </div>

                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">

                                <div class="cart-summary">

                                    <div class="apply-promo">

                                        <h4>Coupons</h4>


                                        <div class="promo-details">

                                            @if ($data['coupon_data'])

                                                <div class="text">

                                                    <img src="{{ asset('assets/front/images/promo.svg') }}" alt="">

                                                    <span class="coupon_applied">
                                                        {{ $data['coupon_data']['offer_code'] }}
                                                    </span>
                                                    <br>
                                                    <span class="coupon_desc">
                                                        {{ $data['coupon_data']['offer_description'] }}
                                                    </span>
                                                </div>

                                                <div class="button coupon_info">
                                                    <div class="coupon_savings">
                                                        {{ $data['coupon_data']['saved_amount'] }} </div>
                                                    <button type="button" class="btn border-btn" data-toggle="modal"
                                                        data-target="#apply-promo">
                                                        Change Coupon
                                                    </button>
                                                </div>

                                            @else

                                                <div class="text">
                                                    <img src="{{ asset('assets/front/images/promo.svg') }}" alt="">Apply
                                                    Coupons
                                                </div>

                                                <div class="button">
                                                    <button type="button" class="btn border-btn" data-toggle="modal"
                                                        data-target="#apply-promo">
                                                        Apply
                                                    </button>
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                    <div class="priceHeader">PRICE DETAILS ({{ count($data['cartDetail']) }} Items)
                                    </div>

                                    <ul>

                                        <li>
                                            <div class="title text-muted">Cart total</div>
                                            <div class="price"><b>{{ $data['cart_summary']['cart_total'] }}</b>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Product Discount</div>
                                            <div class="price">{{ $data['cart_summary']['product_discount'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Coupon Discount</div>
                                            <div class="price">{{ $data['cart_summary']['coupon_discount'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title text-muted">Shipping charges</div>
                                            <div class="price">{{ $data['cart_summary']['shipping_charge'] }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title"><b>Total Amount</b></div>
                                            <div class="price">
                                                <b>{{ $data['cart_summary']['payable_amount'] }}</b>
                                            </div>
                                        </li>

                                    </ul>

                                </div>

                                <div class="place-order">

                                    <a href="javascript:;" class="btn theme-btn place_order" title="">Place Order</a>

                                    <!-- <input type="button" name="next" class="next  btn theme-btn " value="Proceed to checkout" /> -->

                                </div>

                            </div>

                        </div>

                    </fieldset>

                </form>

            @else

                <div class="row">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-5 mb-5 border">

                        <p class="text-center p-5 mb-0 lead">
                            Your cart is empty.
                        </p>

                    </div>

                </div>

            @endif

        </div>



        <div class="container">

            <div class="payment-img" style="justify-content: end;">

                {{-- <div class="img">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-ssl.png') }}" width="70" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-visa.png') }}" width="60" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-mc.png') }}" width="60" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-ae.png') }}" width="60" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-dc.png') }}" width="60" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-nb.png') }}" width="60" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-cod.png') }}" width="60" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-rupay.png') }}" width="60" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-paypal.png') }}" width="60" height="37">

                    <img src="{{ asset('assets/front/images/payment/footer-bank-bhim.png') }}" width="60" height="37">

                </div> --}}

                <div class="right-link">

                    <a href="#" data-toggle="modal" data-target="#contact-us" data-backdrop="static"> <span>Need Help ? Contact Us</span> </a>

                </div>

            </div>

        </div>

        <form action="{{ route('removeFromCart') }}" method="post" id="removeFromCartForm">
            @csrf
            <input type="hidden" name="cart_id" id="cid">
        </form>

        <form action="{{ route('moveToWishlist') }}" method="post" id="moveToWishlistForm">
            @csrf
            <input type="hidden" name="cart_id" id="cwid">
        </form>

        <form action="{{ route('applyCoupon') }}" method="post" id="applyCouponForm">
            @csrf
            <input type="hidden" name="couponCode" id="couponCode">
        </form>

        <form action="{{ route('placeOrder') }}" method="post" id="placeOrderForm">
            @csrf
            <input type="hidden" name="payment_type" id="payment_type">
        </form>

        <form action="{{ route('removeCoupon') }}" method="post" id="removeCouponForm">
            @csrf
        </form>

    </section>

    <!-- Apply Promo Code Modal -->

    <div class="modal fade apply-promo-modal" id="apply-promo">

        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">APPLY COUPON</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group coupan-code">

                        <form action="{{ route('applyCoupon') }}" method="post">

                            @csrf

                            @php
                                $coupon_code = '';
                                $coupon_id = '';
                                
                                if (isset($data['coupon_data']) && !empty($data['coupon_data'])) {
                                    $coupon_code = $data['coupon_data']['offer_code'];
                                    $coupon_id = $data['coupon_data']['id'];
                                }
                            @endphp

                            <input type="text" name="couponCode" class="form-control" placeholder="Enter Coupon Code"
                                value="{{ $coupon_code }}" required>

                            <input type="submit" value="Check" />

                        </form>

                    </div>

                    <div class="form-group">

                        @if (count($couponList) > 0)

                            @if (isset($couponList['coupon_list']) && count($couponList['coupon_list']) > 0)

                                @foreach ($couponList['coupon_list'] as $couponData)

                                    <div class="common-check">

                                        <label class="checkbox">

                                            <h6>{{ $couponData->offer_code }}</h6>

                                            <input type="checkbox" name="coupon_code" class="coupon_code"
                                                value="{{ $couponData->offer_code }}"
                                                {{ $coupon_id != '' && $couponData->id == $coupon_id ? 'checked' : '' }} data-value = "{{ $couponData->save; }}" onchange="couponCode(this)">

                                            <span class="checkmark"></span>

                                        </label>

                                        <p>
                                            {{ $couponData->saved_amount }}

                                            <br />

                                            {{ $couponData->offer_description }}

                                            <br />

                                            {{ $couponData->end_date }}

                                        </p>

                                    </div>

                                @endforeach

                            @endif

                        @endif

                        {{-- <div class="common-check">

                            <label class="checkbox">
                                <h6>Bheshbhush300</h6>

                                <input type="checkbox"><span class="checkmark"></span>

                            </label>

                            <p>Save: 300<br />

                                Rs. 300 off on minimum purchase of Rs. 1999<br />

                                31st December 2021 11:59 PM</p>

                        </div> --}}

                    </div>

                </div>

                <div class="modal-footer">

                    @if (count($couponList) > 0)

                        @if (count($couponList['view_detail']) > 0)

                            <h4 class="saved_amount">{{ $couponList['view_detail']['coupon_discount'] }}</h4>

                        @endif

                    @else

                        <h4 class="saved_amount"></h4>

                    @endif

                    <button type="button" class="btn theme-btn apply_coupon_code">Apply</button>

                    @if (isset($data['coupon_data']) && !empty($data['coupon_data']))
                        <button type="button" class="btn theme-btn remove_coupon_code">Clear All</button>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade order-track" id="contact-us" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Support Number</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">
                    <h3><a href="tel:/{{ $masterData->support_number }}">{{ $masterData->support_number }}</a></h3>
                </div>

                <!-- <div class="modal-footer">

            <button type="button" class="btn border-btn" data-dismiss="modal">Close</button>

            <button type="button" class="btn theme-btn"></button>

          </div> -->

            </div>

        </div>

    </div>

@endsection

@section('footer')

    <script type="text/javascript">
        $(document).ready(function() {

            $(".remove_coupon_code").click(function() {
                var form = $("#removeCouponForm");
                // Delete if confirm
                (async () => {
                    var isConfirmed = await confirmDelete();
                    if (isConfirmed) {
                        form.submit();
                    }
                })();
            });

            $(document).on('click', '.proceed_to_checkout', function(e) {
                var btn = $(this);
                var checkAddressLength = $('.address_id:checked').length;
                if (checkAddressLength == 0) {
                    showError('No address selected.');
                    return false;
                } else {
                    var address_id = $('.address_id:checked').val();

                    $.ajax({
                        url: "{{ route('proceedToCheckout') }}",
                        type: 'POST',
                        data: {
                            address_id: address_id,
                            _token: "{{ csrf_token() }}"
                        },
                        // processData: false,
                        success: function(data) {
                            // console.log(data);
                            // console.log(data.status);
                            if (data.status) {
                                iziToast.success({
                                    title: data.message,
                                    position: "topRight",
                                });
                                btn.removeClass("proceed_to_checkout").addClass("next");
                                btn.click();
                                // console.log(btn);
                                // location.reload();
                            } else {
                                iziToast.error({
                                    title: data.message,
                                    position: "topRight",
                                });
                                // location.reload();
                            }
                            //   alert(data);


                        }
                    });
                }
            });

            $(".place_order").click(function() {
                var checkPaymentModeLength = $('.payment_type:checked').length;
                if (checkPaymentModeLength == 0) {
                    showError('Choose payment mode.');
                    return false;
                } else {
                    var payment_type = $('.payment_type:checked').val();
                    // console.log(coupon_code);
                    var form = $("#placeOrderForm");
                    $("#payment_type").val(payment_type)
                    form.submit();
                }
            });

            $(".apply_coupon_code").click(function() {
                var checkCouponCodeLength = $('.coupon_code:checked').length;
                if (checkCouponCodeLength == 0) {
                    showError('No coupon code selected.');
                    return false;
                } else {
                    var coupon_code = $('.coupon_code:checked').val();
                    // console.log(coupon_code);
                    var form = $("#applyCouponForm");
                    $("#couponCode").val(coupon_code)
                    form.submit();
                }
            });

            $('.coupon_code').on('change', function() {
                $('.coupon_code').not(this).prop('checked', false);
            });

            $(".move_to_wishlist").click(function() {
                var checkedItemLength = $('.cart_item:checked').length;
                if (checkedItemLength == 0) {
                    showError('No items selected.');
                    return false;
                } else {
                    var cartId = [];
                    $('.cart_item:checked').each(function() {
                        cartId.push($(this).val());
                    });
                    if (cartId.length != 0) {
                        cartId = cartId.join(",");
                        // console.log(cartId);
                        var form = $("#moveToWishlistForm");
                        $("#cwid").val(cartId)
                        form.submit();
                    }
                }
            });

            $(".removeItem").click(function() {
                var cartId = $(this).data('id');
                var form = $("#removeFromCartForm");
                $("#cid").val(cartId);
                // console.log(cartId);
                // Delete if confirm
                (async () => {
                    var isConfirmed = await confirmDelete();
                    if (isConfirmed) {
                        form.submit();
                    }
                })();
            });

            $(".remove_from_cart").click(function() {
                var checkedItemLength = $('.cart_item:checked').length;
                if (checkedItemLength == 0) {
                    showError('No items selected.');
                    return false;
                } else {
                    var cartId = [];
                    $('.cart_item:checked').each(function() {
                        cartId.push($(this).val());
                    });
                    if (cartId.length != 0) {
                        cartId = cartId.join(",");
                        // console.log(cartId);
                        var form = $("#removeFromCartForm");
                        $("#cid").val(cartId)
                        form.submit();
                    }
                }
            });

            $("#select_all_items").click(function() {
                $(".cart_item").prop('checked', $(this).prop("checked"));
                $("#selected_items").html($(".cart_item:checked").length);
            });

            $(".cart_item").click(function() {
                var numberOfChildCheckBoxes = $('.cart_item').length;
                $("#selected_items").html($(".cart_item:checked").length);
                var checkedChildCheckBox = $('.cart_item:checked').length;
                if (checkedChildCheckBox == numberOfChildCheckBoxes) {
                    $("#select_all_items").prop('checked', true);
                } else {
                    $("#select_all_items").prop('checked', false);
                }
            });

            var current_fs, next_fs, previous_fs; //fieldsets

            var opacity;

            $(document).on('click', 'input.next', function(e) {

                // current_fs = $(this).parent();

                // next_fs = $(this).parent().next(); 

                current_fs = $(this).parent().parent().parent();

                next_fs = $(this).parent().parent().parent().parent().next();

                //Add Class Active

                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset

                next_fs.show();

                //hide the current fieldset with style

                current_fs.animate({

                    opacity: 0

                }, {

                    step: function(now) {

                        // for making fielset appear animation

                        opacity = 1 - now;

                        current_fs.css({

                            'display': 'none',

                            'position': 'relative'

                        });

                        next_fs.css({

                            'opacity': opacity

                        });

                    },

                    duration: 600

                });

            });

            $(".previous").click(function() {

                current_fs = $(this).parent();

                previous_fs = $(this).parent().prev();

                //Remove class active

                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset

                previous_fs.show();

                //hide the current fieldset with style

                current_fs.animate({

                    opacity: 0

                }, {

                    step: function(now) {

                        // for making fielset appear animation

                        opacity = 1 - now;

                        current_fs.css({

                            'display': 'none',

                            'position': 'relative'

                        });

                        previous_fs.css({

                            'opacity': opacity

                        });

                    },

                    duration: 600

                });

            });

            // $('.radio-group .radio').click(function() {

            //     $(this).parent().find('.radio').removeClass('selected');

            //     $(this).addClass('selected');

            // });

            $(".submit").click(function() {

                return false;

            })

        });
        function couponCode(objs)
        {
            var savedAmount = $(objs).data("value");
            if ($(objs).prop("checked") == true)
            {
                $('.saved_amount').text(savedAmount);
            }
            else
            {
                $('.saved_amount').text('0 NR');
            }
        }
    </script>

@endsection
