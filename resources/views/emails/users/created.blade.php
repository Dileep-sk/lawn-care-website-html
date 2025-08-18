@component('mail::message')
# Welcome, {{ $user->name }} 🎉

Your account has been created successfully.

Here are your login details:

@component('mail::panel')
**Email:** {{ $user->email }}
**Password:** {{ $password }}
@endcomponent

@component('mail::button', ['url' => config('app.url') . '/login'])
Login Now
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent