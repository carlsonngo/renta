@extends('layouts.backend-fullwidth')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }} </span>
<span class="badge text-uppercase p-2 mr-3">/ {{ $c = count($files) }} {{ trans('backend.files') }}</span>

<div class="float-right">
    <button class="btn btn-sm btn-primary btn-insert" disabled>{{ trans('backend.insert') }}</button>    

    @if( has_access('media', ['add']) )
    <a href="{{ route($view.'.frame-add', query_vars()) }}" class="btn btn-sm btn-dark"> {{ trans('backend.add_new') }}</a>   
    @endif
</div>
<hr>
@stop

@section('content')

<form method="GET">

<?php parse_str(query_vars(), $qv); ?>
@foreach($qv as $q_k => $q_v)
<input type="hidden" name="{{ $q_k  }}" value="{{ $q_v }}">
@endforeach

<div class="form-row bg-white p-2 mb-3 border rounded">
    @if( Input::get('mode') != 'single' && Input::get('name') != 'slider' )
    <div class="col-12 col-md-auto">
        {{ Form::select('format', ['' => 'All media items'] + media_formats(), Input::get('format'), ['class' => 'mb-2 mb-lg-0 form-control onchange-submit']) }}
    </div>
    @endif

    <div class="col-12 col-md-auto">
        {{ Form::select('sort', media_sort(), Input::get('sort'), ['class' => 'mb-2 mb-lg-0 form-control onchange-submit']) }}
    </div>
    <div class="col-12 col-md-auto">
        <input type="text" class="form-control search" placeholder="Search media items...">
    </div>        
</div>
</form>

<!-- Modal -->
<div class="modal modal-img fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <div class="col-lg-8 col-md-6">
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
                    <a href="#" class="btn btn-outline-danger btn-sm btn-unlink"
                        data-url=""
                        data-toggle="confirm-modal" 
                        data-target=".confirm-modal" 
                        data-title="{{ trans('backend.confirm_delete') }}" 
                        data-body="{{ trans('messages.confirm_delete') }}?">{{ trans('backend.delete_permanently') }}</a> 
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
            <a href=".modal-img" data-toggle="modal-img" 
            data-unlink="{{ route('backend.media.unlink', $query_vars + ['folder' => $file['folder'], 'filename' => $file['id']]) }}">
            <img src="{{ asset($file['file']) }}?v={{ $file['date'] }}" class=" img-fluid" 
            data-large="{{ asset($file['large']) }}?v={{ $file['date'] }}"
            data-medium="{{ asset($file['medium']) }}?v={{ $file['date'] }}"
            data-full="{{ asset($file['original']) }}?v={{ $file['date'] }}"
            data-original="{{ $file['original'] }}"
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
.selected {
    border: 1px solid #007bff;    
}
</style>
@stop

@section('plugin_script')
@stop

@section('script')
<script>
$(document).on('click', '[data-toggle="modal-img"]', function(event) {
    event.preventDefault();

    var target   = $(this).attr('href'),
        fileinfo = $(this).closest('div').find('.fileinfo').html()
        unlink   = $(this).attr('data-unlink'),
        format   = $(this).closest('div').find('img').attr('data-format'),
        type     = $(this).closest('div').find('img').attr('data-type'),
        img      = $(this).closest('div').find('img').attr('data-large'),
        original = $(this).closest('div').find('img').attr('data-original'),
        mode     = getSearchParams('mode');

    if (event.altKey) {
        $(target).modal('show');
        $(target).find('.row').removeClass('align-items-center');
        if(format != 'image') {
            $(target).find('.row').addClass('align-items-center');
        }

        $(target).find('.fileinfo').html(fileinfo);
        $(target).find('img').attr('src', img);
        $(target).find('.btn-download').attr('href', original);
        $(target).find('.btn-unlink').attr('data-url', unlink);    
    }

    if( mode == 'single' ) {
        $('.img-thumbnail').removeClass('selected');
        $(this).closest('.img-thumbnail').addClass('selected');    
    } else {
        $(this).closest('.img-thumbnail').toggleClass('selected');        
    }

    $('.btn-insert').attr('disabled', 'disabled');
    if( $('.selected').length > 0 ) {
        $('.btn-insert').removeAttr('disabled');
    }
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


$(document).on('click', '.btn-insert', function(){


    var target  = getSearchParams('target'),
        mode    = getSearchParams('mode'),
        name    = getSearchParams('name'),
        imgtype = ["gif", "jpeg", "jpg", "png"],
        filepath = '';

    if( mode == 'editor' ) {

        $('.selected').each(function(){

            var path = $(this).find('img').attr('data-full');     
            var filename = path.split('/').pop().replace(/\.[^/.]+$/, "");

            if ($.inArray( path.split('.').pop().toLowerCase().split('?')[0], imgtype) < 0) {
                filepath += '<a href="'+path+'">'+filename+'</a> ';
            } else {
                filepath += '<img src="'+path+'"> ';
            }

        });
    

        $( target, window.parent.tinymce.get(target).insertContent(filepath) );
    }

    if( mode == 'single' ) {
        var large  = $('.selected').find('img').attr('data-large'),
         original  = $('.selected').find('img').attr('data-original');

        name = name ? name : 'image';
        filepath = '<li class="list-unstyled"><div class="media-thumb img-thumbnail"><img src="'+large+'" class="img-fluid w-100"><input type="hidden" name="'+name+'" value="'+original+'"><a href="" class="delete-media"><i class="fas fa-trash"></i></a></div></li>';
    }

    if( mode == 'multiple' ) {

        $('.selected').each(function(){

            var large  = $(this).find('img').attr('data-medium'),
             original  = $(this).find('img').attr('data-original');

            name = name ? name : 'image';
            filepath += '<div class="m-list col-lg-2 col-sm-3 mb-4"><div class="media-thumb img-thumbnail"><img src="'+large+'" class="img-fluid w-100"><input type="hidden" name="'+name+'[]" value="'+original+'"><a href="" class="delete-media"><i class="fas fa-trash"></i></a></div></div>';

        });

    }

    if( mode == 'gallery' ) {
        $('.selected').each(function(){
            var large  = $(this).find('img').attr('data-medium'),
                original  = $(this).find('img').attr('data-original'),
                gid = Math.random().toString(36).substr(2, 5);
 
            name = name ? name : 'image';
            filepath += '<div class="m-list col-lg-3 col-sm-6 mb-3 px-2"><div class="media-thumb img-thumbnail"><img src="'+large+'" class="img-fluid w-100 mb-1"><input type="hidden" name="gallery['+name+']['+gid+']" value="'+original+'"><input type="text" name="gallery[name]['+gid+']" class="form-control rounded-0"><label class="mt-2 mb-0 fsk18"><input type="hidden" name="gallery[fsk18]['+gid+']" value="0"><input type="checkbox" name="gallery[fsk18]['+gid+']" value="1" class="checkboxes"> Mark as FSK18</label><a href="" class="delete-media" tabindex="-1"><i class="fas fa-trash"></i></a></div></div>';
        });
    }

    if( mode == 'gallery-simple' ) {
        $('.selected').each(function(){
            var large  = $(this).find('img').attr('data-medium'),
             original  = $(this).find('img').attr('data-original');

            name = name ? name : 'image';
            filepath += '<div class="m-list col-lg-4 col-sm-6 mb-4"><div class="media-thumb img-thumbnail"><img src="'+large+'" class="img-fluid w-100"><input type="hidden" name="'+name+'[]" value="'+original+'"><a href="" class="delete-media" tabindex="-1"><i class="fas fa-trash"></i></a></div></div>';
        });
    }    

    if( mode == 'single' ) { 
        $( target, window.parent.document ).html(filepath);
    }

    if( mode == 'multiple' || mode == 'gallery' || mode == 'gallery-simple' ) { 
        $( target, window.parent.document ).append(filepath);
    }

    $('#media-modal', window.parent.document ).modal('hide').click();

});
</script>
@stop
