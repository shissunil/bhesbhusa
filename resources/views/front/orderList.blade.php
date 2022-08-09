@extends('front.layout.main')
@section('section')
<section class="inner-page-banner">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">My Orders</li>
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
                    <h3>Orders</h3>
                    <select class="form-control">
                        <option>All Orders</option>
                        <option>Open Orders</option>
                        <option>Return Refund Orders</option>
                        <option>Cancelled Refund Orders</option>
                        <option>Return Orders</option>
                        <option>Cancelled Orders</option>
                        <option>Alteration orders</option>
                    </select>
                </div>
                <div class="white-bg my-orders">
                    <div class="row">
                        @if (count($orderList) > 0)
                            @foreach ($orderList as $order)
                                <div class="col-xl-6 col-lg-6">
                                    <div class="product-list">
                                        <div class="details">
                                            <a href="{{ route('orderDetail',$order->id) }}" title="">
                                                <h4>Order ID: {{ $order->order_no }}</h4>
                                            </a>
                                            <p>{{ $order->order_date }}</p>
                                            @if ($order->status == '1')
                                                <div class="text-warning">{{ $order->order_status }}</div>
                                            @elseif ($order->status == '2') 
                                                <div class="order-info delivered">{{ $order->order_status }}</div>
                                            @elseif ($order->status == '3') 
                                                <div class="text-warning">{{ $order->order_status }}</div>
                                            @elseif ($order->status == '4') 
                                                <div class="order-info delivered">{{ $order->order_status }}</div>
                                            @elseif ($order->status == '5') 
                                                <div class="text-danger">{{ $order->order_status }}</div>
                                            @elseif ($order->status == '6') 
                                                <div class="order-info confirmed">{{ $order->order_status }}</div>
                                            @endif
                                            <div>{{ $order->total_amount }}</div>
                                            <div>{{ $order->payment_type }}</div>
                                            <span class="">{{ $order->user_name }}</span>
                                        </div>
                                        <div class="cancel-track">
                                            @if ($order->is_cancel == '0')
                                                <a href="#" onclick="cancelModel({{ $order->id }})"  class="btn border-btn">Cancle</a>
                                            @endif
                                            @if ($order->is_return == '0')
                                                <a href="#"  data-toggle="modal" data-target="#track-order" data-backdrop="static" class="btn border-btn">Return</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted col-12">You have no items in wishlist.</div>
                        @endif
                        <div class="col-12">
                            @if (count($orderList) > 0)
                                {{ $orderList->links('vendor.pagination.default') }}
                            @endif
                        </div>
                        {{-- <div class="col-xl-6 col-lg-6">
                            <div class="product-list">
                                <div class="product-img">
                                    <a href="{{ route('orderDetails') }}" title=""><img src="{{ asset('assets/front/images/profile-pic.png') }}" alt=""></a>
                                </div>
                                <div class="details">
                                    <a href="{{ route('orderDetails') }}" title="">
                                        <h4>Puma</h4>
                                        <p>Men Skinny Fit Tshirt</p>
                                    </a>
                                    <p>Size: M</p>
                                    <div class="delivered">Delivered</div>
                                    <span>on fri, 12 apr</span>
                                </div>
                                <div class="cancel-track">
                                    <a href="#" data-toggle="modal" data-target="#cancel-order" data-backdrop="static"  class="btn border-btn">Cancle</a>
                                    <a href="#"  data-toggle="modal" data-target="#track-order" data-backdrop="static" class="btn border-btn">Track</a>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="col-xl-12">
                            <div class="bottom-btn"><button class="btn theme-btn">Show my older orders</button></div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
                    <input type="hidden" name="order_id" id="order_id">
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
@endsection

@section('footer')
<script type="text/javascript">
    function cancelModel(orderId)
    {
        $('#order_id').val(orderId);
        $('#cancel-order').modal('show');
    }
</script>
@endsection