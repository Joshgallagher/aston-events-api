@component('mail::message')
# Aston Events Email Confirmation

We need you to confirm your email before you can start organising events.

@component('mail::button', ['url' => '#'])
    Confirm Email Address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
