@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="text-uppercase p-2 mr-3">/ {{ trans('backend.add_new') }}</span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index') }}" class="btn float-right btn-sm btn-outline-dark">
        <i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_bookings') }}
    </a>
    <a href="{{ URL::route('backend.posts.index', ['post_type' => 'event']) }}" class="btn float-right btn-sm btn-outline-dark mr-2">
        <i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_events') }}
    </a>
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
                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <img src="{{ has_image($info->image) }}" class="img-fluid rounded border">
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted"><i class="far fa-calendar mr-1"></i> {{ trans('backend.date_posted') }}</label> 
                            <h6 class="mb-4">{{ time_ago($info->created_at) }}</h6>
                            @if( $info->date_start || $info->time_start )
                            <label class="text-muted"><i class="far fa-calendar mr-1"></i> {{ trans('backend.event_schedule') }}</label>
                            <div class="h6 mb-4">
                                @if( $info->date_start )
                                <span class="text-muted">on</span> <span class="mr-2">{{ date_formatted(@$info->date_start) }}</span> 
                                @endif
                                @if( $info->time_start )
                                <span class="text-muted">from</span> {{ @$info->time_start }}
                                @endif
                            </div>
                            @endif
                            <label class="text-muted"><i class="far fa-user-circle mr-1"></i> {{ trans('backend.organizer') }}</label>
                            <h6 class="mb-4">{{ @$info->organizer }}</h6>
                            <label class="text-muted"><i class="fas fa-map-marked-alt mr-1"></i> {{ trans('backend.location') }}</label> 
                            <h6 class="mb-4">{{ @$info->location }}</h6>
                        </div>
                    </div>
                    <p>{{ trans('backend.description') }}</p>
                    {!! trans_post($info, 'post_content', '_content') !!}
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.register') }}</div>
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <label>{{ trans('backend.nickname') }}</label>
                            <p class="text-muted small">{{ trans('messages.public_registration') }}</p>
                        </div>
                        <div class="col">
                            <input type="text" name="nickname" class="form-control" value="{{ Input::old('nickname') }}">   
                            {!! $errors->first('nickname','<p class="text-danger my-2">:message</p>') !!}          
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <label>{{ trans('backend.gender') }}</label>            
                        </div>
                        <div class="col">
                            {{ Form::select('gender', ['Select Gender'] + genders(), Input::old('gender'), ['class' => 'form-control']) }}
                            {!! $errors->first('gender','<p class="text-danger my-2">:message</p>') !!}             
                        </div>
                    </div>  
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <label>{{ trans('backend.name') }}</label>     
                            <p class="text-muted small">{{ trans('backend.non_public') }}</p>
                        </div>
                        <div class="col">
                            <input type="text" name="name" class="form-control" value="{{ Input::old('name') }}">   
                            {!! $errors->first('name','<p class="text-danger my-2">:message</p>') !!}          
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <label>{{ trans('backend.email') }}</label>      
                        </div>
                        <div class="col">
                            <input type="text" name="email" class="form-control no-space" value="{{ Input::old('email') }}"> 
                            {!! $errors->first('email','<p class="text-danger my-2">:message</p>') !!}          
                        </div>
                    </div>
                    @if( @$info->event_type == 'ticket' )
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <label>{{ trans('backend.choice_of_card') }}</label>
                        </div>


                        <div class="col-md-8">
                            <div class="row">
                            <?php 
                            $total_amount = 0;
                            $amount = json_decode(@$info->amount); ?>

                            @if( $amount )
                                @foreach($amount as $am_k => $am_v)
                                <div class="col-md-6 form-group">
                                    <?php $total_amount = $am_v * Input::old('attendee.'.$am_k.'.count'); ?>
                                    <label>{{ trans('backend.'.$am_k) }}</label> <span class="float-right">( {{  currency_symbol($currency) }} <span class="calc-label">{{ number_format($total_amount, 2) }}</span>  )</span>
                                    {{ Form::select('attendee['.$am_k.'][count]', range(0, 10), Input::old('attendee.'.$am_k.'.count'), ['class' => 'calc form-control', "data-id" => $am_k]) }}       
                                    <input type="hidden" name="attendee[{{ $am_k }}][amount]" value="{{ @$am_v }}">         
                                </div>
                                @endforeach       
                            @else
                                <p>Fields Not Set</p>
                            @endif
                            </div>
                        </div>

                    </div>
                    <div class="row align-items-center mb-4">
                        <div class="col-md-4">
                            <label><b>{{ trans('backend.total') }}</b></label>     
                        </div>
                        <div class="col">
                            <input type="hidden" name="total" value="{{ $total = Input::old('total', 0) }}">
                            <h5 class="font-weight-bold">{{  currency_symbol($currency) }} <span class="calc-total">{{ number_format($total, 2) }}</span></h5>
                            {!! $errors->first('total','<p class="text-danger my-2">:message</p>') !!}      
                        </div>
                    </div>
                    @else
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <label>{{ trans('backend.whos_coming') }}</label>       
                        </div>


                        <div class="col-md-8">
                            <div class="row">
                            <?php $amount = json_decode(@$info->amount); ?>
                            @if( $amount )
                                @foreach($amount as $am_k => $am_v)
                                <div class="col-md-6 form-group">
                                    <?php $total_amount = $am_v * Input::old('attendee.'.$am_k.'.count'); ?>
                                    <label>{{ trans('backend.'.$am_k) }}</label></span>
                                    {{ Form::select('attendee['.$am_k.'][count]', range(0, 10), Input::old('attendee.'.$am_k.'.count'), ['class' => 'calc form-control', "data-id" => $am_k]) }}            
                                </div>
                                @endforeach   
                            @else
                                @foreach(attendees() as $att_k => $att_v)
                                <div class="col-md-6 form-group">
                                    <label>{{ $att_v }}</label>
                                    {{ Form::select('attendee['.$att_k.'][count]', range(0, 10), Input::old('attendee.'.$att_k.'.count'), ['class' => 'calc form-control']) }}                  
                                </div>
                                @endforeach                                 
                            @endif          
                            </div>
                        </div>

                    </div>
                    @endif
                    <div class="row align-items-center mb-4">
                        <div class="col-md-4">
                            <label>{{ trans('backend.comment') }}</label>      
                        </div>
                        <div class="col">
                            <textarea name="comment" class="form-control" rows="4">{{ Input::old('comment') }}</textarea>
                            {!! $errors->first('comment','<p class="text-danger my-2">:message</p>') !!}          
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.date') }}</div>
                <div class="card-body">
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
                        {{ Form::select('status', order_status(), Input::old('status'), ['class' => 'form-control'] ) }}
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

<style>
.order-notes {
    max-height: 300px;
    overflow-y: auto;
}
.note-content {
    background: #eef1f5;
    position: relative; 
    padding: 10px;
    margin-bottom: 10px;
    font-size: 13px;
}
.note-content:after {
    content: '';
    position: absolute;
    bottom: -15px;
    left: 20px;
    border-width: 15px 15px 0 0;
    border-style: solid;
    border-color: #eef1f5 transparent;
}
</style>
@stop

@section('plugin_script')
@stop

@section('script')
<script>
$(document).on('change', '.calc', function() {
    var total = gtotal = 0
    val       = $(this).val(), 
    name      = $(this).attr('data-id'), 
    amount    = $('[name="attendee['+name+'][amount]"]').val();
    subtotal  = (val * amount).toFixed(2);

    $(this).closest('div').find('.calc-label').html(subtotal);
    $('.calc-label').each(function() {
        total += Number($(this).html());
    });
    gtotal = Number(total).toFixed(2);
    $('[name="total"]').val(gtotal);
    $('.calc-total').html(gtotal);
});

$(document).on('click', '.edit-details', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    $(target+'-details, '+target+'-form').toggle('fast');
});  
</script>
@stop
