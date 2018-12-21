@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.add_new') }}</span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> All Shippings</a>
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
                <input type="text" name="name" class="form-control" value="{{ Input::old('name', $info->post_title) }}" data-type="slug" data-slug=".to-slug"> 
                {!! $errors->first('name','<p class="text-danger my-2">:message</p>') !!}                  
            </div>

            
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control no-space to-slug" data-type="slugy" value="{{ Input::old('slug', $info->post_name) }}">
                {!! $errors->first('slug','<p class="text-danger my-2">:message</p>') !!} 
            </div>

            <div class="row">
                <div class="col form-group">
                    <label>{{ trans('backend.amount') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend amount-symbol">
                          <span class="input-group-text">{{ currency_symbol($setting->get_setting('currency')) }}</span>
                        </div>
                        <input type="text" name="amount" class="form-control numeric" value="{{ Input::old('amount', @$info->amount) }}"> 
                    </div>
                    {!! $errors->first('amount','<p class="text-danger my-2">:message</p>') !!}                  
                </div>                
                <div class="col form-group">
                    <label>Sub Title</label> 
                    <input type="text" name="sub_title" class="form-control" value="{{ Input::old('sub_title', @$info->sub_title) }}">          
                </div>
            </div>

            <div class="form-group">
                <label>Sub Title</label>
                <textarea type="text" name="description" class="form-control" rows="5">{{ Input::old('description', $info->post_content) }}</textarea>
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
                    <div class="row align-items-center">
                        <div class="col-auto text-right">
                            Order                   
                        </div>
                        <div class="col">
                            <input type="text" name="post_order" class="form-control numeric" value="{{ Input::old('post_order', @$info->post_order) }}">                        
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
@stop

@section('plugin_script')
@stop

@section('script')
@stop
