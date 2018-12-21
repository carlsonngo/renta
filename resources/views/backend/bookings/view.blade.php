@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }}</span> 
/ <span class="badge text-uppercase p-2 mr-3"><a href="{{ route($view.'.index', ['post_type' => $info->post_type]) }}">{{ event_type($info->post_type) }}</a></span>

<div class="float-right">
    @if( has_access($module, ['calendar']) )
    <a class="btn btn-sm btn-dark mr-2" href="{{ route('backend.bookings.calendar') }}">{{ trans('backend.view_calendar') }}</a>
    @endif

    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark">
        <i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_bookings') }}
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
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.event') }} {{ trans('backend.details') }}</div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 col-sm-6 text-center mb-4">

                            <a href="{{ has_image(@$event->image) }}" class="btn-img-preview" data-title="Event Photo">
                                <img src="{{ has_image(@$event->image) }}" class="img-fluid rounded border w-100"> 
                            </a>    

                        </div>
                        <div class="col-md-8 col-sm-6">

                            <label class="text-muted">{{ trans('backend.title') }}</label>
                            <div class="mb-4">
                                <span class="mb-4 h6">{!! trans_post($event, 'post_title', '_title') !!}</span> - 
                                <a href="{{ route('backend.posts.edit', [$event->id, 'post_type' => 'event']) }}">{{ trans('backend.view') }}</a>                                
                            </div>

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
                                @if( is_past_date(@$info->date_start.' '.@$info->time_start) )
                                <div class="mt-2">
                                    <label class="badge badge-danger text-uppercase small px-3 py-1"><b>{{ trans('backend.past_event') }}</b></label>
                                </div>               
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
                    <div class="text-justify">{!! trans_post($event, 'post_content', '_content') !!}</div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">
                    {{ trans('backend.booking_details') }}  <span class="float-right">{{ @$info->confirmed ? status_ico('confirmed') : '' }}</span>
                </div>
                <div class="card-body">

                    <label class="text-muted">{{ trans('backend.verification_code') }} :</label>
                    <h4>{{ $info->post_name }}</h4>
                    <hr>
                    <div class="billing-details" style="{{ count($errors->all()) ? 'display: none;' : '' }}">

                        @if( has_access($module, ['add_edit']) )
                        <a href="#" class="edit-details float-right" data-target=".billing"><i class="far fa-edit"></i> {{ trans('backend.edit') }}</a>
                        @endif

                        <p><span class="text-muted">{{ trans('backend.nickname') }} :</span><br>
                            {{ $info->nickname }}
                        </p>
                        <p><span class="text-muted">{{ trans('backend.name') }} :</span><br>
                            {{ $info->name }}
                        </p>
                        <p><span class="text-muted">{{ trans('backend.email') }} :</span><br>
                            {{ $info->post_title }}
                        </p>

                        <?php $attendee = json_decode(@$info->attendee); ?>
                        @foreach($attendee as $a_k => $a_v)                        
                        <p><span class="text-muted">{{ trans('backend.'.$a_k) }} :</span><br>
                            @if( @$info->post_type == 'ticket' )
                                {{ $a_v->count }} x {{ $a_v->amount }} ( {{ amount_formatted($a_v->count * $a_v->amount, $info->currency) }} ) 
                            @else
                                {{ $a_v->count }}
                            @endif
                        </p>
                        @endforeach

                        @if( @$info->total )
                        <p><span class="text-muted">{{ trans('backend.total') }} :</span><br>
                            {{ amount_formatted($info->total, $info->currency) }}
                        </p>
                        @endif
                        <p><span class="text-muted">{{ trans('backend.comment') }} :</span><br>
                            {{ $info->post_content }}
                        </p>
                    </div>
                    <div class="billing-form" style="{{ count($errors->all()) ? '' : 'display: none;' }}">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-4">
                                <label>{{ trans('backend.nickname') }}</label>
                                <p class="text-muted small">{{ trans('messages.public_registration') }}</p>
                            </div>
                            <div class="col">
                                <input type="text" name="nickname" class="form-control" value="{{ Input::old('nickname', $info->nickname) }}">   
                                {!! $errors->first('nickname','<p class="text-danger my-2">:message</p>') !!}           
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <div class="col-md-4">
                                <label>{{ trans('backend.gender') }}</label>            
                            </div>
                            <div class="col">
                                {{ Form::select('gender', ['Select Gender'] + genders(), Input::old('gender', @$info->gender), ['class' => 'form-control']) }}
                                {!! $errors->first('gender','<p class="text-danger my-2">:message</p>') !!}             
                            </div>
                        </div>  
                        <div class="row align-items-center mb-3">
                            <div class="col-md-4">
                                <label>{{ trans('backend.name') }}</label>     
                                <p class="text-muted small">{{ trans('backend.non_public') }}</p>
                            </div>
                            <div class="col">
                                <input type="text" name="name" class="form-control" value="{{ Input::old('name', $info->name) }}">   
                                {!! $errors->first('name','<p class="text-danger my-2">:message</p>') !!}           
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <div class="col-md-4">
                                <label>{{ trans('backend.email') }}</label>      
                            </div>
                            <div class="col">
                                <input type="text" name="email" class="form-control no-space" value="{{ Input::old('email', $info->post_title) }}"> 
                                {!! $errors->first('email','<p class="text-danger my-2">:message</p>') !!}          
                            </div>
                        </div>
                        @if( @$info->post_type == 'ticket' )
                        <div class="row align-items-center mb-3">
                            <div class="col-md-4">
                                <label>{{ trans('backend.choice_od_card') }}</label>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                <?php 
                                $total_amount = 0;
                                $attendee = json_decode(@$info->attendee); ?>

                                @foreach($attendee as $am_k => $am_v)
                                <div class="col-md-6 form-group">
                                    <?php $total_amount = $am_v->amount * Input::old('attendee.'.$am_k.'.count', @$am_v->count); ?>
                                    <label>{{ trans('backend.'.$am_k) }}</label> <span class="float-right">( {{ currency_symbol($info->currency) }} <span class="calc-label">{{ number_format($total_amount, 2) }}</span>  )</span>
                                    {{ Form::select('attendee['.$am_k.'][count]', range(0, 10), Input::old('attendee.'.$am_k.'.count', @$am_v->count), ['class' => 'calc form-control', "data-id" => $am_k]) }}       
                                    <input type="hidden" name="attendee[{{ $am_k }}][amount]" value="{{ @$am_v->amount }}">         
                                </div>
                                @endforeach             
                                </div>
                            </div>

                        </div>
                        <div class="row align-items-center mb-4">
                            <div class="col-md-4">
                                <label><b>{{ trans('backend.total') }}</b></label>     
                            </div>
                            <div class="col">
                                <input type="hidden" name="total" value="{{ $total = Input::old('total', @$info->total) }}">
                                <h5 class="font-weight-bold">{{ currency_symbol($info->currency) }} <span class="calc-total">{{ number_format($total, 2) }}</span></h5>
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
                                <?php 
                                $total_amount = 0;
                                $attendee = json_decode(@$info->attendee); ?>

                                @foreach($attendee as $am_k => $am_v)
                                <div class="col-md-6 form-group">
                                    <label>{{ trans('backend.'.$am_k) }}</label></span>
                                    {{ Form::select('attendee['.$am_k.'][count]', range(0, 10), Input::old('attendee.'.$am_k.'.count', @$am_v->count), ['class' => 'calc form-control', "data-id" => $am_k]) }}              
                                </div>
                                @endforeach             
                                </div>
                            </div>

                        </div>
                        @endif
                        <div class="row align-items-center mb-4">
                            <div class="col-md-4">
                                <label>{{ trans('backend.comment') }}</label>      
                            </div>
                            <div class="col">
                                <textarea name="comment" class="form-control" rows="4">{{ Input::old('comment', $info->post_content) }}</textarea>
                                {!! $errors->first('comment','<p class="text-danger my-2">:message</p>') !!}            
                            </div>
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
                        <strong>{{ date_formatted($info->created_at) }}</strong> @ 
                        <strong>{{ time_formatted($info->created_at) }}</strong>                          
                    </div>

                    <label>{{ trans('backend.event_date') }}</label>
                    <div class="input-group">
                        <input type="text" name="date_start" class="form-control w-50 date-format datepicker" placeholder="mm-dd-yyyy" 
                        value="{{ Input::old('date_start', date_formatted_b($info->date_start)) }}">  
                        <input type="text" name="time_start" class="form-control w-25 time-format timepicker" placeholder="00:00" 
                        value="{{ Input::old('time_start', $info->time_start) }}">  
                    </div>

                </div>
            </div>


            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.notes') }}</div>
                <div class="card-body">

                <div class="order-notes mb-4">     
                    @foreach($notes as $note)   
                        @include('backend.partials.note')
                    @endforeach
                </div>

                    <div class="form-group">
                        <textarea id="note" class="form-control" rows="5" placeholder="Enter your comment here."></textarea>   
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-note float-right" data-url="{{ route('backend.general.note', $info->id) }}">
                    <i class="fa fa-plus"></i> {{ trans('backend.add_note') }}
                    </button> 
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.status') }}
                    <div class="float-right">
                        {{ status_ico(@$info->post_status) }}                    
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', booking_status([], $info->post_type), Input::old('status', @$info->post_status), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if( has_access($module, ['add_edit']) )
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ trans('backend.save_changes') }}</button>                     
    </div>
    @endif
</form>

<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')
@include('backend.partials.preview-modal')

@endsection

@section('style')
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
