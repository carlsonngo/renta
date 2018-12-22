@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.edit') }}</span>

<div class="float-right">
    <a href="{{ route($view.'.add') }}" class="btn btn-sm btn-dark"> {{ trans('backend.add_new') }}</a>   
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_users') }}</a>
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
	                        <label>{{ trans('backend.group') }}</label>
	                        {{ Form::select('group', user_group(), Input::old('group', $info->group), ['class' => 'form-control'] ) }}
	                        {!! $errors->first('group','<p class="text-danger my-2">:message</p>') !!}  
	                    </div>
                    </div>


                    <div class="row">
	                    <div class="col-sm form-group">
	                        <label>{{ trans('backend.username') }}</label>
	                        <input type="text" name="username" class="form-control no-space" value="{{ Input::old('username', $info->username) }}">                    
	                        {!! $errors->first('username','<p class="text-danger my-2">:message</p>') !!}  
	                    </div>
	                    <div class="col-sm form-group">
	                        <label>{{ trans('backend.password') }}</label>
	                        <input type="password" name="password" class="form-control no-space" value="{{ Input::old('password') }}" autocomplete="new-password">                    
	                        <div class="text-muted mt-2">{{ trans('messages.leave_password') }}</div>
	                        {!! $errors->first('password','<p class="text-danger my-2">:message</p>') !!}  
	                    </div>
	                </div>


                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">Extra</div>
                <div class="card-body">
				@foreach($info->usermetas as $user)
				<div class="row mb-3 align-items-center">
				    <div class="col-4 text-muted">{{ code_to_text($user->meta_key) }}</div>
				    <div class="col-8">{!! form_field($user->meta_key, $user->meta_value) !!}</div>
				</div>
				@endforeach
				</div>
			</div>

        </div>

        <div class="col-lg-4 col-md-5">
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
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">
                    {{ trans('backend.status') }}
                    <span class="float-right">{{ status_ico($info->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', user_status(), Input::old('status', $info->status), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>

                    @if( !@$info->confirmed && @$info->membership == 'premium' && @$info->group == 'customer' )
                    <div class="custom-control custom-checkbox align-items-center d-flex">
                        <input type="hidden" name="confirmed" value="0">
                        {{ Form::checkbox('confirmed', 1, @$info->confirmed, ['class' => 'custom-control-input', 'id' => 'confirmed']) }}
                        <label class="custom-control-label" for="confirmed">Confirm</label> 
                    </div>
                    @endif


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
