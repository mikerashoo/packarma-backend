<!DOCTYPE html>
<html class="loading" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="author" content="MYPCOTINFOTECH">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('backend/img/ico/favicon.png') }}">
    <title>@yield('title')</title>
    <link
        href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900%7CMontserrat:300,400,500,600,700,800,900"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/fonts/feather/style.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/fonts/simple-line-icons/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/fonts/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/vendors/css/perfect-scrollbar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/vendors/css/prism.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/pages/authentication.css')}}">
    <script src="{{ asset('backend/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{ asset('backend/vendors/js/core/bootstrap.min.js')}}"></script>
</head>

<body class="vertical-layout vertical-menu 1-column auth-page navbar-sticky blank-page" data-menu="vertical-menu"
    data-col="1-column">
    @yield('content')
</body>
<script src="{{ asset('backend/js/sidebar.js')}}"></script>
<script src="{{ asset('backend/vendors/js/switchery.min.js')}}"></script>
<script src="{{ asset('backend/js/notification-sidebar.js')}}"></script>
<script src="{{ asset('backend/js/customizer.js')}}"></script>
<script src="{{ asset('backend/js/scroll-top.js')}}"></script>
<script src="{{ asset('backend/js/scripts.js')}}"></script>

</html>
