@extends('front.layout.main')

@section('section')

<section class="login-shop">

  <div class="login-inner">

    <form method="post" action="{{ route('login.submit') }} ">

      @csrf

      @php
        $data = Session::get('data');
      @endphp

      <div class="login-view">

        <div class="logo"><img src="{{ asset('assets/front/images/black-bb.svg') }}" alt=""></div>

        <h2>Login with Mobile</h2>

        <p>We Will Send You An One Time Password On This Mobile Number</p>

        <div class="form-group">

          <input 
            type="text" 
            name="mobile_number" 
            class="form-control @error('mobile_number') is-invalid @enderror" 
            value="{{ old('mobile_number') ? old('mobile_number') : '' }}" 
            placeholder="Enter Mobile Number" required="" />

        </div>         
         
        <input type="hidden" name="first_name" value="{{ old('first_name') ? old('first_name') : $data['firstName'] ?? '' }}" />
        <input type="hidden" name="last_name" value="{{ old('last_name') ? old('last_name') : $data['lastName'] ?? '' }}" />
        <input type="hidden" name="email" value="{{ old('email') ? old('email') : $data['email'] ?? '' }}" />
        <input type="hidden" name="social_token" value="{{ old('social_token') ? old('social_token') : $data['social_token'] ?? '' }}" />
        <input type="hidden" name="social_media" value="{{ old('social_media') ? old('social_media') : $data['social_media'] ?? 0 }}" />        
        <input type="hidden" name="profile_pic" value="{{ old('profile_pic') ? old('profile_pic') : $data['profile_pic'] ?? '' }}" />        
    
        <div class="verify"><button type="submit" class="btn theme-btn">Login</button></div>

      </div>

    </form>

  </div>

</section>

@endsection