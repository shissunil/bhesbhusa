@extends('front.layout.main')

@section('section')
<section class="login-shop">

    <div class="login-inner">

      <form method="post" action="{{ route('your_self.submit') }} ">

      @csrf

      @php
        $mobile_number = Session::get('mobile_number');
      @endphp

      <div class="about-your-self"> 

        <div class="logo"><img src="{{ asset('assets/front/images/black-bb.svg') }}" alt=""></div>     

        <h2>Tell us about yourself</h2>

        <p>This info will help us tailor your experience</p>

        <div class="form-group">

          <input 
          type="text" 
          name="first_name" 
          class="form-control @error('first_name') is-invalid @enderror" 
          value="{{ old('first_name') ? old('first_name') : '' }}" 
          placeholder="Enter First Name" />

        </div>

        <div class="form-group">

          <input 
          type="text" 
          name="last_name" 
          class="form-control @error('last_name') is-invalid @enderror" 
          value="{{ old('last_name') ? old('last_name') : '' }}" 
          placeholder="Enter Last Name" />

        </div>

        <div class="form-group"> 

          <label>Gender</label>

          <div class="gender">                              

            <label class="radio-box">Female<input type="radio" name="gender" value="female" {{ (old('gender')=='female') ? 'checked' : '' }} ><span class="checkmark"></span></label>

            <label class="radio-box">Male<input type="radio" name="gender" value="male" {{ (old('gender')=='male') ? 'checked' : '' }}><span class="checkmark"></span></label>

          </div>

        </div>  

        <input type="hidden" name="mobile_number" value="{{ old('mobile_number') ? old('mobile_number') : $mobile_number ?? '' }}" />            

        <div class="verify"><button type="submit" class="btn theme-btn">Done</button></div>

        <div class="skip"><a href="{{ route('index') }}" title="">Skip</a></div>

      </div>

      </form>

    </div>

  </section>

@endsection