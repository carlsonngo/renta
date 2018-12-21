@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.settings') }}</span>
<hr>
@stop

@section('content')
<form method="POST">

    {{ csrf_field() }}

    <div class="custom-control custom-checkbox align-items-center d-flex">
        <input type="hidden" name="status" value="inactived">
        {{ Form::checkbox('status', 'actived', (@$info->post_status=='actived')?1:0, ['class' => 'custom-control-input', 'id' => 'status']) }}
        <label class="custom-control-label" for="status">{{ trans('backend.enable_slider') }}</label> 
    </div>

    <div class="row media-multiple my-4 sortable">
    <input type="hidden" name="slider" value="">

    @if( @$info->post_content )
        @foreach(json_decode($info->post_content) as $row)
        <div class="m-list col-lg-2 col-sm-3 mb-4">
            <div class="media-thumb img-thumbnail">
            <img src="{{ has_image(str_replace('large', 'medium', $row)) }}" class="img-fluid w-100">
            <input type="hidden" name="slider[]" value="{{ $row }}">
            <a href="" class="delete-media"><i class="fas fa-trash"></i></a>
            </div>
        </div>
        @endforeach
    @endif

    </div>


    <button type="button" class="filemanager btn btn-sm btn-outline-primary" 
data-href="{{ route('backend.media.frame', ['name' => 'slider', 'format' => 'image', 'mode' => 'multiple', 'target' => '.media-multiple']) }}"><i class="fas fa-plus"></i> {{ trans('backend.add_slider') }}</button>

<button class="btn btn-sm btn-primary">{{ trans('backend.save') }}</button>

</form>



<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
