<!-- BEGIN HEAD -->
@include('frontend/partials/head')
<!-- END HEADER -->

<!-- BEGIN HEAD -->
@include('frontend/partials/header')
<!-- END HEADER -->

@yield('header')

<div class="container mt-4">

    <div class="row">
        <div class="col-lg-9">

        <!-- BEGIN CONTENT BODY -->
        @yield('content')     
        <!-- END CONTENT BODY -->

        </div>
        <div class="col-lg-3">

        <!-- BEGIN SIDEBAR -->
           @include('frontend/partials/sidebar')   
        <!-- END SIDEBAR -->

        </div>
    </div>

</div>

<!-- BEGIN FOOTER -->
@include('frontend/partials/footer')
<!-- END FOOTER -->





