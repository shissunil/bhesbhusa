@extends('layouts.admin')

@section('title')
Reply
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Review Reply</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.review.review') }}">Review Master</a>
                        </li>
                        <li class="breadcrumb-item active">Add Reply
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
                        <h4 class="card-title">Add Reply</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.review.store') }}" id="area_form"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="row">

                                    <div class="col-md-12 col-12">
                                        <p><b>Review Message: </b> {{ $reviewData->message }}</p>
                                    </div>

                                    <div class="col-xl-12 col-md-12 col-12 mb-1">
                                        <input type="hidden" name="review_id" id="review_id" value="{{ $reviewData->id }}">
                                        <fieldset class="form-group">
                                            <label for="reply_message" class="mb-1">Reply Message <span class="text-danger h6">*</span></label>
                                            <textarea class="form-control @error('reply_message') is-invalid @enderror" name="reply_message" id="reply_message">{{ old('reply_message') }}</textarea>
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
        $("#area_form").validate({
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
                reply_message:{
                    required:true,
                },
            },

            messages: {
                reply_message:{
                    required:"Message Required",
                },
                
            },
            submitHandler: function(form) {
                form.submit();
            }   
        });
    });
</script>
@endsection