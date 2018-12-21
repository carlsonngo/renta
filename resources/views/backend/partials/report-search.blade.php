


@if( Input::get('type') )
<input type="hidden" name="type" value="{{ Input::get('type') }}">
@endif

<div class="row my-3">
    <div class="col-auto mb-2 mb-md-0">
        <div class="input-group">
            <select class="form-control" name="action">
                <option value="">{{ trans('backend.bulk_actions') }}</option>
                @if( Input::get('type') == 'trash' )
                <option value="restore">{{ trans('backend.restore') }}</option>
                <option value="destroy">{{ trans('backend.delete_permanently') }}</option>
                @else                
                <option value="trash">{{ trans('backend.move_trash') }}</option>
                @endif
            </select>     
            <span class="input-group-append">
                <button type="submit" class="btn btn-apply-action" type="button" disabled>{{ trans('backend.apply') }}</button>
            </span>
        </div>
    </div>
    <div class="col-auto">
        {{ Form::select('post_type', ['' => 'All Bookings'] + event_type(), Input::get('post_type'), ['class' => 'form-control']) }}
    </div>
    <div class="col-auto">
        <div class="input-group">
            <input type="text" class="form-control" name="s" placeholder="{{ trans('backend.enter_search') }} ..." value="{{ Input::get('s') }}">  
            <span class="input-group-append">
                <button type="submit" class="btn btn-primary" type="button">{{ trans('backend.search') }}</button>
            </span>
        </div>
    </div>
</div>

@if( Input::get('lang') )
<input type="hidden" name="lang" value="{{ Input::get('lang') }}">
@endif