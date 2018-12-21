<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <title>{{ App\Setting::get_setting('site_title') }}</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-url" content="{{ url('/') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/tags/jquery.tag-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/timepicker/mmnt.css') }}">
    @if( file_exists(public_path('css/backend.min.css')) )
    <link rel="stylesheet" href="{{ asset('css/backend.min.css') }}?v={{ filemtime(public_path('css/backend.min.css')) }}">
    @else
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}?v={{ filemtime(public_path('css/backend.css')) }}">
    @endif

    @yield('style')

</head>
<body>

