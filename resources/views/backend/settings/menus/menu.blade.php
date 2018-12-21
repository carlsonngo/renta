<li class="dd-item" 
data-value="{{ $menu->value }}" 
data-label="{{ $menu->label }}" 
data-type="{{ $menu->type }}" 
data-id="{{ $menu->id }}">
    <div class="dd-handle">
        <span class="dd-label">{{ $menu->label }}</span> 
        <small class="font-weight-normal float-right mr-3 text-muted">{{ $menu->type }}</small>
    </div>
    <a class="sort-icon" data-toggle="collapse" href="#c-{{ $menu->id }}">
    <i class="fa fa-caret-down"></i>
    </a>
    <div class="collapse border p-2" id="c-{{ $menu->id }}">
        <div class="form-group">
            <label>{{ trans('backend.navigation_label') }}</label>
            <input type="text" name="label" class="form-control" value="{{ $menu->label }}">
        </div>

        @if( $menu->type == 'custom-link' )
        <div class="form-group">
            <label>URL</label>
            <input type="text" name="value" class="form-control" value="{{ $menu->value }}">
        </div>
        @else
        <div class="form-group border rounded px-2 pt-2 pb-0 bg-light">
            <label>{{ trans('backend.original') }} : </label>
            <a href="{{ permalink($menu) }}" target="_blank">{{ $menu->value }}</a>
        </div>
        @endif

        <a href="" class="text-danger remove-menu">{{ trans('backend.remove') }}</a> | <a href="#c-{{ $menu->id }}" data-toggle="collapse">{{ trans('backend.cancel') }}</a>     
    </div>

    @if( @$menu->children )
        <ol class="dd-list">
        @each('backend.settings.menus.menu',  $menu->children, 'menu')
        </ol>
    @endif

</li>

