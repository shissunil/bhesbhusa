@extends('layouts.admin')

@section('title')
Customer
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
                <h2 class="content-header-title float-left mb-0">Customer</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Customer
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
                        <h4 class="card-title">Product List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                           <form method="post" action="#" id="cancel_list_form">
                                <div class="row">
                                    <div class="col-md-3 offset-md-9">
                                        <fieldset class="form-group">
                                            <label for="status">Filter By Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1">Active</option>
                                                <option value="0">InActive</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>First Name </th>
                                                <th>Last Name </th>
                                                <th>Gender</th>
                                                <th>Mobile Number</th>
                                                <th>Mode of registration</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($userList as $user)
                                            <tr>
                                                <td>{{ $loop->iteration}}</td>
                                                <td>{{ $user->first_name }}</td>
                                                <td>{{ $user->last_name }}</td>
                                                <td>{{ $user->gender }}</td>
                                                <td>{{ $user->mobile_number }}</td>
                                                <td>{{ $user->mode_of_registration }}</td>
                                               {{--  <td>{!! $user->status ? '<p
                                                        class="badge badge-pill badge-light-primary">
                                                        Active</p>' : '<p class="badge badge-pill badge-light-danger">
                                                        Inactive
                                                    </p>' !!}
                                                </td> --}}
                                                <td>
                                                    <a href="{{ route('changeUserStatus',$user->id) }}"><div class="badge {{ ($user->status == '1') ? 'badge-success' : 'badge-danger' }}">{{ ($user->status == '1') ? 'Active' : 'InActive' }}</div></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
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
    $(document).ready(function() {
        $('#cancel_list_form').on('change', '#status', function(){
            // console.log(this.value);
            var status = 'status='+ this.value;
            var route = "{{ route('customerStatusWiseData') }}";
            $.ajax({
                type: "GET",
                url: route,
                data: status,
                cache: false,
                success: function(result)
                {
                    // console.log(result);
                    $('#cancel_table tbody').html(result);
                    return true;
                }
            });
        })
    });
</script>
@endsection