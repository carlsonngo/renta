@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.add_new') }}</span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_coupons') }}</a>
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
                <input type="text" name="name" class="form-control no-space" value="{{ Input::old('name', $info->post_title) }}"> 
                {!! $errors->first('name','<p class="text-danger my-2">:message</p>') !!}                  
            </div>

            <div class="row">
                <div class="col form-group">
                    <label>{{ trans('backend.type') }}</label>
                    {{ Form::select('discount_type', discount_types(), $discount_type = Input::old('discount_type', @$info->post_name), ['class' => 'form-control']) }}
                    {!! $errors->first('discount_type','<p class="text-danger my-2">:message</p>') !!}                  
                </div>
                <div class="col form-group">
                    <label>{{ trans('backend.amount') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend amount-symbol fixed" style="{{ $discount_type=='fixed'?'':'display:none;' }}">
                          <span class="input-group-text">{{ currency_symbol($setting->get_setting('currency')) }}</span>
                        </div>
                        <input type="text" name="amount" class="form-control numeric" value="{{ Input::old('amount', @$info->amount) }}"> 
                        <div class="input-group-append amount-symbol percent" style="{{ $discount_type=='percent'?'':'display:none;' }}">
                          <span class="input-group-text">%</span>
                        </div>
                    </div>
                    {!! $errors->first('amount','<p class="text-danger my-2">:message</p>') !!}                  
                </div>                
            </div>

            <div class="row">
                <div class="col form-group">
                    <label>{{ trans('backend.date_start') }}</label>  
                    <div class="input-group">
                        <input type="text" name="date_start" class="form-control w-50 datepicker date-format variable_sale_date_start" placeholder="mm-dd-yyyy" 
                        value="{{ Input::old('date_start', @$info->date_start) }}">  
                        <input type="text" name="time_start" class="form-control w-25 timepicker time-format" placeholder="00:00" 
                        value="{{ Input::old('time_end', @$info->time_start) }}">  
                    </div>                            
                </div>
                <div class="col form-group">
                    <label>{{ trans('backend.date_end') }}</label>  
                    <div class="input-group">
                        <input type="text" name="date_end" class="form-control w-50 datepicker date-format variable_sale_date_start" placeholder="mm-dd-yyyy" 
                        value="{{ Input::old('date_start', @$info->date_end) }}">  
                        <input type="text" name="time_end" class="form-control w-25 timepicker time-format" placeholder="00:00" 
                        value="{{ Input::old('time_end', @$info->time_end) }}">  
                    </div>                            
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox align-items-center d-flex">
                    <input type="hidden" name="one_time_use" value="0">
                    {{ Form::checkbox('one_time_use', 1, Input::old('one_time_use', @$info->one_time_use), ['class' => 'custom-control-input', 'id' => 'one_time_use']) }}
                    <label class="custom-control-label" for="one_time_use">One-time use only</label> 
                </div>
            </div>

            <div class="form-group">
                <label>{{ trans('backend.description') }}</label>
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
<script>
$(document).on('change', '[name="discount_type"]', function(){
    var val = $(this).val();
    $('.amount-symbol').hide();
    $('.'+val).show();
});  
</script>
@stop
