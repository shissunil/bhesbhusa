@extends('layouts.admin')

@section('title')
Refund Payment
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
                <h2 class="content-header-title float-left mb-0">Refund Payments</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Refund Payments
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Refund Payments: {{ ($refundPayments) ? $refundPayments->sum('total_amount') : '' }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="usersTable">
                                <div class="button-container text-right mt-1 mb-1"></div>
                                <div class="table-responsive">
                                    <table class="table table-striped dataex-html5-selectors_1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Order ID</th>
                                                <th>Order Date</th>
                                                <th>Refund Amount</th>
                                                <th>Customer Name</th>
                                                <th>Order Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($refundPayments) > 0)
                                            @foreach ($refundPayments as $booking)
                                            <tr>
                                                <td>{{ $loop->iteration}}</td>
                                                <td>{{ $booking->id }}</td>
                                                <td><span>{{ $booking->created_at->timestamp; }}</span>{{ $booking->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $booking->total_amount - (double)$booking->shipping_charge }}</td>
                                                <td>{{ $booking->full_name }}</td>
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
                                                    <a href="{{ route('admin.booking.detail',$booking->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye fa-lg"></i>
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
        // {
        //     text: 'JSON',
        //     action: function ( e, dt, button, config ) {
        //         var data = dt.buttons.exportData();

        //         $.fn.dataTable.fileSave(
        //             new Blob( [ JSON.stringify( data ) ] ),
        //             'Export.json'
        //         );
        //     }
        // },
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

    $(document).ready(function(){
        $("#filterStatus").change(function(){
            var status = $(this).val();
            // console.log(status);
            $.ajax({
                url: "{{ route('admin.users.index') }}",
                type: 'GET',
                data: {
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                // processData: false,
                success: function(data) {
                    // console.log(data);
                    $("#usersTable").html(data);
                }
            });
        });
    });
</script>
@endsection