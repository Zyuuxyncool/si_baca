<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{ url('/') }}" />
    <title>{{ $file }}</title>
    <meta charset="utf-8" />
    <meta name="token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}" />
</head>
<body style="padding: 0;">
<iframe src="{{ asset('storage/' . $file) }}" frameborder="0" style="height: 100vh;width: 100%;"></iframe>
</body>
</html>
