<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" lang="{{ str_replace('_', '-', app()->getLocale()) }}" itemscope itemtype="http://schema.org/WebPage">
<head>
    <?php
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $partial_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    ?>
    <?php
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- color en moviles -->
    <meta name="theme-color" content="#004FB6">
    <meta name="apple-mobile-web-app-status-bar-style" content="#004FB6">
    <meta name="msaplication-navbutton-color" content="#004FB6">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slots4funtx</title>

    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="">
    <meta name="twitter:site" content="">
    <meta name="twitter:title" content="">
    <meta name="twitter:description" content="">
    <meta name="twitter:creator" content="">
    <meta name="twitter:url" content="">
    <meta name="twitter:image" content="">

    <!-- Open Graph data -->
    <link rel="canonical" href="{{$actual_link}}"/>
    <meta property="og:image" content="">
    <meta property="og:image:url" content="">
    <meta property="og:image:width" content="" />
    <meta property="og:image:height" content="" />
    <meta property="og:site_name" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:title" content=""/>
    <meta property="og:type" content="website"/>
    <meta property="og:image:alt" content=""/>
    <meta property="og:description" content=""/>
    <meta property="og:locale" content="es_LA">
    <meta property="fb:admins" content=""/>
    <meta property="fb:pages" content=""/>
    <meta property="fb:app_id" content=""/>

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }} ">
<!--===============================================================================================-->
    <!--link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}"-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fontawesome5.3.1.css') }} ">

<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/hamburgers.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.css') }} ">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">

</head>
<body>

        @yield('content')

    <!--===============================================================================================-->
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/jquery3.3.1.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>

    <!--===============================================================================================-->
    <script src="{{ asset('js/select2.js') }}"></script>
    <!--===============================================================================================-->
    <!--script src="{{ asset('js/main.js') }}"></script-->
    <script>
      @if(Session::has('message'))

        var type = "{{ Session::get('alert-type', 'info') }}";
        switch(type){
          case 'info':
            toastr.info("{{ Session::get('message') }}","{{ Session::get('title') }}");
          break;
          case 'warning':
            toastr.warning("{{ Session::get('message') }}","{{ Session::get('title') }}");
          break;

          case 'success':
            toastr.success("{{ Session::get('message') }}","{{ Session::get('title') }}");
          break;

          case 'error':
            toastr.error("{{ Session::get('message') }}","{{ Session::get('title') }}");
          break;
        }

      @endif
    </script>


</body>
</html>
