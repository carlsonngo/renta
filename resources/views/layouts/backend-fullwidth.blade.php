<!-- BEGIN HEAD -->
@include('backend/partials/head')
<!-- END HEADER -->

<main>
    <div class="container-fluid mt-3">

        <!-- BEGIN CONTENT BODY -->
        @yield('title')     
        <!-- END CONTENT BODY -->

        <!-- BEGIN NOTIF -->
        @include('notification')
        <!-- END NOTIF -->

        <!-- BEGIN CONTENT BODY -->
        @yield('content')     
        <!-- END CONTENT BODY -->

    </div>  
</main>


<!-- BEGIN FOOTER -->
@include('backend/partials/footer')
<!-- END FOOTER -->


