@extends('layouts.admin')

@section('title')

Past Booking

@endsection

@section('head')

<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/admin/app-assets/vendors/css/tables/datatable/datatables.min.css')  }}">
<style>
    .btn-print {
        color: #FFFFFF !important;
        background-color: #1E1E1E !important;
        border-color: #1E1E1E !important;
    }
</style>
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">

        <div class="row breadcrumbs-top">

            <div class="col-12">

                <h2 class="content-header-title float-left mb-0">
                    Past Booking
                </h2>

                <div class="breadcrumb-wrapper col-12">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>

                        </li>

                        <li class="breadcrumb-item active">
                            Past Booking
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>

    {{-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">

        <div class="form-group breadcrum-right">

            <div class="dropdown">

                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle waves-effect waves-light"
                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <i class="feather icon-settings"></i>

                </button>

            </div>

        </div>

    </div> --}}

</div>



<div class="content-body">

    <section>

        <div class="row">

            <div class="col-md-12">

                <div class="card">

                    <div class="card-header">

                        <h4 class="card-title">Past Booking</h4>

                    </div>

                    <div class="card-content">

                        <div class="card-body">
                            <div class="button-container text-right mt-1 mb-1"></div>
                            <div class="table-responsive">
                                <table class="table table-striped dataex-html5-selectors_1">
                                    <thead>
                                        <tr>

                                            <th>#</th>

                                            <th>Order ID</th>

                                            <th>Order Date</th>

                                            <th>Order Amount</th>

                                            <th>Customer Name</th>
                                            <th>No. of Unit</th>
                                            <th>Order Status</th>

                                            <th>Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @if(count($pastBooking) > 0)

                                        @foreach ($pastBooking as $booking)

                                        <tr>

                                            <td>{{ $loop->iteration}}</td>

                                            <td>{{ $booking->id }}</td>

                                            <td><span>{{ $booking->created_at->timestamp; }}</span>{{ $booking->created_at->format('d-m-Y') }}</td>

                                            <td>{{ $booking->total_amount }}</td>

                                            <td>{{ $booking->full_name }}</td>
                                            <td>{{ $booking->no_of_unit }}</td>
                                            <td>
                                                @if($booking->order_status==1)
                                                <p class="badge badge-pill badge-light-primary">Pending</p>
                                                @elseif($booking->order_status==2)
                                                <p class="badge badge-pill badge-light-info">Assigned</p>
                                                @elseif($booking->order_status==3)
                                                <p class="badge badge-pill badge-light-danger">Cancelled</p>
                                                @elseif($booking->order_status==4)
                                                <p class="badge badge-pill badge-light-success">Delivered</p>
                                                @elseif($booking->order_status==5)
                                                <p class="badge badge-pill badge-light-warning">Returned</p>
                                                @elseif($booking->order_status==6)
                                                <p class="badge badge-pill badge-light-info">Out For Service</p>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.booking.detail',$booking->id) }}" class="btn btn-sm btn-primary"><i
                                                        class="fa fa-eye fa-lg"></i>
                                                </a>
                                            </td>

                                        </tr>

                                        @endforeach

                                        @endif

                                    </tbody>

                                </table>

                            </div>

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

<script type="text/javascript">
    var table = $('.dataex-html5-selectors_1').DataTable( {
    
    dom: 'Blfrtip',
    buttons: [
        // {
        //     extend: 'copyHtml5',
        //     exportOptions: {
        //         columns: [ 0, ':visible' ]
        //     }
        // },
        {
            extend: 'csv',
            text: '<i class="fa fa-file-excel-o"></i> &nbsp; EXCEL',
            className: 'btn-success',
            exportOptions: {
                columns: ':visible:not(.not-export-col)'
            }
        },
        {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf-o"></i> &nbsp; PDF',
            className: 'btn-danger',
            exportOptions: {
                columns: ':visible:not(.not-export-col)'
            }
        },
        {
            extend: 'print',
            text: '<i class="fa fa-print"></i> &nbsp; PRINT',
            className: 'btn-print',
            exportOptions: {
                columns: ':visible:not(.not-export-col)'
            }
        }
    ]
    });

    table.buttons().container()
    .appendTo('.button-container');
</script>
@endsection