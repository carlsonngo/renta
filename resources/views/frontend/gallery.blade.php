@extends('layouts.frontend-with-sidebar')

@section('header')
<div class="bg-white">
    <div class="container">
        <div class="row py-4 align-items-center">
            <div class="col-sm-6 mb-2 mb-sm-0">
                <h1 class="font-weight-bold text-uppercase m-0">{{ $row->post_title }}</h1>
            </div>
            <div class="col-sm-6 text-sm-right">
                <a href="{{ route('frontend.galleries') }}" class="btn btn-sm btn-outline-primary"> {{ trans('backend.all_galleries') }}</a>
            </div>
        </div>    
    </div>
</div>     
@stop

@section('content')

<div class="bg-white rounded py-4 pb-2 px-4 mb-4">

    <div class="row pt-3">
        @if( $gallery = json_decode($row->post_content, true) )
        @foreach($gallery['slider'] as $gal_k => $gal_v)
        <div class="col-lg-4 col-md-6 text-center">
            <div class="shadow-3d">
                <div class="img-container rounded mb-2 box-shadow border">
                    <div class="hovereffect">
                        <img src="{{ has_image($gal_v) }}" class="img-fluid">
                        <div class="overlay">
                            @if( $name = $gallery['name'][$gal_k] )
                            <h2 class="text-white">{{ str_limit($name, 60, '...') }}</h2>
                            @endif
                            <a class="info text-white" href="#lightbox" data-toggle="modal" data-slide-to="<?php echo $gal_k; ?>">
                                {{ trans('backend.view') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>

<div class="modal fade carousel slide position-fixed" id="lightbox">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $row->post_title }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <!-- Indicators -->
                <ul class="carousel-indicators">
                    @if( $gallery )
                    @foreach($gallery['slider'] as $gal_k => $gal_v)
                    <li data-target="#lightbox" data-slide-to="<?php echo $gal_k; ?>" class="<?php echo $gal_k==0 ? 'active' : ''; ?>"></li>
                    @endforeach
                    @endif
                </ul>
                <!-- The slideshow -->
                <div class="carousel-inner text-center">
                    @if( $gallery )
                    @foreach($gallery['slider'] as $gal_k => $gal_v)
                    <div class="carousel-item <?php echo $gal_k==0 ? 'active' : ''; ?>">
                        <img src="{{ has_image($gal_v) }}" alt="Third slide" class="img-fluid w-100">
                          @if( $name = $gallery['name'][$gal_k] )
                            <div class="carousel-caption bg-trans-primary mb-5 p-2 rounded">
                            <h5 class="text-white m-0">{{ $name }}</h5>
                            </div>
                        @endif

                    </div>
                    @endforeach
                    @endif
                </div>
                <!-- Left and right controls -->
                <a class="carousel-control-prev" href="#lightbox" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#lightbox" data-slide="next">
                <span class="carousel-control-next-icon"></span>
                </a>
            </div>
        </div>
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