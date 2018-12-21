@extends('layouts.frontend-home')

@section('header')
<header>
    <div class="container">
        <nav class="home navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand d-lg-none" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <?php $cart = session('cart'); ?>
            <div class="mobi-cart d-block d-md-none">
                <a href="#" class="mr-2" data-toggle="modal" data-target="#cart-modal">
                <i class="fa fa-shopping-cart text-white"></i>
                <span>{{ $cart['quantity'] ?? 0 }}</span>
                </a>            
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto text-uppercase">
                    <?php $menu = @App\Post::where('post_type', 'menu')->where('post_name', 'header')->site()->first(); ?>
                    @if( $menu ) 
                    <?php $menus = @$menu->post_content ? json_decode(@$menu->post_content) : []; ?>
                    @each('frontend.partials.menu', $menus, 'menu')            
                    @endif
                    @if( Auth::check() )
                    <li class="nav-item">
                        @if( Auth::User()->group == 'customer' )
                        <a class="nav-link" href="{{ route('shop.customer.index') }}">{{ trans('backend.my_account') }}</a>
                        @else
                        <a class="nav-link" href="{{ route('login') }}">{{ trans('backend.dashboard') }}</a>
                        @endif
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ trans('backend.log_in') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.register') }}">{{ trans('backend.register') }}</a>
                    </li>
                    @endif
                    <div class="mini-cart d-none d-md-block">
                        @include('shop.mini-cart')   
                    </div>
                </ul>
            </div>
        </nav>
    </div>
</header>
@stop

@section('content')
<div class="banner text-center" style="height:100vh;background: url({{ asset('assets/img/banner.jpg') }});">
    <div class="container">
        <div class="banner-content" style="padding-top: 30vh;">
            @if($site_logo = App\Setting::get_setting('site_logo'))
            <a href="{{ route('frontend.home') }}">
            <img src="{{ asset($site_logo) }}" width="350" class="pb-5">
            </a>
            @else
            <a href="{{ route('frontend.home') }}" class="navbar-brand">
            {{ App\Setting::get_setting('site_title') }}
            </a>
            @endif
            <h2>Borrowing is best</h2>
            <h2>when borrowing from the best.</h2>
            <a class="home-btn py-3" href="{{ route('auth.register') }}">REGISTER</a>
        </div>
    </div>
</div>

<?php 
    $products = App\Post::site()
                        ->where('post_type', 'product-category')
                        ->where('post_status', 'actived')
                        ->paginate(9);
?>

@if( @$products )
<div class="categories">
    <div class="container">
        <h2 class="text-center">Our Closet</h2>
        <div class="row text-center">

        @foreach($products as $product)
        <?php $productmeta = get_meta( $product->postMetas()->get() ); ?>
        <div class="col-lg-3 col-md-4 col-sm-6 product-image">
            <a href="{{ route('shop.index', ['category' => $product->id])}}">
                <img src="{{ has_image( str_replace('-large', '-medium', $productmeta->image) ) }}" alt="" class="img-fluid">  
                <?php $title = trans_post($product, 'post_title', '_title'); ?>
                <h5>{!! str_limit(strip_tags($title), 50, '...') !!}</h5>
            </a>
        </div>
        @endforeach

        </div>
        <div class="home-cta text-right">
            <a href="{{ route('shop.index') }}">BROWSE FOR MORE</a>
        </div>
    </div>
</div>
@endif

<div class="how-to-uppa">
    <div class="container">
        <h2 class="text-center">How to Uppa</h2>
        <div class="row text-center">
            <div class="col-sm">
                <img src="assets/img/circle.png" alt="" class="img-fluid pb-5" width="250">
                <h3>Step 1 <span>REGISTER</span></h3>
                <p>Browse from our personal <br> closet to find that dress you have <br> been eyeing for.</p>
            </div>
            <div class="col-sm">
                <img src="assets/img/circle.png" alt="" class="img-fluid pb-5" width="250">
                <h3>Step 2 <span>RESERVE</span></h3>
                <p>Pick your item and choose <br> your rental date to have <br> it delivered to you.</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-sm">
                <img src="assets/img/circle.png" alt="" class="img-fluid pb-5" width="250">
                <h3>Step 3 <span>RECEIVE</span></h3>
                <p>Receive your item and enjoy <br> wearing it all you want during <br> the rental period.</p>
            </div>
            <div class="col-sm">
                <img src="assets/img/circle.png" alt="" class="img-fluid pb-5" width="250">
                <h3>Step 4 <span>RETURN</span></h3>
                <p>Schedule your item for <br> return from our free <br> pick up service.</p>
            </div>
        </div>
        <div class="home-cta text-right">
            <a href="#">LET'S GET STARTED</a>
        </div>
    </div>
</div>
<div class="blog text-center">
    <h2>Passion for Fashion</h2>
    <div class="blog-article" style="background: url({{ asset('assets/img/slider1.jpg') }});">
    </div>
</div>
@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop