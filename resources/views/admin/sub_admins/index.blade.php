@extends('layouts.admin')



@section('title')

Admin Management

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



    .sub-admin-label {

        font-size: 14px;

        padding: 8px 14px;

    }

</style>

@endsection



@section('content')



<div class="content-header row">



    <div class="content-header-left col-md-9 col-12 mb-2">

        <div class="row breadcrumbs-top">

            <div class="col-12">

                <h2 class="content-header-title float-left mb-0">Admin Management</h2>

                <div class="breadcrumb-wrapper col-12">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>

                        </li>

                        <li class="breadcrumb-item active">Admin Management

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

                        <h4 class="card-title">Sub Admin List</h4>

                    </div>

                    <div class="card-content">

                        <div class="card-body">

                            <div class="button-container text-right mt-1 mb-1"></div>

                            @if(Auth()->user()->permissions->contains('name','admin.sub_admins.create'))

                            <a href="{{ route('admin.sub_admins.create') }}" class="btn btn-primary float-right"><i

                                    class="fa fa-plus"></i> Add Sub Admin</a>

                            @endif

                            <div class="table-responsive">

                                <table class="table table-striped zero-configuration">

                                    <thead>

                                        <tr>

                                            <th>#</th>

                                            <th>Name</th>

                                            <th>Email</th>

                                            {{-- <th>Role</th> --}}

                                            <th>Status</th>



                                            @if(

                                            Auth()->user()->permissions->contains('name','admin.sub_admins.edit')

                                            ||

                                            Auth()->user()->permissions->contains('name','admin.sub_admins.destroy')

                                            )

                                            <th>Action</th>

                                            @endif

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @if(count($sub_admins) > 0)

                                        @foreach ($sub_admins as $user)

                                        <tr>

                                            <td>{{ $loop->iteration}}</td>

                                            <td>{{ $user->name }}</td>

                                            <td>{{ $user->email }}</td>

                                            {{-- <td>

                                                <p class="badge badge-inverse sub-admin-label">{{

                                                    ucwords(str_replace('-',' ','abc')) }}</p>

                                            </td> --}}



                                            <td>{!! $user->status ? '<p class="badge badge-pill badge-light-primary">

                                                    Active</p>' : '<p class="badge badge-pill badge-light-danger">

                                                    Inactive

                                                </p>' !!}</td>



                                            @if(

                                            Auth()->user()->permissions->contains('name','admin.sub_admins.edit')

                                            ||

                                            Auth()->user()->permissions->contains('name','admin.sub_admins.destroy')

                                            )



                                            <td>



                                                @if(Auth()->user()->permissions->contains('name','admin.sub_admins.edit'))

                                                <a href="{{ route('admin.sub_admins.edit',$user->id) }}"

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

    dom: 'Bfrtip',

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

</script>

@endsection