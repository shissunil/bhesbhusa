@extends('layouts.admin')

@section('title')
Manage Banner
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
                <h2 class="content-header-title float-left mb-0">Manage Best Deals</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Manage Best Deals
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
                        <h4 class="card-title">Best Deals List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if (count($bestDeals) < 5)
                                @if(Auth()->user()->permissions->contains('name','admin.bestdeals.create'))
                                    <a href="{{ route('admin.bestdeals.create') }}" class="btn btn-primary float-right"><i
                                        class="fa fa-plus"></i> Add BestDeal</a>
                                @endif
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            {{-- <th>Offer</th> --}}
                                            <th>Status</th>
                                            @if(
                                            Auth()->user()->permissions->contains('name','admin.bestdeals.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.bestdeals.destroy')
                                            )
                                            <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($bestDeals) > 0)
                                        @foreach ($bestDeals as $bestDeal)
                                        <tr>

                                            <td>{{ $loop->iteration}}</td>

                                            <td>
                                                <img src="{{ asset('uploads/banners/'.$bestDeal->image) }}"
                                                    class="img-thumbnail" height="100" width="100" />
                                            </td>

                                            {{-- <td>{{ $banner->offer->offer_name }}</td> --}}

                                            <td>
                                                {!!
                                                $bestDeal->status ?
                                                '<p class="badge badge-pill badge-light-primary">Active</p>'
                                                :
                                                '<p class="badge badge-pill badge-light-danger">Inactive</p>'
                                                !!}
                                            </td>
                                            
                                            @if(
                                            Auth()->user()->permissions->contains('name','admin.bestdeals.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.bestdeals.destroy')
                                            )
                                            <td>
                                                @if(Auth()->user()->permissions->contains('name','admin.bestdeals.edit'))
                                                <a href="{{ route('admin.bestdeals.edit',$bestDeal->id) }}"
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
@endsection