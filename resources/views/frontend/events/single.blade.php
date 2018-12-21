@extends('layouts.frontend-with-sidebar')

@section('header')

<style>
.bg-banner {
	background: linear-gradient(rgba(52, 58, 64, 0.32), rgba(52, 58, 64, 0.19), rgb(248, 249, 250)), url({{ has_image($info->image) }});
	text-align: center;
}
</style>

<?php $title = trans_post($info, 'post_title', '_title'); ?>

@if( $info->image )
    <div class="bg-banner">
    	<h2 class="font-weight-bold">{{ trans('backend.event') }}</h2>
    </div>
    @if($title)
    <div class="container">
    	<div class="col-12 offset-top bg-white px-4 py-4">
    	    <h1 class="font-weight-bold text-uppercase m-0">{{ $title }}</h1>
            @if( $sub_title = trans_post($info, 'sub_title', '_sub_title') )
            <p class="text-muted mt-2 mb-0">
                {{ $sub_title }}
                @if( $time_start = @$info->time_start )
                at {{ $time_start }}
                @endif
            </p>
            @endif
    	</div>	
    </div>
    @endif
<style>
.container.mt-5	{ margin-top: 0!important; }
</style>
@else
    @if( $title )
    <div class="bg-white">
        <div class="container">
            <div class="mb-4 px-4 py-3">
                <h1 class="font-weight-bold text-uppercase h4 m-0">{{ $title }}</h1>
                @if( $sub_title = trans_post($info, 'sub_title', '_sub_title') )
                <p class="text-muted mt-2 mb-0">
                    {{ $sub_title }}
                    @if( $time_start = @$info->time_start )
                    at {{ $time_start }}
                    @endif
                </p>
                @endif
            </div>    
        </div>
    </div>     
    @endif
@endif

</div>

@stop

@section('content')


<section class="bg-white pt-4 pb-2 px-4 mb-4 rounded">

<div class="row mb-4">
    <div class="col-md-6 text-center">
        <img src="{{ has_image($info->image) }}" class="img-fluid rounded border">
    </div>    
    <div class="col-md-6">
        <label class="text-muted small text-uppercase"><i class="far fa-calendar mr-1"></i> {{ trans('backend.date_posted') }}</label> 
        <h6 class="mb-3">{{ time_ago($info->created_at) }}</h6>

        @if( is_past_date(@$info->date_start.' '.@$info->time_start) )
        <label class="text-muted small text-uppercase"><i class="far fa-calendar mr-1"></i> {{ trans('backend.event_schedule') }}</label>
            @if( $info->date_start )
	        <div class="h6">
	            <span class="text-muted">on</span> <span class="mr-2">{{ date_formatted(@$info->date_start) }}</span> 
	            @if( $info->time_start )
	            <span class="text-muted">from</span> {{ @$info->time_start }}
	            @endif
	        </div>
	        <div class="mb-3">
			    <small class="bg-danger text-white text-uppercase px-3 py-1">Past Event</small>
	        </div>                       
            @endif   
        @else
        <label class="text-muted small text-uppercase"><i class="far fa-calendar mr-1"></i> {{ trans('backend.event_schedule') }}</label>
        <div class="h6 mb-3">
            @if( $info->date_start )
	            <span class="text-muted">on</span> <span class="mr-2">{{ date_formatted(@$info->date_start) }}</span> 
	            @if( $info->time_start )
	            <span class="text-muted">from</span> {{ @$info->time_start }}
	            @endif
            @else
                <div class="text-uppercase small mt-2 text-danger">{{ trans('backend.not_set') }}</div>                    
            @endif   
        </div>
        @endif

        <label class="text-muted small text-uppercase"><i class="far fa-user-circle mr-1"></i> {{ trans('backend.organizer') }}</label>
        <h6 class="mb-4">{{ @$info->organizer }}</h6> 

        <label class="text-muted small text-uppercase"><i class="fas fa-map-marked-alt mr-1"></i> {{ trans('backend.location') }}</label> 
        <h6 class="mb-4">{{ @$info->location }}</h6>

    </div>    
</div>


<p class="text-muted small text-uppercase">{{ trans('backend.description') }}</p>
{!! trans_post($info, 'post_content', '_content') !!}

</section>


@if( App\Setting::get_setting('bookings_module') )
@include('notification')

<ul class="nav nav-tabs nav-justified bg-white tab-confirm tab-register">
  <li class="nav-item">
    <a class="nav-link py-3 {{ actived(Input::get('form', 'register'), 'register') }}" href="?form=register">{{ trans('backend.pre_register') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link py-3 {{ actived(Input::get('form'), 'confirm') }}" href="?form=confirm">{{ trans('backend.confirm') }}</a>
  </li>
</ul>


@if( Input::get('form') == 'confirm' )
<section class="bg-white pt-4 pb-2 px-4 mb-4 rounded">

@if (Session::get('registration_success'))
<div class="alert alert-success">
	<h6 class="text-success"><b>{{ trans('messages.application_saved') }}</b></h6>
	{{ trans('messages.confirm_registration') }}:
</div>
@endif

<form method="POST">
	{{ csrf_field() }}
	<label>{{ trans('backend.confirm_reservation') }} / {{ trans('backend.ticket_order') }}</label>
	<div class="row form-group align-items-center">
		<div class="col-auto">
			{{ trans('backend.verification_code') }}:
		</div>
		<div class="col">	
			<div class="input-group">
			<input type="text" name="verification_code" class="form-control" value="{{ Input::old('verification_code') }}">	
			<div class="input-group-prepend">
				<button class="btn btn-outline-primary py-2 px-4">{{ trans('backend.confirm') }}</button>								
			</div>
			</div>
			{!! $errors->first('verification_code','<p class="text-danger my-2">:message</p>') !!} 		
		</div>
	</div>
</form>
</section>
@else
<section class="bg-white pt-4 pb-2 px-4 mb-4 rounded">

@if( is_bookable( $info ) )
<form method="POST">
	{{ csrf_field() }}
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
		<div class="col">
			<?php $pairs_total = @$info->pairs_amount * Input::old('pairs'); ?>
			<label>{{ trans('backend.pairs') }}</label> <span class="float-right">( $ <span class="calc-label">{{ number_format($pairs_total, 2) }}</span>  )</span>
			{{ Form::select('pairs', range(0, 10), Input::old('pairs'), ['class' => 'calc form-control']) }}		
			<input type="hidden" name="pairs_amount" value="{{ @$info->pairs_amount }}">	
			{!! $errors->first('pairs','<p class="text-danger my-2">:message</p>') !!} 			
		</div>
		<div class="col">
			<?php $women_total = @$info->women_amount * Input::old('women'); ?>
			<label>{{ trans('backend.women') }}</label> <span class="float-right">( $ <span class="calc-label">{{ number_format($women_total, 2) }}</span>  )</span>
			{{ Form::select('women', range(0, 10), Input::old('women'), ['class' => 'calc form-control']) }}
			<input type="hidden" name="women_amount" value="{{ @$info->women_amount }}">
			{!! $errors->first('women','<p class="text-danger my-2">:message</p>') !!} 				
		</div>
	</div>	

	<div class="row align-items-center mb-4">
		<div class="col-md-4">
			<label><b>{{ trans('backend.total') }}</b></label>		
		</div>
		<div class="col">
			<input type="hidden" name="total" value="{{ $total = Input::old('total', 0) }}">
			<h5 class="font-weight-bold">$ <span class="calc-total">{{ number_format($total, 2) }}</span></h5>	
			{!! $errors->first('total','<p class="text-danger my-2">:message</p>') !!} 		
		</div>
	</div>
	@else
	<div class="row align-items-center mb-3">
		<div class="col-md-4">
			<label>{{ trans('backend.whos_coming') }}</label>		
		</div>
		<div class="col">
			<label>{{ trans('backend.pairs') }}</label>
			{{ Form::select('pairs', range(0, 10), Input::old('pairs'), ['class' => 'calc form-control']) }}	
			{!! $errors->first('pairs','<p class="text-danger my-2">:message</p>') !!} 				
		</div>
		<div class="col">
			<label>{{ trans('backend.women') }}</label>
			{{ Form::select('women', range(0, 10), Input::old('women'), ['class' => 'calc form-control']) }}
			{!! $errors->first('women','<p class="text-danger my-2">:message</p>') !!} 			
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

	<div class="row align-items-center mb-4">
		<div class="col-md-6 mb-md-0">
			<div class="p-1 bg-primary text-primary rounded">
				<div class="captcha rounded"></div>		
			</div>
		</div>
		<div class="col-md-6">
			<p>{{ trans('messages.type_security') }}:</p>
			<input type="text" name="captcha" class="form-control to-uppercase" maxlength="7" placeholder="Enter captcha here">
			{!! $errors->first('captcha','<p class="text-danger my-2">:message</p>') !!} 
		</div>
	</div>
	
	<hr>

	<div class="row mb-3">
		<div class="offset-md-8 col-md-4">
			<button class="btn btn-outline-primary btn-block btn-send">{{ trans('backend.send_registration') }}</button>	
		</div>
	</div>
</form>
@else

<div class="my-4 text-center">
	<h5 class="mb-4">{{ trans('messages.event_unavailable') }}</h5>
	<a href="{{ route('frontend.events.index') }}" class="btn btn-primary px-5">{{ trans('messages.see_upcoming_events') }}</a>	
</div>

@endif
</section>
@endif

@endif

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/captcha/style.css') }}">
<style type="text/css">
.nav .active {
	font-weight: bold;
}	
</style>
@stop

@section('plugin_script')
<script type="text/javascript" src="{{ asset('plugins/captcha/script.js') }}"></script>
@stop

@section('script')
<script type="text/javascript">
$(document).on('change', '.calc', function() {
	var total = gtotal = 0
	val       = $(this).val(), 
	name      = $(this).attr('name'), 
	amount    = $('[name='+name+'_amount').val();
	subtotal  = (val * amount).toFixed(2);
	$(this).closest('div').find('.calc-label').html(subtotal);
	$('.calc-label').each(function() {
	    total += Number($(this).html());
	});
	gtotal = Number(total).toFixed(2);
	$('[name="total"]').val(gtotal);
	$('.calc-total').html(gtotal);
});

@if(Input::get('form'))
$('html, body').animate({
    scrollTop: $(".tab-{{ Input::get('form') }}").offset().top
}, 0);
@endif
</script>
@stop
