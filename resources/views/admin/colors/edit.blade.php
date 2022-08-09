@extends('layouts.admin')



@section('title')

Edit Color

@endsection


@section('head')

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.css"
    integrity="sha512-HcfKB3Y0Dvf+k1XOwAD6d0LXRFpCnwsapllBQIvvLtO2KMTa0nI5MtuTv3DuawpsiA0ztTeu690DnMux/SuXJQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

@endsection

@section('content')



<div class="content-header row">



    <div class="content-header-left col-md-9 col-12 mb-2">

        <div class="row breadcrumbs-top">

            <div class="col-12">

                <h2 class="content-header-title float-left mb-0">Edit Color</h2>

                <div class="breadcrumb-wrapper col-12">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>

                        </li>

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.color.index') }}">Colors</a>

                        </li>

                        <li class="breadcrumb-item active">Edit Color

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

                        <h4 class="card-title">Edit Color</h4>

                    </div>

                    <div class="card-content">



                        <div class="card-body">



                            <form method="post" action="{{ route('admin.color.update',$color->id) }}" id="color_form"
                                enctype="multipart/form-data">

                                @method('PUT')

                                @csrf



                                <div class="row">



                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="color" class="mb-1">Color Name <span class="text-danger h6">*</span></label>

                                            <input type="text" name="color"
                                                class="form-control @error('color') is-invalid @enderror" id="color"
                                                placeholder="Color Name..." value="{{ $color->color }}">

                                        </fieldset>

                                    </div>


                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label for="code" class="mb-1">Color Code <span class="text-danger h6">*</span></label>

                                            <div class="input-group colorpicker-element">

                                                <input name="code"
                                                    class="form-control input-block color-io @error('code') is-invalid @enderror"
                                                    value="{{ $color->code }}"  />

                                                <span class="input-group-append">
                                                    <span id="color_picker" class="input-group colorpicker-element"
                                                        title="Using input value">
                                                        <input type="hidden" class="form-control input-lg"
                                                        value="{{ $color->code }}" >
                                                        <span class="input-group-text colorpicker-input-addon"
                                                            data-original-title="" title="" tabindex="0">
                                                            <i style="background: {{ $color->code }}"></i>
                                                        </span>
                                                    </span>
                                                </span>

                                            </div>

                                        </fieldset>

                                    </div>





                                    <div class="col-xl-6 col-md-6 col-12 mb-1">

                                        <fieldset class="form-group">

                                            <label class="mb-1 d-block">Color Status</label>

                                            <div class="custom-control custom-switch custom-control-inline">

                                                <input type="checkbox" name="status" class="custom-control-input"
                                                    value="1" id="customSwitch1" {{ ($color->status==1) ? 'checked' : '' }}>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js"
    integrity="sha512-94dgCw8xWrVcgkmOc2fwKjO4dqy/X3q7IjFru6MHJKeaAzCvhkVtOS6S+co+RbcZvvPBngLzuVMApmxkuWZGwQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>
    $(document).ready(function(){

        $("#color_form").validate({

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

                color:{

                    required:true,

                },

            },



            messages: {

                color: {

                    required: "Color Name Required",

                },

            },

            submitHandler: function(form) {

                form.submit();

            }   

        });

    });

</script>

<script type="text/javascript">
    $(document).ready(function () {
        let color_picker = $('#color_picker').colorpicker()
        .on('colorpickerCreate', function (e) {
            // initialize the input on colorpicker creation
            var io = $('.color-io');

            io.val(e.color.string());

            io.on('change keyup', function () {
                e.colorpicker.setValue(io.val());
            });
        })
        .on('colorpickerChange', function (e) {
            var io = $('.color-io');

            if (e.value === io.val() || !e.color || !e.color.isValid()) {
                // do not replace the input value if the color is invalid or equals
                return;
            }

            io.val(e.color.string());
        });
    });

</script>


@endsection