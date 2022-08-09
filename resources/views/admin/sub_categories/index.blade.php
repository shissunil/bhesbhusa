@extends('layouts.admin')

@section('title')
Sub Category
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
                <h2 class="content-header-title float-left mb-0">Sub Category</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Sub Category
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
                        <h4 class="card-title">Sub Category List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if(Auth()->user()->permissions->contains('name','admin.sub-category.create'))
                            <a href="{{ route('admin.sub-category.create') }}" class="btn btn-primary float-right"><i
                                    class="fa fa-plus"></i> Add Sub Category</a>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            @if(
                                            Auth()->user()->permissions->contains('name','admin.sub-category.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.sub-category.destroy')
                                            )
                                            <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($sub_categories) > 0)
                                        @foreach ($sub_categories as $sub_category)
                                        <tr>

                                            <td>{{ $loop->iteration}}</td>
                                            <td>{{ $sub_category->name }}</td>
                                            <td>{{ $sub_category->category->name }}</td>
                                            <td>
                                                <img src="{{ asset('uploads/sub_category/'.$sub_category->image) }}"
                                                    class="img-thumbnail" height="100" width="100" />
                                            </td>
                                            <td>
                                                {!!
                                                $sub_category->status ?
                                                '<p class="badge badge-pill badge-light-primary">Active</p>'
                                                :
                                                '<p class="badge badge-pill badge-light-danger">Inactive</p>'
                                                !!}
                                            </td>

                                            @if(
                                            Auth()->user()->permissions->contains('name','admin.sub-category.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.sub-category.destroy')
                                            )

                                            <td>
                                                @if(Auth()->user()->permissions->contains('name','admin.sub-category.edit'))
                                                <a href="{{ route('admin.sub-category.edit',$sub_category->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa fa-pencil fa-lg"></i>
                                                </a>
                                                @endif

                                                {{-- <button type="button" class="btn btn-danger delete-record"
                                                    data-action="{{ route('admin.sub-category.destroy',$sub_category->id) }}">
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