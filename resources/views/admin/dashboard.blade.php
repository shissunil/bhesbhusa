@extends('layouts.admin')

@section('title')
Dashboard
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Dashboard</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>                       
                        <li class="breadcrumb-item active">Dashboard
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <a href="{{ route('admin.setting.index') }}"><button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle waves-effect waves-light"
                    type="button">
                    <i class="feather icon-settings"></i>
                </button></a>
            </div>
        </div>
    </div>
</div>

<div class="content-body">

    <!-- Dashboard Ecommerce Starts -->
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <h3>Total Statistics</h3>
            </div>
        </div>
        <div class="row">
            {{-- <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">92.6k</h2>
                        <p class="mb-0">Subscribers Gained</p>
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-1"></div>
                    </div>
                </div>
            </div> --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.users.index') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $totalCustomer }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-users text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;TOTAL CUSTOMER</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.delivery_associates.index') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $totalDeliveryAssociate }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-users text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Delivery Associates</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $ticketRaiseCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-users text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Customer Raised Ticket</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.offers.index') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $ongoingOfferCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-gift text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;on Going Offer</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.product.index') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $availableProductCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Available Product</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $canceledOrderCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Canceled Orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $deliveredOrderCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Delivered Orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $returnOrderCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Returned Orders</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3>Today Statistics</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.users.index') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $todayCustomer }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-users text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;TODAY CUSTOMER</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.delivery_associates.index') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $todayDeliveryAssociate }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-users text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;TODAY Delivery Associates</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $todayTicketRaiseCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-users text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Today Customer Raised Ticket</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.offers.index') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $ongoingOfferCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-gift text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Today on Going Offer</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.product.index') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $todayAvailableProductCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Available Product</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $todayCanceledOrderCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Today Canceled Orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $todayAssignOrder }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Today Assign Orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $todayDeliveredOrderCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Today Delivered Orders</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">{{ $todayReturnOrderCount }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Today Returned Orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="{{ route('admin.booking.list') }}">
                    <div class="card">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700" style="margin-top: 10px;">₹ {{ $todayCashCollected }}</h2>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-shopping-cart text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <p>&nbsp;&nbsp;&nbsp;Today Collected Cash</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <!-- Dashboard Ecommerce ends -->

</div>

@endsection

@section('footer')
<script src="{{ asset('assets/admin/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
@endsection