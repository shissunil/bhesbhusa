@extends('front.layout.main')

@section('section')

<section class="login-shop">

    <div class="login-inner">

      <div class="login-view">

        <div class="logo"><img src="{{ asset('assets/front/images/black-bb.svg') }}" alt=""></div>

        <h2>Login with Mobile</h2>

        <p>We Will Send You An One Time Password On This Mobile Number</p>

        <form method="post" action="{{ route('login.submit') }} ">

        @csrf
        
        <div class="form-group">

          <input 
            type="text" 
            name="mobile_number" 
            class="form-control @error('mobile_number') is-invalid @enderror" 
            value="{{ old('mobile_number') ? old('mobile_number') : '' }}" 
            placeholder="Enter Mobile Number" required="" />

          <input type="hidden" name="social_media" value="0" >


        </div>
        
        </form>

        <div class="other-login"><span>Or</span></div>

        <div class="login-btn">

          <a href="{{ route('login.facebook.redirect') }}" title="" class="btn"><img src="{{ asset('assets/front/images/login-facebook.svg') }}" alt="">Facebook</a>

          <a href="{{ route('login.google.redirect') }}" title="Login with Google" class="btn" ><img src="{{ asset('assets/front/images/login-google.svg') }}" alt="">Google</a>

        </div>

        <div class="apple-login"><a href="#" class="btn" title=""><img src="{{ asset('assets/front/images/login-apple.svg') }}" alt="">Continue With Apple</a></div>

        <div class="guest-user"><a href="{{ route('index') }}">Guest User</a></div>

        <div class="term-condition">

          <p>By clicking on Sign up you agree to</p>

          <a href="{{ route('terms_and_conditions') }}">Terms & Conditions</a>

        </div>

      </div>

    </div>

  </section>

@endsection