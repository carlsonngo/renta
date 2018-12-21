@foreach(backend_menus() as $menu_module => $menu)
    @if( $menu_module == 'dashboard' || has_access($menu_module) || ($menu_module == 'shop' && @$menu['child']) )
    <li class="nav-item menu-{{ text_to_slug(@$menu['name']) }}">
        <?php 
            $menu_attributes = $menu['child'] ? 'data-toggle=collapse aria-expanded=false data-target=#m-'.text_to_slug($menu['name']) : '';
        ?>
        <a class="nav-link collapsed" href="{{ $menu['url'] }}" {{ $menu_attributes }}>
            <i class="{{ $menu['icon'] }} mr-1"></i>  {{ $menu['name'] }}
            @if(@$menu['count'])
                <span class="badge badge-warning float-right px-2 py-1">{{ $menu['count'] }}</span>
            @endif
        </a>
        @if( $menu['child'] )
        <div id="m-{{ text_to_slug(@$menu['name']) }}" class="collapse">
            <ul class="nav flex-column">
                @foreach($menu['child'] as $child)
                    <?php $menu_module = @$child['module'] ? @$child['module'] : $menu_module; ?>
                    @if( has_access($menu_module, @$child['role']) )
                    <li class="nav-item menu-child-{{ text_to_slug($child['name']) }}">
                        <a class="nav-link" href="{{ $child['url'] }}">
                            {{ $child['name'] }}
                            @if(@$child['count'])
                                <span class="badge badge-warning float-right px-2 py-1">{{ $child['count'] }}</span>
                            @endif
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif
    </li>
    @endif
@endforeach

<li class="text-white site-lang" style="{{ App\Setting::get_setting('localization') ? '' : 'display:none' }}">
    <div class="dropdown-divider my-3"></div>
    <div class="px-3">
        <form method="GET" action="">
            <?php parse_str($_SERVER['QUERY_STRING'], $queries); unset($queries['lang']); ?>
            @foreach($queries as $qs_k => $qs_v)
            <input type="hidden" name="{{ $qs_k }}" value="{{ $qs_v }}">
            @endforeach

            <?php $lang = Input::get('lang', App\Setting::get_setting('site_language')); ?>
            <label class="small text-uppercase">{{ trans('backend.select_language') }}</label>
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <img src="{{ has_image('assets/img/flags/'.$lang.'.png') }}" class="rounded-circle" width="25">                 
                </div>
                <div class="col">
                    {{ Form::select('lang', site_languages(), $lang, ['class' => 'form-control form-control-sm switch-lang']) }}                
                </div>
            </div>   
        </form>             
    </div>
</li>
