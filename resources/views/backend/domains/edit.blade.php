@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span> 
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.edit') }}</span>

<div class="float-right">
    <a href="{{ route($view.'.add') }}" class="btn btn-sm btn-dark"> {{ trans('backend.add_new') }}</a>   
    <a href="{{ URL::route($view.'.index') }}" class="btn btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_domains') }}</a>
    
</div>

<hr>
@stop

@section('content')
<form method="POST">
    {{ csrf_field() }}
    @include('backend.domains.views.edit')
    @include('backend.settings.inc.general')
   
    <div class="form-actions">
        <button type="submit" class="btn btn-primary mr-3">{{ trans('backend.save_changes') }}</button>     

        @if( $info->post_status == 'draft' )
        <a href="#" 
        class="text-danger" 
        data-url="{{ URL::route($view.'.delete', [$info->id, query_vars()]) }}"
        data-toggle="confirm-modal" 
        data-target=".confirm-modal" 
        data-title="{{ trans('backend.confirm_move_trash') }}" 
        data-body="{{ trans('messages.confirm_move_trash') }} ID: <b>#{{ $info->id }}</b>?"> {{ trans('backend.move_trash') }}</a>     
        @endif

    </div>
</form>

<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
<script type="text/javascript">
$(document).on('click', '[name="localization"]', function(){
    var uri = $(this).is(':checked') ? '?lang='+$('[name="site_language"]').val() : '',
        url = $(this).closest('form').data('url')+uri;

    history.pushState({}, null, url);
});  
$(document).on('click', '.enabled-modules', function(){
    var target = $(this).attr('data-target'); 
    $('.'+target).toggle();
    $('.menu-'+target).toggle();
});  

$(document).on('click', '.btn-add-ba', function(e) {
    e.preventDefault();
    var target = $(this).data('target'),
        form = $(target+'-copy'+' '+target+'-body').html();
    $(target+' '+target+'-body').append( form );

    var index = $(target+' '+target+'-row').length;
    $(target+' '+target+'-row:last-child .input-f').each(function() {
        name = $(this).attr('name');
        name = name.replace(/\[[0-9]+\]/g, '['+index+']');
        $(this).attr('name',name);
    });
         
});

$(document).on('click', '.btn-remove-ba', function(e) {
    e.preventDefault();
    if( confirm("Do you want delete selected row?") ) {
        var target = $(this).data('target');
        $(this).closest(target+'-row').remove();            
    }
});
</script>
@stop
