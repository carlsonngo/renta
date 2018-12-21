@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.upload') }}</span>
<a href="{{ route($view.'.index') }}" class="btn btn-sm btn-dark float-right">{{ $label }}</a>   
<hr>
@stop

@section('content')

<form action="{{ route('backend.media.upload') }}"  method="post" enctype="multipart/form-data" class="dropzone p-0">
    {{ csrf_field() }}

    <div class="dz-message needsclick d-row d-flex align-items-center justify-content-center">
    <p class="h6">
        {{ trans('messages.media_upload_drop') }} <br>
        <small class="py-4">or</small>
        <br> {{ trans('messages.media_upload_click') }}
    </p>
    </div>            

    <div class="fallback">
        <input name="file" type="file" multiple />
    </div>
</form>

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/dropzone/dropzone.css') }}">
<style>
.needsclick {
    height: 60vh;    
}
.dropzone.dz-started .dz-message {
    display: none !important;
}
.dropzone.dz-clickable {
    cursor: pointer;
}
.dropzone {
    background: #fff;
    border: 2px dashed #0087F7;
    border-radius: 5px;
    height: 70vh;
    overflow-y: auto;
}  
.dropzone .dz-message {
    text-align: center;
    margin: 2em 0;
}
</style>
@stop

@section('plugin_script')
<script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
@stop

@section('script')
<script>

Dropzone.autoDiscover = false;
$(".dropzone").dropzone({
   init: function () {
        var totalFiles = 0,
            completeFiles = 0;
        this.on("sending", function (file) {
            window.onbeforeunload = function() {
                return false;
            }
        });
        this.on("addedfile", function (file) {
            totalFiles += 1;
        });
        this.on("removed file", function (file) {
            totalFiles -= 1;
        });
        this.on("complete", function (file) {
            completeFiles += 1;
            if (completeFiles === totalFiles) {
                window.onbeforeunload = null;        
            }
        });
    }
});    
</script>
@stop
