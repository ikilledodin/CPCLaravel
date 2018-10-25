<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title> @yield('title') </title>
    <!-- Favicon-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{!! asset('favicon.ico') !!}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{!! asset('adminbsb/plugins/bootstrap/css/bootstrap.css') !!}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{!! asset('adminbsb/plugins/node-waves/waves.css') !!}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{!! asset('adminbsb/plugins/animate-css/animate.css') !!}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{!! asset('adminbsb/css/style.css') !!}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{!! asset('adminbsb/css/themes/all-themes.css') !!}" rel="stylesheet" />
    @stack('styles')
</head>

<body class="@stack('body-class')">
    <div id="app">
    @if (Auth::check())
        @include('shared.loader')
        @include('shared.navbar')
        
    @endif
    @yield('content')
    </div>

   

    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- Jquery Core Js -->
    <script src="{!! asset('adminbsb/plugins/jquery/jquery.min.js') !!}"></script>
    <!-- <script type="text/javascript" src="{!! asset('js/jquery-3.2.1.min.js') !!}"></script> -->
    <script src="/js/app.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="{!! asset('adminbsb/plugins/bootstrap/js/bootstrap.js') !!}"></script>
    <!-- <script type="text/javascript" src="{!! asset('js/bootstrap.min.js') !!}"></script> -->

    <!-- Slimscroll Plugin Js -->
    <script src="{!! asset('adminbsb/plugins/jquery-slimscroll/jquery.slimscroll.js') !!}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{!! asset('adminbsb/plugins/node-waves/waves.js') !!}"></script>

    <!-- Custom Js -->
    <script src="{!! asset('adminbsb/js/admin.js') !!}"></script>

    <!-- Demo Js -->
    <script src="{!! asset('adminbsb/js/demo.js') !!}"></script>
    @stack('scripts')
</body>

</html>