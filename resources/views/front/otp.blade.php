@extends('front.layout.main')

@section('section')

<section class="login-shop">

    <div class="login-inner">

      <form method="post" action="{{ route('otp.verify') }} " id="otp_form">

      @csrf

      @php
        $mobile_number = Session::get('mobile_number');
        $social_media = Session::get('social_media');
      @endphp

      <div class="otp-view">

        <div class="logo"><img src="{{ asset('assets/front/images/black-bb.svg') }}" alt=""></div>

        <h2>Enter OTP</h2>

        <p>Please enter an One Time Password we've just sent you on your mobile number</p>

        <div class="form-group otp-input">

          <input 
          type="text" name="otp" 
          class="form-control @error('otp') is-invalid @enderror" 
          value="{{ old('otp') ? old('otp') : '' }}" 
          placeholder="OTP" 
          onkeypress="validate()" 
          required="" 
           />

        </div>

        <!-- <div class="second">Expires in 38s</div> -->

        <input type="hidden" name="mobile_number" value="{{ old('mobile_number') ? old('mobile_number') : $mobile_number ?? '' }}" />     
        <input type="hidden" name="social_media" value="{{ old('social_media') ? old('social_media') : $social_media ?? 0 }}" />     

        <div class="verify"><button class="btn theme-btn" type="submit">Verify</button></div>

        

        <div class="term-condition">

          <p>By clicking on Sign up you agree to</p>

          <a href="{{ route('terms_and_conditions') }}">Terms & Conditions</a>

        </div>

      </div>

    </form>

    </div>

</section>

@endsection
@section('footer')
    <script type="text/javascript">
        $("#otp_form").validate({
            rules: {
                otp: {
                    required: true,
                    digits:true,
                    minlength:4,
                    maxlength:4,
                },
            },
            messages: {
                otp: {
                    required: "OTP Required",
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    </script>
@endsection