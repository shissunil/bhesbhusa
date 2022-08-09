@extends('layouts.admin')

@section('title')
Edit Cms
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Cms</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        {{-- <li class="breadcrumb-item">
                            <a href="{{ route('productList') }}">Product Master</a>
                        </li> --}}
                        <li class="breadcrumb-item active">Edit Cms
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
                        <h4 class="card-title">Edit Cms</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('updateCms',$cmsData->id) }}" id="city_form"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Cms Page</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="cms_page">
                                                <option value="0" {{ ($cmsData->cms_page == '0') ? 'selected' : '' }}>FAQs</option>
                                                <option value="1" {{ ($cmsData->cms_page == '1') ? 'selected' : '' }}>About us</option>
                                                <option value="2" {{ ($cmsData->cms_page == '2') ? 'selected' : '' }}>Terms & Conditions</option>
                                                <option value="3" {{ ($cmsData->cms_page == '3') ? 'selected' : '' }}>Privacy Policy</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Title</label>
                                            <input type="text" name="title"
                                                class="form-control @error('title') is-invalid @enderror" id="title"
                                                placeholder="Product Name..." value="{{ $cmsData->title}}">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Cms Status</label>
                                            <select class="select2 form-control select2-hidden-accessible" name="status">
                                                <option value="1" {{ ($cmsData->status == '1') ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ ($cmsData->status == '0') ? 'selected' : '' }}>InActive</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-12 col-12">
                                        <fieldset class="form-group">
                                            <label for="roundText">Description</label>
                                            <textarea name="discription" id="discription">{{ $cmsData->discription }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">Submit</button>
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
<script src="{{ URL::asset('assets/admin/app-assets/dropify/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('assets/admin/app-assets/ckeditor/ckeditor.js') }}"></script>
<script>
    $(document).ready(function(){
        CKEDITOR.replace( 'discription' );
        $('.dropify').dropify();
        // $("#city_form").validate({
        //     debug: true,
        //     errorClass: 'error',
        //     validClass: 'success',
        //     errorElement: 'span',
        //     highlight: function(element, errorClass, validClass) {
        //         $(element).addClass("is-invalid");
        //     },
        //     unhighlight: function(element, errorClass, validClass) {
        //         $(element).parents(".error").removeClass("error");
        //         $(element).removeClass("is-invalid");
        //     },
        //     rules:{                
        //         name:{
        //             required:true,
        //         },
        //         state_id:{
        //             required:true,
        //         },
                
        //     },

        //     messages: {
        //         name: {
        //             required: "Sub Category Name Required",
        //         },
        //         state_id:{
        //             required:"Select State",
        //         },
                
        //     },
        //     submitHandler: function(form) {
        //         form.submit();
        //     }   
        // });
    });
</script>
@endsection