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
                <input type="text" name="name" class="form-control" value="{{ Input::old('name') }}" data-type="slug" data-slug=".to-slug"> 
                {!! $errors->first('name','<p class="text-danger my-2">:message</p>') !!}                  
            </div>

            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control no-space to-slug" data-type="slugy" value="{{ Input::old('slug') }}">
                {!! $errors->first('slug','<p class="text-danger my-2">:message</p>') !!}    
            </div>


            <div class="row">
                <div class="col form-group">
                    <label>{{ trans('backend.amount') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend amount-symbol">
                          <span class="input-group-text">{{ currency_symbol($setting->get_setting('currency')) }}</span>
                        </div>
                        <input type="text" name="amount" class="form-control numeric" value="{{ Input::old('amount') }}"> 
                    </div>
                    {!! $errors->first('amount','<p class="text-danger my-2">:message</p>') !!}                  
                </div>                
                <div class="col form-group">
                    <label>Sub Title</label> 
                    <input type="text" name="sub_title" class="form-control" value="{{ Input::old('sub_title') }}">          
                </div>
            </div>

            <div class="form-group">
                <label>{{ trans('backend.description') }}</label>
                <textarea type="text" name="description" class="form-control" rows="5">{{ Input::old('description') }}</textarea>
                {!! $errors->first('description','<p class="text-danger my-2">:message</p>') !!}                  
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

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.status') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', active_status(), Input::old('status'), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>
                    <div class="row align-items-center">
                        <div class="col-auto text-right">
                            Order                   
                        </div>
                        <div class="col">
                            <input type="text" name="post_order" class="form-control numeric" value="{{ Input::old('post_order', 0) }}">                        
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ trans('backend.add_new') }}</button>                     
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
