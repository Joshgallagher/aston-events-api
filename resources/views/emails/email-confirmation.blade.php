@component('mail::message')
# Aston Events Email Confirmation

We need you to confirm your email before you can start organising events.

@component('mail::button', ['url' => url('api/v1/register/confirm?token='.$user->confirmation_token)])
    Confirm Email Address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
