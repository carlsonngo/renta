@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2">/ {{ trans('backend.add_new') }}</span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_users') }}</a>
</div>
<hr>
@stop

@section('content')

<form method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-lg-8 col-md-7">

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.profile') }}</div>
                <div class="card-body">

                    <div class="form-group">
                        <label>{{ trans('backend.name') }}</label>
                        <div class="input-group">
                            <input type="text" name="firstname" class="form-control" placeholder="{{ trans('backend.firstname') }}" value="{{ Input::old('firstname') }}">                    
                            <input type="text" name="lastname" class="form-control" placeholder="{{ trans('backend.lastname') }}" value="{{ Input::old('lastname') }}">   
                        </div>
                        <div class="row">
                            <div class="col pr-0">
                            {!! $errors->first('firstname','<p class="text-danger my-2">:message</p>') !!}                                  
                            </div>
                            <div class="col pl-0">
                            {!! $errors->first('lastname','<p class="text-danger my-2">:message</p>') !!}                                
                            </div>  
                        </div>
                    </div>

                    <div class="row">
	                    <div class="col-sm form-group">
	                        <label>{{ trans('backend.email') }}</label>
	                        <input type="text" name="email" class="form-control no-space" value="{{ Input::old('email') }}">                    
	                        {!! $errors->first('email','<p class="text-danger my-2">:message</p>') !!}  
	                    </div>
	                    <div class="col-sm form-group">
	                        <label>{{ trans('backend.group') }}</label>
	                        {{ Form::select('group', user_group(), Input::old('group'), ['class' => 'form-control'] ) }}
	                        {!! $errors->first('group','<p class="text-danger my-2">:message</p>') !!}  
	                    </div>
                    </div>
                    <div class="row">
		                <div class="col-sm form-group">
	                        <label>{{ trans('backend.username') }}</label>
	                        <input type="text" name="username" class="form-control no-space" value="{{ Input::old('username') }}">                    
	                        {!! $errors->first('username','<p class="text-danger my-2">:message</p>') !!}  
	                    </div>
	                    <div class="col-sm form-group">
	                        <label>{{ trans('backend.password') }}</label>
	                        <input type="password" name="password" class="form-control no-space" value="{{ Input::old('password') }}" autocomplete="new-password">                    
	                        {!! $errors->first('password','<p class="text-danger my-2">:message</p>') !!}  
	                    </div>
	                </div>

                </div>
            </div>

        </div>

        <div class="col-lg-4 col-md-5">
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.date') }}</div>
                <div class="card-body pb-0">

                    <label>{{ trans('backend.created_on') }}</label>
                    <div class="form-group">
                        <strong>{{ date_formatted(date('Y-m-d')) }}</strong> @ 
                        <strong>{{ time_formatted(date('Y-m-d H:i:s')) }}</strong>                          
                    </div>

                </div>
            </div>


            <div class="card">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.profile_picture') }}</div>
                <div class="card-body">
                    <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png">
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ __('backend.add_new') }}</button>                     
    </div>
</form>

<div class="mb-5 pb-4"></div>

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
