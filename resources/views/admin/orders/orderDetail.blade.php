@extends('layouts.admin')

@section('title')

Booking Detail

@endsection

@section('head')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/vendors/css/tables/datatable/datatables.min.css')  }}">

@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/app-user.css') }}">
<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">

        <div class="row breadcrumbs-top">

            <div class="col-12">

                <h2 class="content-header-title float-left mb-0">
                    Booking Detail
                </h2>

                <div class="breadcrumb-wrapper col-12">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>

                        </li>
                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.booking.list') }}">All Booking</a>

                        </li>
                        <li class="breadcrumb-item active">
                            Booking Detail
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>
</div>



<div class="content-body">
    <section class="page-users-view">
        <div class="row">
            <div class="col-md-6 col-12 ">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-2">Order Details</div>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td class="font-weight-bold">Order ID : </td>
                                <td>{{ $orderData->id }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Delivery Associate Name : </td>
                                <td>
                                    @if (!empty($orderData->hasOneDeliveryAssociate))
                                        {{ $orderData->hasOneDeliveryAssociate->first_name }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Customer Name </td>
                                <td>{{ $orderData->hasOneUser->first_name }} </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Customer Mobile No.</td>
                                <td>{{ $orderData->hasOneUser->mobile_number }} </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Order Date</td>
                                <td>{{ date('d-m-Y',strtotime($orderData->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Order Status</td>
                                <td>
                                    @if($orderData->order_status == '1')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($orderData->order_status == '2')
                                        <span class="badge badge-info">Assigned</span>
                                    @elseif($orderData->order_status == '3')
                                        <span class="badge badge-primary">Cancelled</span>
                                    @elseif($orderData->order_status == '4')
                                        <span class="badge badge-info">Delivered</span>
                                    @elseif($orderData->order_status == '5')
                                        <span class="badge badge-success">Return</span>
                                    @elseif($orderData->order_status == '6')
                                        <span class="badge badge-success">Out For Service</span>
                                    @endif

                                </td>
                            </tr>
                            @if ($orderData->remark != '')
                                <tr>
                                    <td class="font-weight-bold">Remark</td>
                                    <td>{{ $orderData->remark }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="font-weight-bold">Mode Of Payment</td>
                                <td> {{ ($orderData->payment_type == '1') ? 'CASH' : 'Online' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Rating</td>
                                <td>
                                    <label class="badge badge-success">{{ $orderData->avarage_rate }}<i class="feather icon-star ml-25"></i></label>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Delivery Address</td>
                                <td>
                                    @if ($orderData->deliveryAddress)
                                        <p class="mb-0"> {{ $orderData->deliveryAddress->address.', '.$orderData->deliveryAddress->locality.', '.$orderData->deliveryAddress->city.', '.$orderData->deliveryAddress->state.', '.$orderData->deliveryAddress->pincode }}</p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    @if ($orderData->order_status == '1')
                        <div class="card-header">
                            <div class="card-title">Assign Order</div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.booking.assign',$orderData->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Delivery Associate</label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="delivery_associate_id" id="delivery_associate_id" required>
                                                <option value="">--SELECT--</option>
                                                @if ($deliveryAssociates)
                                                    @foreach ($deliveryAssociates as $value)
                                                        <option value="{{ $value->id }}" {{ ($orderData->delivery_associates_id == $value->id) ? 'selected' : '' }}>{{ $value->first_name . ' '.$value->last_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light btn-md"> Submit </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6 col-12 ">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-2">Payment Summary</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td class="font-weight-bold">Cart Total </td>
                                <td>{{ $orderData->total_discount + $orderData->total_amount }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Product Discount</td>
                                <td class="text-danger">-{{ $orderData->total_discount }}</td>
                            </tr>
                            {{-- <tr>
                                <td class="font-weight-bold">Coupon Discount</td>
                                <td class="text-danger">-{{ $orderData->coupon_discount }}</td>
                            </tr> --}}
                            <tr>
                                <td class="font-weight-bold">Shipping Charge</td>
                                <td class="text-success">{{ ($orderData->shipping_charge == '0') ? 'Free' : $orderData->shipping_charge }}</td>
                            </tr>
                            @if ($orderData->coupon_id != '0')
                                @if ($orderData->hasOneCoupon)
                                    <tr>
                                        <td class="font-weight-bold">Coupon Code</td>
                                        <td>{{ $orderData->hasOneCoupon->offer_code }}</td>
                                    </tr>
                                @endif
                            @endif
                            @if ($orderData->order_status == '5')
                                <tr>
                                    <td class="font-weight-bold">Total payable Amount </td>
                                    <td class="text-success">
                                        {{ $orderData->total_amount - (double)$orderData->shipping_charge }}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="font-weight-bold">Total payable Amount </td>
                                    <td class="text-success">
                                        {{ $orderData->total_amount }}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                @if ($orderData->returnOrderData != '')
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title mb-2">Customers Bank Details</div>
                        </div>
                        <div class="card-body">
                            <table class="">
                                <tr>
                                    <td class="font-weight-bold">Account Holder Name: </td>
                                    <td>{{ $orderData->returnOrderData->account_holder_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Account Number:</td>
                                    <td>{{ $orderData->returnOrderData->account_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Bank Name:</td>
                                    <td>{{ $orderData->returnOrderData->bank_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Branch Name:</td>
                                    <td>{{ $orderData->returnOrderData->branch_name }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom mx-2 px-0">
                        <h6 class="border-bottom py-1 mb-0 font-medium-2"><i class="feather icon-lock mr-50 "></i>Order Item Details
                        </h6>
                    </div>
                    <div class="card-body px-75">
                        <div class="table-responsive users-view-permission">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Brand Name</th>
                                        <th>Company Name</th>
                                        <th>Product Image</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItem as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order->product_id }}</td>
                                            <td>{{ $order->hasOneProduct->product_name }}</td>
                                            <td>{{ $order->brand }}</td>
                                            <td>{{ $order->sold_by }}</td>
                                            <td><img class="img-thumbnail" src="{{ asset('uploads/product/'.$order->hasOneProduct->product_image) }}" height="100px" width="150px"></td>
                                            <td>{{ $order->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>



@endsection



@section('footer')

<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>

<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>

<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>

<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>

<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>

<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>

<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/admin/app-assets/js/scripts/datatables/datatable.js') }}"></script>

@endsection