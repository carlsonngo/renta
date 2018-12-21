@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }} </span>
<span class="badge text-uppercase p-2 mr-3">/ {{ $c = count($files) }} f{{ trans('backend.files') }}</span>

@if( has_access($module, ['add']) )
<a href="{{ route($view.'.add') }}" class="btn btn-sm btn-dark float-right"> {{ trans('backend.add_new') }}</a>   
@endif

<hr>
@stop

@section('content')

<form method="GET">
<input type="hidden" name="lang" value="{{ Input::get('lang') }}">
<div class="form-row bg-white p-2 mb-3 border rounded">
    <div class="col-12 col-md-auto">
        {{ Form::select('format', ['' => 'All media items'] + media_formats(), Input::get('format'), ['class' => 'mb-2 mb-lg-0 form-control onchange-submit']) }}
    </div>
    <div class="col-12 col-md-auto">
        {{ Form::select('sort', media_sort(), Input::get('sort'), ['class' => 'mb-2 mb-lg-0 form-control onchange-submit']) }}
    </div>
    <div class="col-12 col-md-auto">
        <input type="text" class="form-control search" placeholder="{{ trans('backend.search_media_items') }} ...">
    </div>        
</div>
</form>

<!-- Modal -->
<div class="modal modal-img fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('backend.attachment_details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8 col-md-6 align-self-center">
                        <div class="form-group text-center">
                            <img src="" class="img-thumbnail">  
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="bg-light p-4 rounded border fileinfo">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-outline-primary btn-sm btn-download" download> {{ trans('backend.download') }}</a>

                    @if( has_access($module, ['trash']) )
                    <a href="#" class="btn btn-outline-danger btn-sm btn-unlink"
                        data-url=""
                        data-toggle="confirm-modal" 
                        data-target=".confirm-modal" 
                        data-title="{{ trans('backend.confirm_delete') }}" 
                        data-body="{{ trans('messages.confirm_delete') }}?">{{ trans('backend.delete_permanently') }}</a> 
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<?php parse_str( query_vars(), $query_vars ); ?>

<div class="media-library px-2">
    <div class="row align-items-center">
    @foreach ($files as $file)
        <div class="m-lib col-lg-1 col-md-2 col-sm-3 col-6 p-1 mb-0 pl-2" data-val="{{ $file['name'] }}">
            <div class="text-center img-thumbnail">
            <a href=".modal-img" data-toggle="modal" 
            data-unlink="{{ route('backend.media.unlink', $query_vars + ['folder' => $file['folder'], 'filename' => $file['id']]) }}">
            <img src="{{ asset($file['file']) }}?v={{ $file['date'] }}" class=" img-fluid" 
            data-large="{{ asset($file['large']) }}?v={{ $file['date'] }}"
            data-original="{{ asset($file['original']) }}"
            data-type="{{ $file['type'] }}"
            data-format="{{ $file['format'] }}"
            data-toggle="tooltip"
            title="{{ $file['name'] }}">       
            <div class="fileinfo" style="display:none;">
                <label class="font-weight-bold">{{ trans('backend.file_name') }}:</label>
                <p>{{ $file['name'] }}</p>
         
                <label class="font-weight-bold">{{ trans('backend.file_type') }}:</label> 
                <p><?php echo $file['type']; ?></p>
                <label class="font-weight-bold">{{ trans('backend.file_size') }}:</label> 
                <p><?php echo $file['size']; ?></p>

                @if( $file['dimension'] )
                <label class="font-weight-bold">{{ trans('backend.dimensions') }}:</label> 
                <p><?php echo $file['dimension']; ?></p>
                @endif

                <label class="font-weight-bold">{{ trans('backend.uploaded_on') }}:</label> 
                <p><?php echo $file['date']; ?></p>    

            </div>
            </a>                     
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('style')
<style>
.media-library {
    height: 70vh;
    overflow-y: auto;
    overflow-x: hidden;
}   
</style>
@stop

@section('plugin_script')
@stop

@section('script')
<script>
$(document).on('click', '[data-toggle="modal"]', function() {
    var target   = $(this).attr('href'),
        fileinfo = $(this).closest('div').find('.fileinfo').html()
        unlink   = $(this).attr('data-unlink'),
        format   = $(this).closest('div').find('img').attr('data-format'),
        type     = $(this).closest('div').find('img').attr('data-type'),
        img      = $(this).closest('div').find('img').attr('data-large'),
        original = $(this).closest('div').find('img').attr('data-original');


    $(target).find('.fileinfo').html(fileinfo);
    $(target).find('img').attr('src', img);
    $(target).find('.btn-download').attr('href', original);
    $(target).find('.btn-unlink').attr('data-url', unlink);    
});  

$(document).on('keyup', '.search', function() {
    $('.media-library').find('.m-lib').hide();
    var txt = $(this).val();
    $('.media-library').find('.m-lib').each(function(){
       if( $(this).attr('data-val').toUpperCase().indexOf(txt.toUpperCase()) != -1){
           $(this).show();
       }
    });
});     

$(document).on('change', '.onchange-submit', function() {
    $('form').submit();
});     

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

</script>
@stop
