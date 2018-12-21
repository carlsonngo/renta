@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span> 
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.edit') }}</span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index') }}" class="btn btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i>  All Reports</a>
</div>

<hr>
@stop

@section('content')
<form method="POST">
    {{ csrf_field() }}

 <div class="row">
        <div class="col-lg-8 col-md-7">

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.details') }}</div>
                <div class="card-body">

                    <div class="form-group">
                        <h5>{{ trans('backend.message') }}</h5>
                        <p>{{ $info->post_title }}</p>                  
                    </div>
                    <hr>
                    <div class="form-group">
                        <h5>URL</h5>
                        <p><a href="{{ $info->post_name }}" target="_blank">{{ $info->post_name }}</a></p>                 
                    </div>
                    <hr>
                    <div class="form-group error-info">
                        {!! $info->post_content !!}         
                    </div>               
               
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
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.status') }}
                    <div class="float-right">{{ status_ico($info->post_status) }}</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', order_status(), Input::old('status', $info->post_status), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.count') }}
                </div>
                <div class="card-body pb-0">
                    <div class="form-group h1 text-center">
                        <b>{{ number_format($info->post_order) }}</b>  
                    </div>
                </div>
            </div>


        </div>
    </div>

    @if( has_access($module, ['edit', 'trash_restore']) )
    <div class="form-actions">

        @if( has_access($module, ['edit']) )
        <button type="submit" class="btn btn-primary mr-3">{{ trans('backend.save_changes') }}</button>     
        @endif

        @if( has_access($module, ['trash_restore']) )
        <a href="#" 
        class="text-danger" 
        data-url="{{ URL::route($view.'.delete', [$info->id, query_vars()]) }}"
        data-toggle="confirm-modal" 
        data-target=".confirm-modal" 
        data-title="{{ trans('backend.confirm_move_trash') }}" 
        data-body="{{ trans('messages.confirm_move_trash') }} ID: <b>#{{ $info->id }}</b>?"> {{ trans('backend.move_trash') }}</a>     
        @endif
    </div>
    @endif

</form>

<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
<style>
.error-info pre {
     white-space: pre-wrap;   
     white-space: -moz-pre-wrap; 
     white-space: -pre-wrap;      
     white-space: -o-pre-wrap;    
     word-wrap: break-word;      
}
</style>
@stop

@section('plugin_script')
@stop

@section('script')
@stop
