@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>

                <li class="breadcrumb-item active">
                    {{ $helpCenter->title ?? '' }}
                </li>

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

                    @if ($helpCenter)

                        <div class="dashboard-title">

                            <h3>{{ $helpCenter->title }}</h3>

                        </div>

                        <div class="white-bg my-address">

                            {!! $helpCenter->discription !!}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>

@endsection
