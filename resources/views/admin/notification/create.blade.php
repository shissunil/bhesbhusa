@extends('layouts.admin')

@section('title')
Send Notification
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Send Notification</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.notification.create') }}">Send Notification</a>
                        </li>
                        <li class="breadcrumb-item active">Send Noification
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
                        <h4 class="card-title">Send Notification</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.notification.send') }}" id="notification_form"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Send Notification To <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="notification_to" id="notification_to" required>
                                                <option value="2">--SELECT--</option>
                                                <option value="0">Customer</option>
                                                <option value="1">Delivery Boy</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row hidden" id="customer_div">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1" id="category_type_div">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Notification Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="customer_category_type">
                                                <option value="1">Order</option>
                                                <option value="2">Promotion</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Customer </label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="user_id[]" multiple="multiple" id="user_id">
                                                @if (count($userList) > 0)
                                                    <option value="-1" class="select_all">SELECT ALL</option>
                                                    @foreach ($userList as $user)
                                                        <option value="{{ $user->id }}">{{ $user->first_name.' '. $user->last_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row hidden" id="delivery_div">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1" id="category_type_div">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Notification Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="delivery_category_type">
                                                <option value="3">Order Assignee</option>
                                                <option value="4">Order Delivered</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <fieldset class="form-group">
                                        <label for="offer_description" class="mb-1">Notification Message <span class="text-danger h6">*</span></label>
                                        <textarea name="notification_message" class="form-control " id="notification_message" placeholder="Message..." required></textarea>
                                    </fieldset>
                                </div>
                                <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light"> Submit </button>
                            <form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@section('footer')

<script type="text/javascript">
    $('#notification_form').on('change','#notification_to',function(){
        var notificationTo = this.value;
        if (notificationTo == '0')
        {
            $('#customer_div').removeClass('hidden');
            $('#delivery_div').addClass('hidden');
        }
        if (notificationTo == '1')
        {
            $('#delivery_div').removeClass('hidden');
            $('#customer_div').addClass('hidden');
        }
    });

    $('#user_id').on("select2:select", function (e){
        var data = e.params.data.text;
        if(data=='SELECT ALL' && e.params.data.selected == true)
        {
            $("#user_id > option").prop("selected","selected");
            $("#user_id").trigger("change");
        }
        if(data=='SELECT ALL' && e.params.data.selected == false)
        {
            $("#user_id > option").prop("selected",false);
        }
    });
</script>
@endsection