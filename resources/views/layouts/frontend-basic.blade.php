<!-- BEGIN HEAD -->
@include('frontend/partials/head')
<!-- END HEADER -->

@yield('header')

<!-- BEGIN CONTENT BODY -->
@yield('content')     
<!-- END CONTENT BODY -->

@include('frontend.partials.script')

@yield('script')
