@extends('front.layout.main')

@section('section')
    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 56px;
            height: 28px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #05d89e
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #05d89e
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .notification-desc {
            color: #999;
            font-size: 14px;
        }

    </style>

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

                <li class="breadcrumb-item active">Settings</li>

            </ol>

        </div>

    </section>

    <section class="profile-page">

        <div class="container">

            <div class="row">

                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">

                    @include('front.layout.profile-sidebar')

                </div>

                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">

                    <h3>Settings</h3>

                    <div class="white-bg">

                        <div class="d-flex justify-content-sm-between align-items-center">

                            <div>
                                <h6>Notification</h6>
                                <span class="notification-desc">This will not affect any order updated</span>
                            </div>
                            <div>
                                <!-- Rounded switch -->
                                <label class="switch">
                                    <input type="checkbox" class="notification_status" name="notification"
                                        value="{{ auth()->user()->notification == 0 ? 1 : 0 }}"
                                        {{ auth()->user()->notification == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


@endsection


@section('footer')

    <script type="text/javascript">
        $(document).ready(function() {
            $(".notification_status").click(function() {
                var notification = $(this).val();
                var _token = "{{ csrf_token() }}";
                $form = $("<form method='post' action='{{ route('changeNotificationStatus') }}'></form>");
                $form.append('<input type="hidden" name="_token" value="' + _token + '">');
                $form.append('<input type="hidden" name="notification" value="' + notification + '">');
                // console.log($form);
                $('body').append($form);
                $form.submit();
            });
        });
    </script>

@endsection
