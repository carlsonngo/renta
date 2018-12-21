    

    @if( App\Setting::get_setting('google_translate') )
    <div class="container my-3">
        <div id="google_translate_element"></div>
    </div>
    @else 
        @if( App\Setting::get_setting('localization') )
	    <div class="container my-3">
	        <?php $lang = Input::get('lang', App\Setting::get_setting('site_language')); ?>
	        <div class="form-row align-items-center">
	            <div class="col-auto">
	                <label class="small text-uppercase m-0">{{ trans('backend.select_language') }}</label>
	            </div>
	            <div class="col-auto">
	                {{ Form::select('lang', languages(), $lang, ['class' => 'form-control form-control-sm switch-lang']) }}                
	            </div>
	            <div class="col-auto">
	                <img src="{{ asset('assets/img/flags/'.$lang.'.png') }}" width="25">                 
	            </div>
	        </div>
	    </div>
        @endif
    @endif

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                <a href="{{ route('frontend.home') }}">
                        @if($footer_logo = App\Setting::get_setting('footer_logo'))
                            <img src="{{ asset($footer_logo) }}" width="200">
                        @else
                            {{ App\Setting::get_setting('footer_title') }}
                        @endif
                    </a>
                </div>
                <div class="col-sm">
                    <h6>HELP</h6>
                    <ul>
                    <?php $menu = App\Post::where('post_type', 'menu')->where('post_name', 'footer_1')->site()->first(); ?>
                    @if( $menu ) 
                        @foreach(json_decode($menu->post_content) as $menu)
                        <li><a href="{{ $menu->value }}">{{ $menu->label }}</a></li>
                        @endforeach
                    @endif
                    </ul>
                </div>
                <div class="col-sm">
                    <h6>COMPANY</h6>
                    <ul>
                    <?php $menu = App\Post::where('post_type', 'menu')->where('post_name', 'footer_2')->site()->first(); ?>
                    @if( $menu ) 
                        @foreach(json_decode($menu->post_content) as $menu)
                        <li><a href="{{ $menu->value }}">{{ $menu->label }}</a></li>
                        @endforeach
                    @endif
                    </ul>
                </div>
                <div class="col-sm">
                    <h6>SOCIALIZE</h6>
                    <ul class="social-links">
                        <a href="#"><li><i class="fab fa-instagram"></i></li></a>
                        <a href="#"><li><i class="fab fa-twitter"></i></li></a>
                        <a href="#"><li><i class="fab fa-facebook-f"></i></li></a>
                    </ul>
                </div>
            </div>
        </div>
    </footer>


    <div class="o-loader animated">
        <div class="row align-items-center h-100">
            <div class="col-12 m-auto w-25 text-center animated bounceIn">
                <img src="{{ asset('assets/img/loaders/1.gif' ) }}?<?php date('ymdhis'); ?>" class="img-thumbnail rounded-circle animated bounce delay-1s" width="90">          
            </div>
        </div>
    </div>

    <a href="javascript:" id="return-to-top"><i class="fas fa-chevron-up"></i></a>

    @include('frontend.partials.script')

  </body>
</html>