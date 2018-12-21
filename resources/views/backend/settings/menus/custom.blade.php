<li class="dd-item" 
    data-value="{{ Input::get('value') }}"  
    data-label="{{ Input::get('text') }}" 
    data-type="{{ Input::get('type') }}" 
    data-id="{{ $rand = rand(1111, 9999) }}">
    <div class="dd-handle">
        <span class="dd-label">{{ Input::get('text') }}</span>
        <small class="font-weight-normal float-right mr-3 text-muted">{{ Input::get('type') }}</small>
    </div>
    <a class="sort-icon" data-toggle="collapse" href="#c-{{ $rand }}">
    <i class="fa fa-caret-down"></i>
    </a>
    <div class="collapse border p-2" id="c-{{ $rand }}">
        <div class="form-group">
            <label>{{ trans('backend.navigation_label') }}</label>
            <input type="text" name="label" class="form-control" value="{{ Input::get('text') }}">
        </div>
        <div class="form-group">
            <label>URL</label>
            <input type="text" name="value" class="form-control" value="{{ Input::get('value') }}">
        </div>
        <a href="" class="text-danger remove-menu">{{ trans('backend.remove') }}</a> | <a href="#c-{{ $rand }}" data-toggle="collapse">{{ trans('backend.cancel') }}</a>     
    </div>
</li>