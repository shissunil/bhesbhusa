@extends('front.layout.main')

@section('section')

    <section class="inner-page-banner">

        <div class="container-fluid">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>

                <li class="breadcrumb-item active">
                    {{ $FAQs->title ?? '' }}
                </li>

            </ol>

        </div>

    </section>

    <section class="faq-page">

        <div class="container-fluid">

            <div class="row">

                <div class="col-xl-12">

                    @if ($FAQs)

                        <h2>
                            {{ $FAQs->title }}
                        </h2>

                        {!! $FAQs->discription !!}

                    @endif

                </div>

            </div>

        </div>

    </section>

@endsection
