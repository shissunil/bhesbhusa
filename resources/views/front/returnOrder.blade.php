@extends('front.layout.main')
@section('section')
<section class="inner-page-banner">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Address</li>
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
                    <h3>Return Order</h3>
                </div>
                <div class="return-order">
                    <div class="product-info">
                        @if ($data['orderData']['order_item'])
                            @foreach ($data['orderData']['order_item'] as $item)
                                <div class="box">
                                    <div class="img"><img src="{{ $item->productData->product_image }}" alt=""></div>
                                    <div class="text">
                                        <p><b>You are returning</b></p>
                                        <p>{{ $item->productData->product_name }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="pickup-date">
                            <p><i class="fas fa-truck-pickup"></i> Item will be picked up by {{ $data['orderData']->estimate_date }}</p>
                            <a href="{{ route('returnPolicy') }}" title="">View Policy</a>
                        </div>
                    </div>
                    <form class="form-group" action="{{ route('returnOrderConfirm',) }}" method="post">
                        @csrf
                    <div class="pickup-address">
                        {{-- <p><b>Pickup Address</b></p> --}}
                        <h4>Reason for Return Order</h4>

                        <p>Please tell us correct reason for return. This information is only used to improve our service
                        </p>

                        <h5>Select Reason</h5>

                        <ul>
                            @if ($data['reasonList'])
                                @foreach ($data['reasonList'] as $item)
                                    <li><label class="radio-box">{{ $item->reason_description }}<input type="radio" name="reason_id" value="{{ $item->id }}"><span class="checkmark"></span></label></li>
                                @endforeach
                            @endif

                        </ul>
                        <textarea class="form-control" name="additional_comment" placeholder="Additional Comments"></textarea>
                    </div>
                    <div class="pickup-address">
                        <p><b>Pickup Address</b></p>
                        <div class="white-bg my-address">
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
                                                <p><span class="text-muted">Email: </span>{{ $defaultAddress->email }}</p>

                                                <input type="radio" name="address_id" value="{{ $defaultAddress->id }}" checked="checked">
                                                <span class="checkmark"></span>
                                            </label>
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
                                                    <p><span class="text-muted">Email: </span>{{ $otherAddress->email }}
                                                    </p>
                                                    <input type="radio" name="address_id" value="{{ $otherAddress->id }}" checked="checked">
                                                    <span class="checkmark"></span>
                                                </label>
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
                        {{-- <p>{{ $data['delivery_address']->locality .', '. $data['delivery_address']->city.', '.$data['delivery_address']->address.', '.$data['delivery_address']->state.', '.$data['delivery_address']->pincode }}</p> --}}
                        {{-- <p>Rno 319 Shree Krishna Complex,<br/> opp mahesh aptm gate no2 new ashok nagar east delhi,<br/> Delhi 380061</p> --}}
                        {{-- <a href="#" title="" class="btn border-btn" data-toggle="modal" data-target="#add-new-address" data-backdrop="static">Change Pickup Address</a> --}}
                    </div>
                    <div class="bank-details">
                        <h5>Bank Details</h5>
                        {{-- <div class="form-group">
                            <input type="hidden" name="order_id" id="order_id" value="{{ $data['orderData']->id }}">
                            <input type="text" name="ifsc_code" class="form-control" placeholder="IFSC CODE (optional)">
                        </div> --}}
                        <div class="form-group">
                            <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="branch_name" class="form-control" placeholder="Branch Name" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="account_number" class="form-control" placeholder="Account Number" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="account_holder_name" class="form-control" placeholder="Account holder Name" required>
                        </div>
                    </div>
                    <div class="refund-price">
                        <h4><span>Refund Details</span>{{ $data['refund_detail'] }}</h4>
                        <button type="submit" class="btn theme-btn">Confirm</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection