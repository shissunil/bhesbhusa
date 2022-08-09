@extends('front.layout.main')
@section('section')
<section class="inner-page-banner">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">My Order Details</li>
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
                <div class="dashboard-title">
                    <h3>Orders Detail</h3>
                </div>
                <div class="white-bg orders-details">
                    @if ($data['orderDetail'])
                        @if ($data['orderDetail']->status == '1')
                            <div class="order-info confirmed">
                                <div class="icon"><img src="{{ asset('assets/front/images/bag.svg') }}" alt=""></div>
                                {{ $data['orderDetail']->order_status }} - On {{ $data['orderDetail']->order_date }}
                            </div>
                        @elseif ($data['orderDetail']->status== '2')
                            <div class="order-info confirmed">
                                <div class="icon"><img src="{{ asset('assets/front/images/bag.svg') }}" alt=""></div>
                                {{ $data['orderDetail']->order_status }} - On {{ $data['orderDetail']->order_date }}
                            </div>
                        @elseif ($data['orderDetail']->status== '3')
                            <div class="order-info confirmed">
                                <div class="icon"><img src="{{ asset('assets/front/images/bag.svg') }}" alt=""></div>
                                {{ $data['orderDetail']->order_status }} - On {{ $data['orderDetail']->order_date }}
                            </div>
                        @elseif ($data['orderDetail']->status== '4')
                            <div class="order-info delivered">
                                <div class="icon"><img src="{{ asset('assets/front/images/bag.svg') }}" alt=""></div>
                                {{ $data['orderDetail']->order_status }} - On {{ $data['orderDetail']->delivered_date }}
                            </div>
                        @elseif ($data['orderDetail']->status== '5')
                            <div class="order-info confirmed">
                                <div class="icon"><img src="{{ asset('assets/front/images/bag.svg') }}" alt=""></div>
                                {{ $data['orderDetail']->order_status }} - On {{ $data['orderDetail']->return_date }}
                            </div>
                        @elseif ($data['orderDetail']->status== '6')
                            <div class="order-info confirmed">
                                <div class="icon"><img src="{{ asset('assets/front/images/bag.svg') }}" alt=""></div>
                                {{ $data['orderDetail']->order_status }} - On {{ $data['orderDetail']->return_date }}
                            </div> 
                        @endif
                    @endif
                    <div class="head-order">
                        <div class="left">
                            <p>Placed On:<span>{{ $data['orderDetail']->order_date }}</span></p>
                            <p>Order No:<span>{{ $data['orderDetail']->order_no }}</span></p>
                        </div>
                        <div class="right">
                            @if ($data['orderDetail']->invoice)
                                <a href="{{ $data['orderDetail']->invoice }}" class="btn border-btn">
                                    <div class="icon-btn"><img src="{{ asset('assets/front/images/download.svg') }}" alt="">Invoice</div>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="order-body">
                        <!-- <div class="price-details">
                            </div> -->
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="text-uppercase text-muted">
                                        <th scope="col">Product</th>
                                        <th scope="col" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data['orderDetail']['orderItem'])
                                        @foreach ($data['orderDetail']['orderItem'] as $item)
                                            <tr>
                                                <th scope="row">
                                                            <div class="box">
                                                                <img src="{{ $item->productData->product_image }}" alt="">
                                                                <div class="content">
                                                                    <p>{{ $item->brand_name }}</p>
                                                                    <p>{{ $item->productData->product_name }}</p>
                                                                    <p>Size: {{ $item->size_name }} | Qty: {{ $item->quantity }}</p>
                                                                    <p>Saved {{ $item->saved_amount }}</p>
                                                                </div>
                                                            </div>
                                                </th>
                                                <td class="text-right"><div><b>{{ $item->product_price }}</b></div>
                                                    @if ($item->is_rate == '0')
                                                        <a href="#" data-id="{{ $item->id }}" onclick="rateOrder(this)" data-backdrop="static" class="btn btn-info btn-sm">Rate Now</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="pt-2 border-bottom mb-3"></div>
                        <div class="d-flex justify-content-start align-items-center pl-3">
                            <div class="text-muted">Total Amount</div>
                            <div class="ml-auto"> <label>{{ $data['orderDetail']->mrp }}</label> </div>
                        </div>
                        <div class="d-flex justify-content-start align-items-center pl-3">
                            <div class="text-muted">Cash on delivery</div>
                            <div class="ml-auto"><label>{{ $data['orderDetail']->cash_on_delivery }}</label> </div>
                        </div>
                        <div class="d-flex justify-content-start align-items-center pb-4 pl-3 border-bottom">
                            <div class="text-muted">Item Discount </div>
                            <div class="ml-auto price"> -{{ $data['orderDetail']->item_discount }}</div>
                        </div>
                        <div class="d-flex justify-content-start align-items-center pl-3 py-3 mb-4 border-bottom">
                            <div class=""> <b>Payable Amount</b> </div>
                            <div class="ml-auto h5"> {{ $data['orderDetail']->total }}</div>
                        </div>
                        <div class="contact-details">
                            <div class="box">
                                <b>Shipping Address</b>
                                <p>{{ $data['delivery_address']->locality .', '. $data['delivery_address']->city.', '.$data['delivery_address']->address.', '.$data['delivery_address']->state.', '.$data['delivery_address']->pincode }}</p>
                                {{-- <p>Rno 319 Shree Krishna Complex, opp mahesh aptm gate no2 new ashok nagar east delhi, delhi 380061</p> --}}
                                @if ($data['orderDetail']->status == '1')
                                    <a class="change-address" href="#" data-toggle="modal" data-target="#edit-order-address" data-backdrop="static">Change Address</a>
                                @endif
                            </div>
                            <div class="box">
                                <b>Updates sent to:</b>
                                <ul>
                                    <li><a href="tel:{{ $data['delivery_address']->mobile_no }}" title=""><img src="{{ asset('assets/front/images/phone.svg') }}" alt="">{{ $data['delivery_address']->mobile_no }}</a></li>
                                    <li><a href="mailto:{{ $data['delivery_address']->email }}" title=""><img src="{{ asset('assets/front/images/mail.svg') }}" alt="">{{ $data['delivery_address']->email }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="cancle-track-order">
                            {{-- <a href="#" data-toggle="modal" data-target="#rate-now" data-backdrop="static" class="btn border-btn">Rate Now</a> --}}
                            @if ($data['orderDetail']->status != '3' && $data['orderDetail']->status != '5')
                                <a href="#" data-toggle="modal" data-target="#track-order" data-backdrop="static" class="btn border-btn">Track Order</a>
                            @endif
                            @if ($data['orderDetail']->is_return == '0')
                                <a href="{{ route('returnOrder',Crypt::encrypt($data['orderDetail']->id)) }}" class="btn border-btn">Return Order</a>
                            @endif
                            @if ($data['orderDetail']->is_cancel == '0')
                                <a href="#" onclick="cancelModel({{ $data['orderDetail']->id }})"  class="btn border-btn">Cancel</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Order Track Modal -->

<div class="modal fade order-track" id="track-order" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Track Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="track-item">
                    @if ($data['orderDetail']->status == '4')
                        <li class="active"><span>Order Delivered</span> By {{ $data['orderDetail']->delivered_date }}</li>
                    @else
                        <li class=""><span>Order Delivered</span> By {{ $data['orderDetail']->estimate_date }}</li>
                    @endif
                    <li class="active"><span>Order Placed</span> By {{ $data['orderDetail']->order_date }}</li>
                </ul>
            </div>
            <!-- <div class="modal-footer">
        <button type="button" class="btn border-btn" data-dismiss="modal">Close</button>
        <button type="button" class="btn theme-btn"></button>
      </div> -->
        </div>
    </div>
</div>

<!-- Edit Address Modal -->

<div class="modal fade add-address-modal" id="edit-order-address">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('updateOrderAddress') }}" id="editAddressForm">
                @csrf
                <div class="modal-header">

                    <h5 class="modal-title">Edit Address</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <h4>Contact Details</h4>

                    <div class="form-group">

                        <input type="text" name="contact_name" class="form-control" placeholder="Name*"
                            id="e_contact_name" value="{{ $data['delivery_address']->contact_name }}">
                    </div>

                    <div class="form-group">
                        <input type="text" name="mobile_no" class="form-control" placeholder="Mobile Number*"
                            id="e_mobile_no" value="{{ $data['delivery_address']->mobile_no }}">
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="Email*" id="e_email" value="{{ $data['delivery_address']->email }}">
                    </div>
                    <h4>Address</h4>
                    <div class="form-group">
                        <input type="text" name="pincode" class="form-control" placeholder="Pincode*" id="e_pincode" value="{{ $data['delivery_address']->pincode }}" readonly>
                    </div>
                    <div class="form-group">
                        <textarea name="address" class="form-control"
                            placeholder="Address (House No, building, street, area)*" id="e_address">{{ $data['delivery_address']->address }}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" name="locality" class="form-control" placeholder="locality / town*"
                            id="e_locality" value="{{ $data['delivery_address']->locality }}">
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" id="e_city" value="{{ $data['delivery_address']->city }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" id="e_state" value="{{ $data['delivery_address']->state }}">
                            </div>

                        </div>

                    </div>
                    <h4>Save Address As</h4>
                    <div class="radio-list">
                        <label class="radio-box">Home
                            <input type="radio" name="save_as" value="0" class="e_save_as" {{ ($data['delivery_address']->save_as == '0') ? 'checked' : '' }}><span
                                class="checkmark"></span></label>
                        <label class="radio-box">Work
                            <input type="radio" name="save_as" value="1" class="e_save_as" {{ ($data['delivery_address']->save_as == '1') ? 'checked' : '' }}><span class="checkmark"></span></label>
                    </div>
                    <br>
                    <div class="common-check">
                        <label class="checkbox">
                            <div>Make this my default address</div>
                            <div class="number">
                            </div>
                            <input type="checkbox" name="is_default" value="1" id="e_is_default" {{ ($data['delivery_address']->is_default == '1') ? 'checked' : '' }}>
                            <span class="checkmark">
                            </span>
                        </label>

                    </div>
                    <input type="hidden" name="address_id" id="e_address_id" value="{{ $data['delivery_address']->id }}">
                    <input type="hidden" name="order_id" id="order_id" value="{{ $data['orderDetail']->id }}">
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn border-btn" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn theme-btn">Save Address</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Cancel Modal -->

<div class="modal fade cancel-order" id="cancel-order" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Cancel Order</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>
            <form method="post" action="{{ route('cancelOrder') }}">
                @csrf
                <div class="modal-body">
                    <h4>Reason for Cancellation</h4>
                    <p>Please tell us correct reason for cancellation. This information is only used to improve our service
                    </p>
                    <h5>Select Reason</h5>
                    <ul>
                        @if ($reasonList)
                            @foreach ($reasonList as $item)
                                <li><label class="radio-box">Incorrect size ordered<input type="radio" name="reason_id" value="{{ $item->id }}"><span
                                            class="checkmark"></span></label></li>
                            @endforeach
                        @endif
                    </ul>
                    <input type="hidden" name="order_id" id="cancel_order_id">
                    <textarea class="form-control" rows="4" name="cancel_description" placeholder="Additional Comments"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn border-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn theme-btn">Cancel</button>
                </div>
            </form>
        </div>

    </div>

</div>

<!-- Rating Modal -->

<div class="modal fade rate-now" id="rate-now" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Rate Now</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>
            <div class="modal-body">
                <div class="product">
                    <div class="img"><img src="{{ asset('assets/front/images/product1.png') }}" id="product_image"></div>
                    <p><b id="brand_name">Puma</b></p>
                    <p id="product_name">Men Skinny Fit Tshirt</p>
                </div>
                <h6 class="text-center">Rate Now</h6>
                <form method="post" action="{{ route('saveReviewAndRating') }}">
                    @csrf
                    <input type="hidden" name="order_item_id" value="" id="order_item_id">
                    <fieldset class="rating">

                        <input type="radio" id="star5" name="rating" value="5" /><label class="full" for="star5"
                            title="Awesome - 5 stars"></label>

                        <input type="radio" id="star4half" name="rating" value="4.5" /><label
                            class="half" for="star4half" title="Pretty good - 4.5 stars"></label>

                        <input type="radio" id="star4" name="rating" value="4" /><label class="full" for="star4"
                            title="Pretty good - 4 stars"></label>

                        <input type="radio" id="star3half" name="rating" value="3.5" /><label
                            class="half" for="star3half" title="Meh - 3.5 stars"></label>

                        <input type="radio" id="star3" name="rating" value="3" /><label class="full" for="star3"
                            title="Meh - 3 stars"></label>

                        <input type="radio" id="star2half" name="rating" value="2.5" /><label
                            class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>

                        <input type="radio" id="star2" name="rating" value="2" /><label class="full" for="star2"
                            title="Kinda bad - 2 stars"></label>

                        <input type="radio" id="star1half" name="rating" value="1.5" /><label
                            class="half" for="star1half" title="Meh - 1.5 stars"></label>

                        <input type="radio" id="star1" name="rating" value="1" /><label class="full" for="star1"
                            title="Sucks big time - 1 star"></label>

                        <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half"
                            for="starhalf" title="Sucks big time - 0.5 stars"></label>

                    </fieldset>
                    <div class="form-group">
                        <label>Detailed Review (Optional)</label>
                        <textarea class="form-control" rows="4" name="message" placeholder="Review Message"></textarea>
                    </div>
                    <div class="form-group text-center">

                        <button type="submit" class="btn theme-btn">Submit</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script type="text/javascript">
        function cancelModel(orderId)
        {
            $('#cancel_order_id').val(orderId);
            $('#cancel-order').modal('show');
        }
        function rateOrder(objs)
        {
            var orderItemId = $(objs).data('id');
            $('#order_item_id').val(orderItemId);
            $.ajax({
                type: "POST",
                url: "{{ route('orderItemData') }}",
                data: {"_token":"{{ csrf_token() }}",order_item_id:orderItemId},
                cache: false,
                success: function(result)
                {
                    $('#product_name').text(result.product_name);
                    $('#brand_name').text(result.brand_name);
                    $('#product_image').attr("src",result.product_image);
                }
            });
            $('#rate-now').modal('show');
        }
    </script>
@endsection