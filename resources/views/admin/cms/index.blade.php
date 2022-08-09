@extends('layouts.admin')

@section('title')
CMS Management
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
                <h2 class="content-header-title float-left mb-0">Cms Management</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Cms Management
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
                        <h4 class="card-title">CMS Management</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if(Auth()->user()->permissions->contains('name','admin.cms-master.create'))
                            <a href="{{ route('admin.cms-master.create') }}" class="btn btn-primary float-right"><i class="fa fa-plus"></i> Add</a>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cms Page</th>
                                            <th>Cms Page For</th>
                                            <th>Title</th>
                                            {{-- <th>Discription</th> --}}
                                            <th>Status</th>
                                            @if(
                                            Auth()->user()->permissions->contains('name','admin.cms-master.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.cms-master.destroy')
                                            )
                                            <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cmsList as $cms)
                                        <tr>
                                            <td>{{ $loop->iteration}}</td>
                                            {{-- <td>{{ $cms->cms_page }}</td> --}}
                                            <td>
                                                @if ($cms->cms_page == 0)
                                                <label class="title">FAQs</label>
                                                @elseif($cms->cms_page == 1)
                                                <label class="title">About us</label>
                                                @elseif($cms->cms_page == 2)
                                                <label class="title">Terms&Conditions</label>
                                                @elseif($cms->cms_page == 3)
                                                <label class="title">Privacy Policy</label>
                                                @elseif($cms->cms_page == 4)
                                                <label class="title">Return Policy</label>
                                                @elseif($cms->cms_page == 5)
                                                <label class="title">Help Center</label>
                                                @elseif($cms->cms_page == 6)
                                                <label class="title">About Us</label>
                                                @elseif($cms->cms_page == 7)
                                                <label class="title">Terms & condition</label>
                                                @elseif($cms->cms_page == 8)
                                                <label class="title">Privacy Policy</label>
                                                @endif

                                            </td>
                                            <td>
                                                @if ($cms->app_type == '1')
                                                    <label class="title">Customer App</label>
                                                @endif
                                                @if ($cms->app_type == '2')
                                                    <label class="title">Delivery App</label>
                                                @endif
                                            </td>
                                            <td>{{ $cms->title }}</td>
                                            {{-- <td>{{ $cms->discription }}</td> --}}
                                            <td>{!! $cms->status ? '<p class="badge badge-pill badge-light-primary">
                                                    Active</p>' : '<p class="badge badge-pill badge-light-danger">
                                                    Inactive
                                                </p>' !!}
                                            </td>

                                            @if(
                                            Auth()->user()->permissions->contains('name','admin.cms-master.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.cms-master.destroy')
                                            )
                                            <td>
                                                @if(Auth()->user()->permissions->contains('name','admin.cms-master.edit'))
                                                <a href="{{ route('admin.cms-master.edit',$cms->id) }}"
                                                    class="btn btn-sm btn-primary"><i class="fa fa-pencil fa-lg"></i>
                                                </a>
                                                @endif
                                                <!-- @if(Auth()->user()->permissions->contains('name','admin.cms-master.destroy'))
                                                <button type="button" class="btn btn-sm btn-danger delete-record"
                                                    data-action="{{ route('admin.cms-master.destroy',$cms->id) }}">
                                                    <i class="fa fa-trash -lg"></i>
                                                </button>
                                                @endif -->
                                            </td>
                                            @endif

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