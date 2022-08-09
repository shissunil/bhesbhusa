@extends('layouts.admin')
@section('title')
Category
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
                <h2 class="content-header-title float-left mb-0">Super Category</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">SuperCategory
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle waves-effect waves-light"
                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="feather icon-settings"></i>
                </button>
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
                        <h4 class="card-title">Super Category List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                             <a href="{{ route('addSuperCategoryList') }}" class="btn btn-primary float-right"><i class="fa fa-plus"></i> Add </a>
                            <div class="table-responsive">
                                <table class="table table-striped zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- {{ dd($superCategoryList); }} --}}
                                        @foreach ($superCategoryList as $superCategory)
                                        <tr>
                                            <td>{{ $loop->iteration}}</td>
                                            <td>{{ $superCategory->supercategory_name }}</td>
                                            {{-- <td>
                                                <img src="{{ asset('uploads/category/'.$superCategory->supercategory_image) }}" class="img-thumbnail" height="100" width="100" />
                                            </td> --}}
                                             <td><img src="{{ URL::asset('uploads/category/'.$superCategory->supercategory_image) }}" width="100px" height="100px"></td>
                                            <td>
                                                {!! $superCategory->supercategory_status ? '
                                                <p
                                                    class="badge badge-pill badge-light-primary">
                                                    Active
                                                </p>
                                                ' : '
                                                <p class="badge badge-pill badge-light-danger">
                                                    Inactive
                                                </p>
                                                ' !!}
                                            </td>
                                            <td>
                                                <a href="{{ route('editSuperCategoryList',$superCategory->id) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-pencil fa-lg"></i></a>

                                                <a href="{{ route('deleteSuperCategoryList',$superCategory->id) }}" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash fa-lg"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
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