@component('mail::message')
    @component('mail::button', ['url' => route('admin.reset-password', $token), 'color' => 'primary'])
        Reset Password
    @endcomponent
@endcomponent
