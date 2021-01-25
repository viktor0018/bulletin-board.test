@component('mail::message')

Verify your email adress:

@component('mail::button', ['url' => $url])
Press to verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
