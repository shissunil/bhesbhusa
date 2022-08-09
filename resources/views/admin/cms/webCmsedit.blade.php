@extends('layouts.admin')

@section('title')
Edit CMS
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/app-assets/dropify/css/dropify.min.css') }}">
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit CMS Management</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.web-cms-master.index') }}">CMS Management</a>
                        </li>
                        <li class="breadcrumb-item active">Edit CMS
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
                        <h4 class="card-title">Edit CMS Management</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.web-cms-master.update',$cmsData->id) }}" id="city_form"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Cms Image One</label>
                                            <input type="file" name="image_one" id="image_one" class="form-control dropify" data-default-file="{{ URL::asset('uploads/cms/'.$cmsData->image_one) }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Cms Image Two</label>
                                            <input type="file" name="image_two" id="image_two" class="form-control dropify" data-default-file="{{ URL::asset('uploads/cms/'.$cmsData->image_two) }}">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Cms Image Three</label>
                                            <input type="file" name="image_three" id="image_three" class="form-control dropify" data-default-file="{{ URL::asset('uploads/cms/'.$cmsData->image_three) }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Title One</label>
                                            <input type="text" name="title_one" class="form-control @error('title_one') is-invalid @enderror" id="title_one" placeholder="Title..." value="{{ $cmsData->title_one }}">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Title Two</label>
                                            <input type="text" name="title_two"
                                                class="form-control @error('title_two') is-invalid @enderror" id="title_two"
                                                placeholder="Title..." value="{{ $cmsData->title_two }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Title Three</label>
                                            <input type="text" name="title_three"
                                                class="form-control @error('title_three') is-invalid @enderror" id="title_three"
                                                placeholder="Title..." value="{{ $cmsData->title_three }}">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-12">
                                        <fieldset class="form-group">
                                            <label for="roundText" class="mb-1">Description One</label>
                                            <textarea name="discription_one" id="discription_one">{{ $cmsData->discription_one }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-12 col-12">
                                        <fieldset class="form-group">
                                            <label for="roundText" class="mb-1">Description Two</label>
                                            <textarea name="discription_two" id="discription_two">{{ $cmsData->discription_two }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-12 col-12">
                                        <fieldset class="form-group">
                                            <label for="roundText" class="mb-1">Description Three</label>
                                            <textarea name="discription_three" id="discription_three">{{ $cmsData->discription_three }}</textarea>
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
<script src="{{ URL::asset('assets/admin/app-assets/dropify/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('assets/admin/app-assets/ckeditor/ckeditor.js') }}"></script>
<script>
    $(document).ready(function(){
        CKEDITOR.replace( 'discription_one' );
        CKEDITOR.replace( 'discription_two' );
        CKEDITOR.replace( 'discription_three' );
        $('.dropify').dropify();
    });
</script>
@endsection