<!doctype html>
<html lang="ru">
<head>
    <title>{{ app(\App\Services\UI\HeaderService::class)->getTitle() }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="sockets-server" content="{{ config('services.sockets.server') }}">
    <meta name="is-authorized" content="{{ (int) Auth::check() }}">
    <meta name="keywords" content="Дурак дроп, Durak drop, Кейсы дурак, кейсы дурак онлайн, дурак онлайн, дурак" />
    <meta name="description" content="Открывай кейсы Durak Online с пинкодами и выигрывай игровую валюту, только на Durak-drop">
    <link rel="stylesheet" type="text/css" href="{{ asset('default/css/style1.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('default/css/media1.css') }}" />
    <link rel="shortcut icon" href="{{ asset('default/images/favicon.ico') }}" />
    @stack('css')
</head>
<body>
@include('front.layouts.modals')
<div class="wrapper">
    @widget('users.widgets.live_widget')
    @widget('users.widgets.header_widget')

    @yield('content')

    @include('front.layouts.footer')

    @stack('js')
    <script type="text/javascript" src="{{ mix('js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/vendor.min.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/app.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('default/js/jquery.arcticmodal-0.3.min.js') }}"></script>
</div>
</body>
</html>
