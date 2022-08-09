@extends('front.layout.main')

@section('section')

 <section class="inner-page-banner">

        <div class="container-fluid"> 

          <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active">Profile</li>

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

                <div class="dashboard-title"> <h3>Profile</h3> </div>                

                <div class="white-bg profile-detail">

                  <form method="post" action="{{ route('profile.update') }}">

                    @csrf

                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">

                            <div class="form-group">

                              <label>Mobile Number</label>

                              <input class="form-control" type="text" name="mobile_number" placeholder="Mobile Number" 
                              value="{{ old('mobile_number') ?? auth()->user()->mobile_number }}"/>

                            </div>

                        </div>   
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">

                          <div class="form-group">

                            <label>Email</label>

                            <input class="form-control" type="email" name="email" placeholder="johndoe28@gmail.com" 
                            value="{{ old('email') ?? auth()->user()->email }}" />

                          </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">

                            <div class="form-group">

                              <label>First Name</label>

                              <input class="form-control" type="text" name="first_name" placeholder="First Name" 
                              value="{{ old('first_name') ?? auth()->user()->first_name }}" />

                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">

                          <div class="form-group">

                            <label>Last Name</label>

                            <input class="form-control" type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') ?? auth()->user()->last_name }}" />

                          </div>

                      </div>
                        

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">                          

                            <div class="form-group">

                              <label>Gender</label>

                              <div class="gender">                              

                                <label class="radio-box">Female<input type="radio" name="gender" value="female" {{ (old('gender')=='female' || auth()->user()->gender=='female') ? 'checked' : '' }} ><span class="checkmark"></span></label>

                                <label class="radio-box">Male<input type="radio" name="gender" value="male" {{ (old('gender')=='male' || auth()->user()->gender=='male') ? 'checked' : '' }} ><span class="checkmark"></span></label>

                              </div>

                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">

                            <div class="form-group">

                              <label>Date Of Birth</label>

                              <input class="form-control" type="date" name="date_of_birth" placeholder="Date of Birth" value="{{ old('date_of_birth') ?? auth()->user()->date_of_birth }}" />

                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">

                            <div class="form-group">

                              <label>Location</label>

                              <input class="form-control" type="text" name="location" placeholder="Location" value="{{ old('location') ?? auth()->user()->location }}" />

                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                          <input type="file" name="profile_pic" class="d-none">
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">

                          <button class="btn theme-btn" type="submit">Save Details</button>

                        </div>

                    </div>

                  </form>

                </div>

            </div>

          </div>

        </div>

    </section>
    

@endsection