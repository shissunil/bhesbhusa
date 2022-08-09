@extends('layouts.admin')

@section('title')
Edit Ticket Reason
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Ticket Reason</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.ticket-reasons.index') }}">Ticket Reason</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Ticket Reason
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
                        <h4 class="card-title">Edit Ticket Reason</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.ticket-reasons.update',$ticket_reason->id) }}" id="ticket_reason_form"
                                enctype="multipart/form-data">

                                @csrf

                                @method('PUT')

                                <div class="row">

                                    <div class="col-xl-12 col-md-12 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="reason_description" class="mb-1">Ticket Reason <span class="text-danger h6">*</span></label>
                                            <textarea name="reason_description"
                                                class="form-control @error('reason_description') is-invalid @enderror" id="reason_description"
                                                placeholder="Ticket Reason...">{{ old('reason_description') ? old('reason_description') : $ticket_reason->reason_description }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Reason For <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="reason_for">
                                                <option value="1" {{ ($ticket_reason->reason_for == '1') ? 'selected' : '' }}>Cancel Order</option>
                                                <option value="2" {{ ($ticket_reason->reason_for == '2') ? 'selected' : '' }}>Return Order</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label class="mb-1 d-block">Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input" value="1" id="customSwitch1" {{ ( old('status') || $ticket_reason->status ) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customSwitch1">
                                                </label>
                                                <span class="switch-label"></span>
                                            </div>
                                        </fieldset>
                                    </div>

                                </div>

                                <button type="submit"
                                    class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">
                                    Submit
                                </button>

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

<script>
    $(document).ready(function(){
        $("#ticket_reason_form").validate({
            debug: true,
            errorClass: 'error',
            validClass: 'success',
            errorElement: 'span',
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents(".error").removeClass("error");
                $(element).removeClass("is-invalid");
            },
            rules:{                
                reason_description:{
                    required:true,
                },                
            },
            messages: {
                reason_description: {
                    required: "Ticket Reason Required",
                },                
            },
            submitHandler: function(form) {
                form.submit();
            }   
        });
    });
</script>
@endsection