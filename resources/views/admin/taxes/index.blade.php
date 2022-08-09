@extends('layouts.admin')

@section('title')
Tax
@endsection

@section('head')
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/admin/app-assets/vendors/css/tables/datatable/datatables.min.css')  }}">
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Tax</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Tax
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
                        <h4 class="card-title">Tax List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if(Auth()->user()->permissions->contains('name','admin.tax.create'))
                            <a href="{{ route('admin.tax.create') }}" class="btn btn-primary float-right"><i
                                    class="fa fa-plus"></i> Add Tax</a>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped zero-configuration ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            @if(
                                            Auth()->user()->permissions->contains('name','admin.tax.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.tax.destroy')
                                            )
                                            <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($taxes) > 0)
                                        @foreach ($taxes as $tax)
                                        <tr>
                                            <td>{{ $loop->iteration}}</td>
                                            <td>{{ $tax->name }}</td>
                                            <td>{{ $tax->amount }}</td>

                                            <td>{!! $tax->status ? '<p class="badge badge-pill badge-light-primary">
                                                    Active</p>' : '<p class="badge badge-pill badge-light-danger">
                                                    Inactive
                                                </p>' !!}</td>

                                            @if(
                                            Auth()->user()->permissions->contains('name','admin.tax.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.tax.destroy')
                                            )

                                            <td>
                                                @if(Auth()->user()->permissions->contains('name','admin.tax.edit'))
                                                <a href="{{ route('admin.tax.edit',$tax->id) }}"
                                                    class="btn btn-sm btn-primary"><i
                                                        class="fa fa-pencil fa-lg"></i></a>
                                                @endif

                                                {{-- <button type="button" class="btn btn-danger delete-record"
                                                    data-action="{{ route('admin.tax.destroy',$tax->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                    Delete
                                                </button> --}}

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
@endsection