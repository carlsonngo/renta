@extends('layouts.frontend-with-sidebar')

@section('header')
<div class="bg-white">
    <div class="container py-3">
        <h1 class="font-weight-bold text-uppercase m-0 h4">{{ trans('backend.galleries') }}</h1>
    </div>
</div>     
@stop

@section('content')

<div class="bg-white rounded py-4 pb-2 px-4 mb-4">
    <div class="row pt-4 px-4">

	@foreach($galleries as $gallery)
	<?php $gallerymeta = get_meta( $gallery->postMetas()->get() ); ?>
    <div class="col-lg-4 col-md-6 text-center">
        <div class="shadow-3d">
            <div class="img-container rounded mb-2 box-shadow">
	            <div class="hovereffect">
	                  <img src="{{ has_image($gallerymeta->image) }}" class="img-fluid">
	                <div class="overlay">
	                    <h2 class="text-white">{{ str_limit($gallery->post_title, 60, '...') }}</h2>
	                    <a class="info text-white" href="{{ route('frontend.gallery', $gallery->id) }}">{{ trans('backend.view') }}</a>
	                </div>
	            </div>
            </div>              
        </div>  
    </div>
	@endforeach
    </div>
	
</div>


@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/img-effects/effects-1.min.css') }}">
@stop

@section('plugin_script')
@stop

@section('script')
@stop
