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
                                                        <a href="#" data-toggle="modal" data-target="#rate-now" data-backdrop="static" class="btn btn-info btn-sm">Rate Now</a>
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
                            <div class="text-muted">MRP</div>
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
                            <div class=""> <b>Total</b> </div>
                            <div class="ml-auto h5"> {{ $data['orderDetail']->total }}</div>
                        </div>
                        <div class="contact-details">
                            <div class="box">
                                <b>Shipping Address</b>
                                <p>{{ $data['delivery_address']->locality .', '. $data['delivery_address']->city.', '.$data['delivery_address']->address.', '.$data['delivery_address']->state.', '.$data['delivery_address']->pincode }}</p>
                                {{-- <p>Rno 319 Shree Krishna Complex, opp mahesh aptm gate no2 new ashok nagar east delhi, delhi 380061</p> --}}
                                @if ($data['orderDetail']->status == '1')
                                    <a class="change-address" href="#" data-toggle="modal" data-target="#add-new-address" data-backdrop="static">Change Address</a>
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
                            @if ($data['orderDetail']->status != '3' || $data['orderDetail'] != '5')
                                <a href="#" data-toggle="modal" data-target="#track-order" data-backdrop="static" class="btn border-btn">Track Order</a>
                            @endif
                            @if ($data['orderDetail']->is_return == '0')
                                <a href="{{ route('returnOrder',$data['orderDetail']->id) }}" class="btn border-btn">Return Order</a>
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
@endsection