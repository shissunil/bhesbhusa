@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>

                <li class="breadcrumb-item active">
                    {{ $privacyPolicy->title ?? '' }}
                </li>

            </ol>

        </div>

    </section>

    <section class="cms-page">

        <div class="container-fluid">

            <div class="row">

                <div class="col-xl-12">

                    @if ($privacyPolicy)

                        <h2>
                            {{ $privacyPolicy->title }}
                        </h2>

                        {!! $privacyPolicy->discription !!}

                    @endif

                </div>

            </div>

        </div>

    </section>

@endsection
