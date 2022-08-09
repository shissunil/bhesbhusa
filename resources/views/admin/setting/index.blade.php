@extends('layouts.admin')

@section('title')
Setting Master
@endsection

@section('head')
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/admin/app-assets/vendors/css/tables/datatable/datatables.min.css')  }}">
@endsection

@section('content')

<div class="content-header row">

    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Setting Master</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Setting Master
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Setting Master</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.setting.update') }}" id="profile_form">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Return Day <span class="text-danger h6">*</span></label>
                                            <input type="text" name="return_day" min="1" 
                                                class="form-control @error('return_day') is-invalid @enderror" id="return_day"
                                                placeholder="Return day" value="{{ isset( $settingMaster->return_day ) ? $settingMaster->return_day : '' }}" onkeypress="validate()" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="email" class="mb-1">Admin Support Number <span class="text-danger h6">*</span></label>
                                            <input type="tel" name="support_number"
                                                class="form-control @error('support_number') is-invalid @enderror" id="support_number"
                                                placeholder="Support Number" pattern="[1-9]{1}[0-9]{9}" value="{{ old('support_number') ? old('support_number') : $settingMaster->support_number }}" required>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Payment Gateway Credentials</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">KHALTI SECRET KEY <span class="text-danger h6">*</span></label>
                                            <input type="text" name="khalti_secret_key"  class="form-control @error('khalti_secret_key') is-invalid @enderror" id="khalti_secret_key" placeholder="KHALTI SECRET KEY" value="{{ old('khalti_secret_key') ? old('khalti_secret_key') : $settingMaster->khalti_secret_key }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">KHALTI PUBLIC KEY <span class="text-danger h6">*</span></label>
                                            <input type="text" name="khalti_public_key"  class="form-control @error('khalti_public_key') is-invalid @enderror" id="khalti_public_key" placeholder="KHALTI PUBLIC KEY" value="{{ old('khalti_public_key') ? old('khalti_public_key') : $settingMaster->khalti_public_key }}" required>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>SMS Gateway Credentials</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">SMS Token <span class="text-danger h6">*</span></label>
                                            <input type="text" name="sms_token"  class="form-control @error('sms_token') is-invalid @enderror" id="sms_token" placeholder="KHALTI SECRET KEY" value="{{ old('sms_token') ? old('sms_token') : $settingMaster->sms_token }}" required>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Email Gateway Credentials</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Mail Mailer <span class="text-danger h6">*</span></label>
                                            <input type="text" name="mail_mailer"  class="form-control @error('mail_mailer') is-invalid @enderror" id="mail_mailer" placeholder="KHALTI SECRET KEY" value="{{ old('mail_mailer') ? old('mail_mailer') : $settingMaster->mail_mailer }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Mail Host <span class="text-danger h6">*</span></label>
                                            <input type="text" name="mail_host"  class="form-control @error('mail_host') is-invalid @enderror" id="mail_host" placeholder="KHALTI SECRET KEY" value="{{ old('mail_host') ? old('mail_host') : $settingMaster->mail_host }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Mail Port <span class="text-danger h6">*</span></label>
                                            <input type="text" name="mail_port"  class="form-control @error('mail_port') is-invalid @enderror" id="mail_port" placeholder="KHALTI SECRET KEY" value="{{ old('mail_port') ? old('mail_port') : $settingMaster->mail_port }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Mail UserName <span class="text-danger h6">*</span></label>
                                            <input type="text" name="mail_username"  class="form-control @error('mail_username') is-invalid @enderror" id="mail_username" placeholder="KHALTI SECRET KEY" value="{{ old('mail_username') ? old('mail_username') : $settingMaster->mail_username }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Mail Password <span class="text-danger h6">*</span></label>
                                            <input type="text" name="mail_password"  class="form-control @error('mail_password') is-invalid @enderror" id="mail_password" placeholder="KHALTI SECRET KEY" value="{{ old('mail_password') ? old('mail_password') : $settingMaster->mail_password }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Mail Encryption <span class="text-danger h6">*</span></label>
                                            <input type="text" name="mail_encryption"  class="form-control @error('mail_encryption') is-invalid @enderror" id="mail_encryption" placeholder="KHALTI SECRET KEY" value="{{ old('mail_encryption') ? old('mail_encryption') : $settingMaster->mail_encryption }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Mail From Address <span class="text-danger h6">*</span></label>
                                            <input type="text" name="mail_from_address"  class="form-control @error('mail_from_address') is-invalid @enderror" id="mail_from_address" placeholder="KHALTI SECRET KEY" value="{{ old('mail_from_address') ? old('mail_from_address') : $settingMaster->mail_from_address }}" required>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <h4>Push Notification Credentials</h4>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">User Notification Sender Id <span class="text-danger h6">*</span></label>
                                            <input type="text" name="user_notification_sender_id"  class="form-control @error('user_notification_sender_id') is-invalid @enderror" id="user_notification_sender_id" placeholder="KHALTI SECRET KEY" value="{{ old('user_notification_sender_id') ? old('user_notification_sender_id') : $settingMaster->user_notification_sender_id }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">User Notification Key <span class="text-danger h6">*</span></label>
                                            <input type="text" name="user_notification_key"  class="form-control @error('user_notification_key') is-invalid @enderror" id="user_notification_key" placeholder="KHALTI SECRET KEY" value="{{ old('user_notification_key') ? old('user_notification_key') : $settingMaster->user_notification_key }}" required>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Driver Notification Sender Id <span class="text-danger h6">*</span></label>
                                            <input type="text" name="driver_notification_sender_id"  class="form-control @error('driver_notification_sender_id') is-invalid @enderror" id="user_notification_sender_id" placeholder="KHALTI SECRET KEY" value="{{ old('driver_notification_sender_id') ? old('driver_notification_sender_id') : $settingMaster->driver_notification_sender_id }}" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <fieldset class="form-group">
                                            <label for="name" class="mb-1">Driver Notification Key <span class="text-danger h6">*</span></label>
                                            <input type="text" name="driver_notification_key"  class="form-control @error('driver_notification_key') is-invalid @enderror" id="driver_notification_key" placeholder="KHALTI SECRET KEY" value="{{ old('driver_notification_key') ? old('driver_notification_key') : $settingMaster->driver_notification_key }}" required>
                                        </fieldset>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">
                                    Submit
                                </button>
                            <form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('footer')
<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/admin/app-assets/js/scripts/datatables/datatable.js') }}"></script>
@endsection