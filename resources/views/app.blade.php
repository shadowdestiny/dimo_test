<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8"> {{--
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">--}}
    <link rel='shortcut icon' href='favicon.png' type='image/x-icon' />

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/paginator.css') }}">
    <link rel="stylesheet" href="/css/all.css">
</head>

<body>
    <div id="app"></div>
    @include('scripts')
</body>

</html>
