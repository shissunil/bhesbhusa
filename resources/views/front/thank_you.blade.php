@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="#">Home</a></li>

                <li class="breadcrumb-item active">Thank You</li>

            </ol>

        </div>

    </section>

    <section class="shoping-cart">

        <div class="container">

            <div class="confirmation-order">

                <div class="box">

                    @if (Session::has('data'))

                        @php
                            $data = Session::get('data');
                        @endphp

                        <div class="save-nr">
                            <img src="{{ asset('assets/front/images/pay-cash.svg') }}" alt="">
                            You saved <span>{{ $data['total_saved'] }}</span> on this order
                        </div>

                        <h3>Order Confirmed</h3>

                        <p>You will receive an order confirmation email shortly with the expected delivery date.</p>

                        <div class="d-flex align-items-center justify-content-center">

                            @if (count($data['productImage']) > 0)
                                @foreach ($data['productImage'] as $pImage)
                                    <div class="img">
                                        <img src="{{ $pImage->product_image }}" alt="">
                                    </div>
                                @endforeach
                            @endif

                        </div>

                        <a href="{{ route('my_orders') }}" class="btn theme-btn">My Orders</a>


                    @endif

                </div>

            </div>

        </div>

    </section>

@endsection
