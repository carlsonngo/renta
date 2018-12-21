@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.add_new') }}</span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_groups') }}</a>
</div>

<hr>
@stop

@section('content')
<form method="POST">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-lg-8 col-md-7">

            <div class="form-group">
                <label>{{ trans('backend.name') }}</label>
                <input type="text" name="name" class="form-control form-control-lg" value="{{ Input::old('name', $info->post_title) }}"> 
                {!! $errors->first('name','<p class="text-danger my-2">:message</p>') !!}                  
            </div>

            <div class="form-group">
                <label>{{ trans('backend.description') }}</label>
                <textarea type="text" name="description" class="form-control form-control-lg" rows="5">{{ Input::old('description', $info->description) }}</textarea>
                {!! $errors->first('description','<p class="text-danger my-2">:message</p>') !!}                  
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
                    <span class="float-right">{{ status_ico($info->post_status) }}</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', active_status(), Input::old('status', $info->post_status), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
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
@stop

@section('plugin_script')
@stop

@section('script')
@stop
