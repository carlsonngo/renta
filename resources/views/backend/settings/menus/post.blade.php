@foreach($rows as $row)
<?php $rand = $row->id.'-'.rand(1111, 9999); ?>
<li class="dd-item" 
    data-value="{{ $row->post_name }}"  
    data-label="{{ $row->post_title }}" 
    data-type="{{ $row->post_type }}" 
    data-id="{{ $rand }}">
    <div class="dd-handle">
        <span class="dd-label">{{ $row->post_title }}</span>
        <small class="font-weight-normal float-right mr-3 text-muted">{{ Input::get('type') }}</small>
    </div>
    <a class="sort-icon" data-toggle="collapse" href="#c-{{ $rand }}">
    <i class="fa fa-caret-down"></i>
    </a>
    <div class="collapse border p-2" id="c-{{ $rand }}">
        <div class="form-group">
            <label>{{ trans('backend.navigation_label') }}</label>
            <input type="text" name="label" class="form-control" value="{{ $row->post_title }}">
        </div>
        <div class="form-group border rounded px-2 pt-2 pb-0 bg-light">
            <label>{{ trans('backend.original') }} : </label>
            <a href="{{ asset($row->post_name) }}" target="_blank">{{ $row->post_title }}</a>
        </div>
        <a href="" class="text-danger remove-menu">{{ trans('backend.remove') }}</a> | <a href="#c-{{ $rand }}" data-toggle="collapse">{{ trans('backend.cancel') }}</a>     
    </div>
</li>
@endforeach
