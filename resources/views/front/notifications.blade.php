@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="#">Home</a></li>

                <li class="breadcrumb-item active">Notification</li>

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

                    <div class="dashboard-title">

                        <h3>Notification</h3>

                    </div>

                    <div class="white-bg notification">

                        <div class="custom-tab-main">

                            <ul class="nav custom-nav-tabs float-left" id="myTab" role="tablist">

                                <li class="nav-item">

                                    <a class="nav-link active" id="home" data-toggle="tab" href="#home-tab2" role="tab"
                                        aria-controls="home" aria-selected="true">All<span
                                            class="marker"></span></a>

                                </li>

                                <li class="nav-item">

                                    <a class="nav-link" id="profile" data-toggle="tab" href="#profile-tab2" role="tab"
                                        aria-controls="profile" aria-selected="false">Orders<span
                                            class="marker"></span></a>

                                </li>

                                <li class="nav-item">

                                    <a class="nav-link" id="contact" data-toggle="tab" href="#contact-tab2" role="tab"
                                        aria-controls="contact" aria-selected="false">Promotions<span
                                            class="marker"></span></a>

                                </li>

                            </ul>

                            <form action="{{ route('deleteNotification') }}" method="post" id="deleteNotification">
                                @csrf

                                <button class="float-right d-inline btn theme-btn">
                                    Clear All
                                </button>

                            </form>

                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="home-tab2" role="tabpanel"
                                    aria-labelledby="home-tab">

                                    <ul class="notification-list">

                                        @if (count($notificationList) > 0)

                                            @foreach ($notificationList as $notification)

                                                <li>
                                                    <div class="box">

                                                        <a href="javascript:;" class="delete delete_notification"
                                                            data-id="{{ $notification->id }}">
                                                            <i class="far fa-trash-alt"></i>
                                                        </a>

                                                        <p>{{ $notification->message }}</p>

                                                        <div class="date">
                                                            <img src="{{ asset('assets/front/images/notification-blue.svg') }}"
                                                                alt="">
                                                            {{ $notification->date }}
                                                        </div>

                                                    </div>

                                                </li>

                                            @endforeach

                                        @else

                                            <li>
                                                <div class="box text-center text-muted">
                                                    notifications not available.
                                                </div>
                                            </li>

                                        @endif

                                    </ul>

                                </div>

                                <div class="tab-pane fade" id="profile-tab2" role="tabpanel" aria-labelledby="profile-tab">

                                    <ul class="notification-list">

                                        @if (count($notificationList) > 0)

                                            @php
                                                $orderNotifications = $notificationList->where('notification_type', 'Order');
                                            @endphp

                                            @if (count($orderNotifications) > 0)

                                                @foreach ($orderNotifications as $notification)

                                                    <li>

                                                        <div class="box">

                                                            <a href="javascript:;" class="delete delete_notification"
                                                                data-id="{{ $notification->id }}">
                                                                <i class="far fa-trash-alt"></i>
                                                            </a>

                                                            <p>{{ $notification->message }}</p>

                                                            <div class="date">
                                                                <img src="{{ asset('assets/front/images/notification-blue.svg') }}"
                                                                    alt="">
                                                                {{ $notification->date }}
                                                            </div>

                                                        </div>

                                                    </li>

                                                @endforeach

                                            @else

                                                <li>
                                                    <div class="box text-center text-muted">
                                                        order notifications not available.
                                                    </div>
                                                </li>

                                            @endif

                                        @else

                                            <li>
                                                <div class="box text-center text-muted">
                                                    order notifications not available.
                                                </div>
                                            </li>

                                        @endif

                                    </ul>

                                </div>

                                <div class="tab-pane fade" id="contact-tab2" role="tabpanel" aria-labelledby="contact-tab">

                                    <ul class="notification-list">

                                        @if (count($notificationList) > 0)

                                            @php
                                                $offerNotifications = $notificationList->where('notification_type', 'Offer');
                                            @endphp

                                            @if (count($offerNotifications) > 0)

                                                @foreach ($offerNotifications as $notification)

                                                    <li>

                                                        <div class="box">

                                                            <a href="javascript:;" class="delete delete_notification"
                                                                data-id="{{ $notification->id }}">
                                                                <i class="far fa-trash-alt"></i>
                                                            </a>

                                                            <p>{{ $notification->message }}</p>

                                                            <div class="date">
                                                                <img src="{{ asset('assets/front/images/notification-blue.svg') }}"
                                                                    alt="">
                                                                {{ $notification->date }}
                                                            </div>

                                                        </div>

                                                    </li>

                                                @endforeach

                                            @else

                                                <li>
                                                    <div class="box text-center text-muted">
                                                        promotional notifications not available.
                                                    </div>
                                                </li>

                                            @endif

                                        @else

                                            <li>
                                                <div class="box text-center text-muted">
                                                    promotional notifications not available.
                                                </div>
                                            </li>

                                        @endif

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

    </section>

@endsection
