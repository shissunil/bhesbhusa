@extends('layouts.admin')

@section('title')
Add Tax
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Tax</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.tax.index') }}">Tax</a>
                        </li>
                        <li class="breadcrumb-item active">Add Tax
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
                        <h4 class="card-title">Add Tax</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.tax.store') }}" id="tax_form"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="row">

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Tax Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Tax Name..." value="{{ old('name') }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="amount" class="mb-1">Tax Amount <span class="text-danger h6">*</span></label>
                                            <input type="text" name="amount"
                                                class="form-control @error('amount') is-invalid @enderror" id="amount"
                                                placeholder="Tax Amount..." value="{{ old('amount') }}" min="1" onkeypress="validate()">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label class="mb-1 d-block">Tax Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="status" class="custom-control-input" value="1" id="customSwitch1" {{ old('status') ? 'checked' : '' }}>
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
        $("#tax_form").validate({
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
                name:{
                    required:true,
                },
                amount:{
                    required:true,
                },
            },

            messages: {
                name: {
                    required: "Tax Name Required",
                },
                amount: {
                    required: "Tax Amount Required",
                },
            },
            submitHandler: function(form) {
                form.submit();
            }   
        });
    });
</script>
@endsection