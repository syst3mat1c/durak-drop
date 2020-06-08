<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} &raquo; @yield('title', 'Администраторская')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('adminlte/dependencies/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dependencies/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dependencies/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/skins/skin-purple.min.css') }}">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    @stack('css')
</head>

<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{ route('admin.index') }}" class="logo">
            <span class="logo-mini">{!! config('admin.title_mini') !!}</span>
            <span class="logo-lg">{!! config('admin.title_full') !!}</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="{{ route('admin.index') }}" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            @widget('admin.widgets.header_widget')

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                @widget('admin.widgets.navigation_widget', [
                    'entry_point' => 'admin::widgets.navigation.navigation_entry_point',
                    'elements'      => config('admin_navigation')
                ])
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper">
        @hasSection('header')
            <section class="content-header">
                <h1>
                    @yield('header')
                </h1>
            </section>
        @endif

        <section class="content container-fluid">
            @yield('content')
        </section>
    </div>

    @include('admin::layouts.footer')
</div>
<!-- ./wrapper -->

@stack('modals')

@stack('before-js')
<script src="{{ asset('adminlte/dependencies/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/dependencies/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
<script src="{{ asset('adminlte/js/custom.js') }}"></script>
@stack('js')

</body>
</html>
