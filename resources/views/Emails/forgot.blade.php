@component('mail::message')
# Hello {{$user->name}},

We received a request to reset your password. If you didn't make this request, you can safely ignore this email.

To reset your password, click the button below:

@component('mail::button', ['url' => url('reset/'.$user->remember_token)])
Reset Your Password
@endcomponent

If you're having trouble clicking the button, copy and paste the following URL into your web browser:

{{ url('reset/'.$user->remember_token) }}

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
