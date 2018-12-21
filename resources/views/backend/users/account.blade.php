@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.edit') }}</span>

<hr>
@stop

@section('content')
<form method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.profile') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ trans('backend.name') }}</label>
                        <div class="input-group">
                            <input type="text" name="firstname" class="form-control" placeholder="{{ trans('backend.firstname') }}" value="{{ Input::old('firstname', $info->firstname) }}">                    
                            <input type="text" name="lastname" class="form-control" placeholder="{{ trans('backend.lastname') }}" value="{{ Input::old('lastname', $info->lastname) }}">   
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
	                        <input type="text" name="email" class="form-control no-space" value="{{ Input::old('email', $info->email) }}">                    
	                        {!! $errors->first('email','<p class="text-danger my-2">:message</p>') !!}                 
	                    </div>
	                    <div class="col-sm form-group">
	                        <label>{{ trans('backend.username') }}</label>
	                        <input type="text" name="username" class="form-control no-space" value="{{ Input::old('username', $info->username) }}">                    
	                        {!! $errors->first('username','<p class="text-danger my-2">:message</p>') !!}                   
	                    </div>
	                </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.change_password') }}</div>
                <div class="card-body">
                    <p class="text-muted">{{ trans('messages.leave_password') }}</p>

                    <div class="row">
		                <div class="col-sm form-group">
	                        <label>{{ trans('backend.password') }}</label>
	                        <input type="password" name="new_password" class="form-control no-space" value="{{ Input::old('new_password') }}" autocomplete="new-password">                    
	                        {!! $errors->first('new_password','<p class="text-danger my-2">:message</p>') !!}             
	                    </div>
	                    <div class="col-sm form-group">
	                        <label>{{ trans('backend.confirm_password') }}</label>
	                        <input type="password" name="new_password_confirmation" class="form-control no-space" value="{{ Input::old('new_password_confirmation') }}">                    
	                        {!! $errors->first('new_password_confirmation','<p class="text-danger my-2">:message</p>') !!}      
	                    </div>
	                </div>
                </div>
            </div>

        </div>
        <div class="col-md-4">

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.date') }}</div>
                <div class="card-body pb-0">
                	<div class="row">
						<div class="col-lg col-md-12 col">               		 
		                    <label>{{ trans('backend.created_on') }}</label>
		                    <div class="form-group">
		                        <strong>{{ date_formatted($info->created_at) }}</strong> @ 
		                        <strong>{{ time_formatted($info->created_at) }}</strong>                          
		                    </div>
	                    </div> 
	                    <div class="col-lg col-md-12 col">
		                    <label>{{ trans('backend.updated_on') }}</label>
		                    <div class="form-group">
		                        <strong>{{ date_formatted($info->updated_at) }}</strong> @ 
		                        <strong>{{ time_formatted($info->updated_at) }}</strong>                          
		                    </div>                    	
	                    </div>
                	</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.profile_picture') }}</div>
                <div class="card-body">

                	@if( $info->profile_picture )
                    <img src="{{ has_image($info->profile_picture) }}" class="img-thumbnail w-100 mb-3">
                    @endif
                    <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png">
                </div>
            </div>

        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ trans('backend.save_changes') }}</button>                     
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
