@extends('layouts.admin')

@section('title')
Edit Offer
@endsection

@section('content')
@php
    $subCategoryIdArr = array_filter(explode(',',$offer->sub_category_id));
@endphp
<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Offer</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.offers.index') }}">Offers Management</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Offer
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
                        <h4 class="card-title">Edit Offer</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <form method="post" action="{{ route('admin.offers.update',$offer->id) }}" id="offer_form"
                                enctype="multipart/form-data">

                                @csrf

                                @method('PUT')

                                <div class="row">

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="offer_name" class="mb-1">Offer Name <span class="text-danger h6">*</span></label>
                                            <input type="text" name="offer_name"
                                                class="form-control @error('offer_name') is-invalid @enderror" id="offer_name"
                                                placeholder="Offer Name..." value="{{ old('offer_name') ? old('offer_name') : $offer->offer_name }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="offer_code" class="mb-1">Offer Code <span class="text-danger h6">*</span></label>
                                            <input type="text" name="offer_code"
                                                class="form-control @error('offer_code') is-invalid @enderror" id="offer_code"
                                                placeholder="Offer Code..." value="{{ old('offer_code') ? old('offer_code') : $offer->offer_code }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="start_date" class="mb-1">Start Date <span class="text-danger h6">*</span></label>
                                            <input type="date" name="start_date"
                                                class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                                placeholder="Start Date..." value="{{ old('start_date') ? old('start_date') : $offer->start_date }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="end_date" class="mb-1">End Date <span class="text-danger h6">*</span></label>
                                            <input type="date" name="end_date"
                                                class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                                placeholder="End Date..." value="{{ old('end_date') ? old('end_date') : $offer->end_date }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Offer Apply on <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="is_global" id="is_global" required>
                                                <option>--SELECT--</option>
                                                <option value="1" {{ ($offer->is_global == '1') ? 'selected' : '' }}>Global</option>
                                                <option value="0" {{ ($offer->is_global == '0') ? 'selected' : '' }}>Sub Category</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1 hidden" id="sub_category_id">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Select Sub Category <span class="text-danger h6">*</span></label>
                                            <select class="select2 form-control select2-hidden-accessible"
                                                name="sub_category_id[]"  multiple="multiple">
                                                @if(count($subCategoryList)>0)
                                                    @foreach ($subCategoryList as $subCategory)
                                                        <option value="{{ $subCategory->id }}" {{ in_array($subCategory->id,$subCategoryIdArr)?'selected':'' }}>{{ ucwords($subCategory->name) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1 hidden" id="total_amount">
                                        <fieldset class="form-group">
                                            <label for="end_date" class="mb-1">Minimum Amount <span class="text-danger h6">*</span></label>
                                            <input type="text" name="total_amount"
                                                class="form-control @error('total_amount') is-invalid @enderror"
                                                placeholder="EX : purchase 10000 NR then 10% off" value="{{ $offer->total_amount }}" min="1" onkeypress="validate()">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="end_date" class="mb-1">Offer Discount (%) <span class="text-danger h6">*</span></label>
                                            <input type="text" name="offer_discount"
                                                class="form-control @error('offer_discount') is-invalid @enderror" id="offer_discount"
                                                placeholder="ex: 10%" value="{{ $offer->offer_discount }}" min="1" max="100" onkeypress="validate()">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="end_date" class="mb-1">Offer How many user use <span class="text-danger h6">*</span></label>
                                            <input type="text" name="total_use"
                                                class="form-control @error('total_use') is-invalid @enderror" id="total_use"
                                                placeholder="ex: 10" value="{{ $offer->total_use }}" min="1" onkeypress="validate()" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="offer_description" class="mb-1">Offer Description <span class="text-danger h6">*</span></label>
                                            <textarea name="offer_description"
                                                class="form-control @error('offer_description') is-invalid @enderror" id="offer_description"
                                                placeholder="Offer Description...">{{ old('offer_description') ? old('offer_description') : $offer->offer_description }}</textarea>
                                        </fieldset>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label class="mb-1 d-block">Offer Status</label>
                                            <div class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" name="offer_status" class="custom-control-input" value="1" id="customSwitch1" {{ ( old('offer_status') || $offer->offer_status ) ? 'checked' : '' }}>
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
        $("#offer_form").validate({
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
                offer_name:{
                    required:true,
                },  
                offer_description:{
                    required:true,
                }, 
                offer_code:{
                    required:true,
                }, 
                start_date:{
                    required:true,
                }, 
                end_date:{
                    required:true,
                }, 
                             
            },
            messages: {
                offer_name: {
                    required: "Offer Name Required",
                },  
                offer_description:{
                    required: "Offer Description Required",
                }, 
                start_date:{
                    required: "Offer Start Date Required",
                }, 
                end_date:{
                    required: "Offer End Date Required",
                }, 
                offer_code:{
                    required: "Offer Code Required",
                },               
            },
            submitHandler: function(form) {
                form.submit();
            }   
        });

        var isGlobal = $('#is_global').find(":selected").val();
        // alert(isGlobal);
        if (isGlobal == '0')
        {
            $('#sub_category_id').removeClass('hidden');
            $('#total_amount').addClass('hidden');
        }
        if (isGlobal == '1')
        {
            $('#sub_category_id').addClass('hidden');
            $('#total_amount').removeClass('hidden');
        }
    });
    $('#offer_form').on('change', '#is_global', function() {
        // alert(this.value);
        var isGlobal = this.value;
        if (isGlobal == '0')
        {
            $('#sub_category_id').removeClass('hidden');
            $('#total_amount').addClass('hidden');
        }
        if (isGlobal == '1')
        {
            $('#sub_category_id').addClass('hidden');
            $('#total_amount').removeClass('hidden');
        }
    });
</script>
@endsection