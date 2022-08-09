@extends('layouts.admin')

@section('title')
Customer Management
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
                <h2 class="content-header-title float-left mb-0">Customer Management</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Customer Management
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
                        <h4 class="card-title">Customer List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            
                            <div class="row">
                            	<div class="col-md-3"></div>
                            	<div class="col-md-3"></div>
                            	<div class="col-md-3"></div>
                            	<div class="col-md-3">
                            		<div class="form-group">
                            			<label class="font-weight-bold">Filter</label>
                            			<select class="form-control" id="filterStatus">
                            				<option value="">Select Status</option>
                            				<option value="1">Active</option>
                            				<option value="0">Inactive</option>
                            			</select>
                            		</div>
                            	</div>
                            </div>

							<div id="usersTable">

	                            <div class="button-container text-right mt-1 mb-1"></div>

	                            <div class="table-responsive">
	                                <table class="table table-striped dataex-html5-selectors_1">
	                                    <thead>
	                                        <tr>
	                                            <th>#</th>
	                                            <th>Name</th>
	                                            <!--<th>Email</th>-->
	                                            <th>Mobile No</th>

	                                            <th>Mode of Reg.</th>
	                                            <th>Status</th>
	                                            @if(
	                                            Auth()->user()->permissions->contains('name','admin.users.edit')
	                                            )
	                                            <th class="not-export-col">Action</th>
	                                            @endif

	                                        </tr>
	                                    </thead>
	                                    <tbody id="usersBody">
	                                        @if(count($users) > 0)
	                                        @foreach ($users as $user)
	                                        <tr>
	                                            <td>{{ $loop->iteration}}</td>
	                                            <td>{{ $user->first_name.' '.$user->last_name }}</td>
	                                            <!--<td>{{ $user->email }}</td>-->
	                                            <td>{{ $user->mobile_number }}</td>
	                                            <td>{{ $user->mode_of_registration }}</td>

	                                            <td>{!! $user->status ? '<p class="badge badge-pill badge-light-primary">
	                                                    Active</p>' : '<p class="badge badge-pill badge-light-danger">
	                                                    Inactive
	                                                </p>' !!}</td>

	                                            @if(
	                                            Auth()->user()->permissions->contains('name','admin.users.edit')
	                                            )
	                                            <td>
	                                                @if(
	                                                Auth()->user()->permissions->contains('name','admin.users.edit')
	                                                )
	                                                <a href="{{ route('admin.users.edit',$user->id) }}"
	                                                    class="btn btn-sm btn-primary"><i class="fa fa-pencil fa-lg"></i>
	                                                </a>
	                                                @endif

	                                            </td>
	                                            @endif
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