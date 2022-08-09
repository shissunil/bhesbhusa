@extends('layouts.admin')

@section('title')
Product Management
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
                <h2 class="content-header-title float-left mb-0">Product Management</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Product Management
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
                        <h4 class="card-title">Product images</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            {{-- @if(Auth()->user()->permissions->contains('name','admin.product.image.create')) --}}
                            <a href="{{ route('admin.product.image.create',$productData->id) }}" class="btn btn-primary float-right"><i
                                    class="fa fa-plus"></i> Add</a>
                            {{-- @endif --}}
                            <div class="table-responsive">
                                <table class="table table-striped zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <td>Color</td>
                                           {{--  @if(
                                            Auth()->user()->permissions->contains('name','admin.product.image.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.product.image.destroy')
                                            ) --}}
                                            <th>Action</th>
                                            {{-- @endif --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($productImages) > 0)
                                        @foreach ($productImages as $product)
                                        <tr>
                                            <td>{{ $loop->iteration}}</td>
                                            <td>
                                                <img src="{{ asset('uploads/product/'.$product->product_image) }}"
                                                    class="img-thumbnail" height="100" width="100" />
                                            </td>
                                            <td>
                                                @if ($product->hasOneColor)
                                                    <p class="p-1 border" style="background: {{ $product->hasOneColor->code }}"></p>
                                                @endif
                                            </td>
                                            {{-- @if(
                                            Auth()->user()->permissions->contains('name','admin.product.edit')
                                            ||
                                            Auth()->user()->permissions->contains('name','admin.product.destroy')
                                            ) --}}
                                                <td>
                                                    {{-- @if(Auth()->user()->permissions->contains('name','admin.product.image.edit')) --}}
                                                    <a href="{{ route('admin.product.image.edit',$product->id) }}"
                                                        class="btn btn-primary btn-sm" title="Edit"><i
                                                            class="fa fa-pencil fa-lg"></i>
                                                    </a>
                                                    {{-- @endif --}}
                                                    {{-- @if(Auth()->user()->permissions->contains('name','admin.product.image.destroy')) --}}
                                                    <button type="button" class="btn btn-sm btn-danger delete-record"
                                                        data-action="{{ route('admin.product.image.destroy',$product->id) }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    {{-- @endif --}}
                                                </td>
                                            {{-- @endif --}}
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