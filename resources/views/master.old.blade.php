<html>
<head>
    <title> @yield('title') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Material Design fonts -->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap -->
    <link href="{!! asset('css/bootstrap.min.css') !!}" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    
    <!-- Material Design Bootstrap -->
    <link href="{!! asset('css/mdb.min.css') !!}" rel="stylesheet">
    <!-- <link href="{!! asset('css/material-dashboard.min.css') !!}" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://mdbootstrap.com/wp-content/themes/mdbootstrap4/css/compiled.min.css?ver=4.5.0"> -->
    <!-- Your custom styles (optional) -->
    <link href="{!! asset('css/style.min.css') !!}" rel="stylesheet">
    <style>
    .navbar {
        background-color:#4facfe !important;
    }
    .page-footer {
        background-color: #4facfe;
    }
    .footer-copyright {
        background-color: #00f2fe;
    }
    </style>
    @stack('styles')
</head>
<body>
<div id='app'>
@include('shared.navbar')
<!--Main layout-->
<main>
@yield('content')
</main>
<!--Main layout-->
@include('shared.footer')
</div>

<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> -->
<script type="text/javascript" src="{!! asset('js/jquery-3.2.1.min.js') !!}"></script>
<!-- <script type="text/javascript" src="{!! asset('js/jquery.js') !!}"></script> -->
<script src="/js/app.js"></script>

<!-- Bootstrap tooltips -->
<script type="text/javascript" src="{!! asset('js/popper.min.js') !!}"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="{!! asset('js/bootstrap.min.js') !!}"></script>
<!-- MDB core JavaScript -->
<!-- <script type="text/javascript" src="{!! asset('js/mdb.min.js') !!}"></script> -->
  <!-- Initializations -->
<script type="text/javascript">
// Animations initialization
// new WOW().init();   
</script>
@stack('scripts')
</body>
</html>