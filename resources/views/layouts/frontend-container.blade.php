<!-- BEGIN HEAD -->
@include('frontend/partials/head')
<!-- END HEADER -->

<!-- BEGIN HEAD -->
@include('frontend/partials/header')
<!-- END HEADER -->

@yield('header')

<div class="container">
	<!-- BEGIN CONTENT BODY -->
	@yield('content')     
	<!-- END CONTENT BODY -->	
</div>

<!-- BEGIN FOOTER -->
@include('frontend/partials/footer')
<!-- END FOOTER -->





