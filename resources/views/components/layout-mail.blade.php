<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <p>{{ __('app.mail_greeting') }}</p>
{{ $slot }}
<p>{{ __('app.mail_ending') }}</p>
<p>{{ config('app.name') }}</p>
</body>
</html>