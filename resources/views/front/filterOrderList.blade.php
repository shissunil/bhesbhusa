<div class="row">
    @if (count($orderList) > 0)
        @foreach ($orderList as $order)
            <div class="col-xl-6 col-lg-6">
                <div class="product-list">
                    <div class="details">
                        <a href="{{ route('orderDetail',Crypt::encrypt($order->id)) }}" title="">
                            <h4 class="mb-2">Order ID: {{ $order->order_no }}</h4>
                        </a>
                        <p class="float-right">{{ $order->order_date }}</p>
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
                        <div class="float-right mt-2">{{ $order->total_amount }}</div>
                        <div class="mt-2">{{ $order->payment_type }}</div>
                        <span class="mt-2">{{ $order->user_name }}</span>
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

