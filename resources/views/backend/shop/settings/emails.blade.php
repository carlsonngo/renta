@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.settings') }}</span>

<hr>
@stop

@section('content')
<form method="POST">
    {{ csrf_field() }}
    <div class="card mb-3">
        <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.details') }}</div>
        <div class="card-body">
            <!-- Nav pills -->
            <ul class="nav nav-pills">
                @foreach(shop_emails() as $se_k => $se_v)
                <li class="nav-item">                    
                    <a class="nav-link {{ actived($se_k, Input::get('email') ) }}" href="{{ route($view.'.emails', ['email' => $se_k]) }}">{{ $se_v }}</a>
                    
                </li>
                @endforeach

            </ul>
            <hr>

            <!-- Tab panes -->
            <div class="p-0 ">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>{{ trans('backend.subject') }}</label>
                            <input type="text" name="subject" class="form-control" value="{{ Input::old('subject', @$info->post_title) }}">
                            {!! $errors->first('subject','<p class="text-danger my-2">:message</p>') !!}                      
                        </div>
                        <div class="form-group">
                            <label>{{ trans('backend.message') }}</label>
                            <textarea name="message" class="form-control tinymce" rows="8">{{ Input::old('message', @$info->post_content) }}</textarea>   
                            {!! $errors->first('message','<p class="text-danger my-2">:message</p>') !!}               
                        </div>                            
                    </div>
                    <div class="col-md-5">
                        <label>Shortcode</label>
                        <div class="row">
                            @foreach(email_shortcodes(Input::get('email', 'booking')) as $email)
                            <div class="col-6 mb-3">
                                <input type="text" value="{{ $email }}" class="form-control form-control-sm shortcode rounded text-center" readonly>
                            </div>
                            @endforeach                                
                        </div>
                    </div>
                </div>
            </div>
     
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ trans('backend.save_changes') }}</button>                     
    </div>
</form>

<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
<style>
.shortcode:hover {
    background-color: #e9ecef !important;
    border-color: #007bff !important;
}    
.shortcode {
    background-color: #fff !important;
} 
</style>
@stop

@section('plugin_script')
@stop

@section('script')
@stop
