<header>
    <nav class="uppa navbar navbar-expand-lg navbar-light">
        <div class="container">

            @if($site_logo = App\Setting::get_setting('site_logo'))
            <a class="navbar-brand" href="{{ route('frontend.home') }}">
                <img src="{{ asset($site_logo) }}" class="img-fluid" width="150px" alt="">
            </a>
            @else
                <a href="{{ route('frontend.home') }}" class="navbar-brand">
                {{ App\Setting::get_setting('site_title') }}
                </a>
            @endif

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
                    <a class="nav-link" href="{{ route('shop.customer.index') }}">{{ Auth::User()->fullname }}</a>
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
        </div>
    </nav>
    <nav class="second-nav navbar navbar-expand-lg navbar-light" style="background-color: #fff">
        <div class="container">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">WOMENS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">MENS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">KIDS</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">FAVORITES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-search"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>

@include('shop.mobi-cart')               

