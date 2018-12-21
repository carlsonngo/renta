@if( has_access($module, ['trash_restore']) )
    @if(Input::get('type') == 'trash')
    <a href="{{ URL::route($view.'.index', query_vars('type=0&s=0&action=0&page=0')) }}" class="text-dark">{{ trans('backend.all') }} ({{ number_format($all) }})</a> | 
    <a href="{{ URL::route($view.'.index', query_vars('type=trash&s=0&action=0&page=0')) }}" class="text-primary">{{ trans('backend.trashed') }} ({{ number_format($trashed) }})</a>
    @else
    <a href="{{ URL::route($view.'.index', query_vars('type=0&s=0&action=0&page=0')) }}" class="text-primary">{{ trans('backend.all') }} ({{ number_format($all) }})</a> | 
    <a href="{{ URL::route($view.'.index', query_vars('type=trash&s=0&action=0&page=0')) }}" class="text-dark">{{ trans('backend.trashed') }} ({{ number_format($trashed) }})</a>
    @endif
@endif

@if( Input::get('post_type') )
<input type="hidden" name="post_type" value="{{ Input::get('post_type') }}">    
@endif

@if( Input::get('type') )
<input type="hidden" name="type" value="{{ Input::get('type') }}">
@endif

<input type="submit" class="d-none">

<div class="row my-3 align-items-center">
    <div class="col-auto mb-2 mb-lg-0">
        <div class="input-group">
            <select class="form-control" name="action">
                <option value="">{{ trans('backend.bulk_actions') }}</option>
                @if( Input::get('type') == 'trash' )
                    <option value="restore">{{ trans('backend.restore') }}</option>
                    <option value="destroy">{{ trans('backend.delete_permanently') }}</option>
                @else                
                    @if( has_access($module, ['trash_restore']) )
                    <option value="trash">{{ trans('backend.move_trash') }}</option>
                    @endif
                    <option value="actived">{{ trans('backend.mark_as_actived') }}</option>
                    <option value="inactived">{{ trans('backend.mark_as_inactived') }}</option>
                @endif            
            </select>     
            <span class="input-group-append">
                <button type="submit" class="btn btn-apply-action" type="button" disabled>{{ trans('backend.apply') }}</button>
            </span>
        </div>
    </div>
    <div class="col-auto mb-2 mb-lg-0">
        <div class="input-group">
            <input type="text" class="form-control" name="s" placeholder="{{ trans('backend.enter_search') }} ..." value="{{ Input::get('s') }}">  
            <span class="input-group-append">
                <button type="submit" class="btn btn-primary" type="button">{{ trans('backend.search') }}</button>
            </span>
        </div>
    </div>
    <div class="col-auto mb-2 mb-lg-0">
        <a href="{{ route($view.'.export', query_vars()) }}">Export Product</a>
    </div>
    <div class="col-auto">
        <a href="#" data-href="{{ route($view.'.import') }}" data-toggle="modal" data-target="#import-data">Import Product</a>
    </div>
</div>


@if( Input::get('lang') )
<input type="hidden" name="lang" value="{{ Input::get('lang') }}">
@endif