@extends('layouts.frontend-container')

@section('header')
<div class="promo-notification text-center">
    <h6>PROMO ALERT - KEYWORD (GUCCI30) : Get a 30% discount when you rent anything from GUCCI.</h6>
</div>
@stop

@section('content')

<div class="my-5">
@include('notification')

@if( @$cart['orders'] )
<form method="POST" action="">
    {{ csrf_field() }}

    <div class="cart">
        <div class="page-title">
            <h2>Rental Cart</h2>
            <h6>Reminder: Regular delivery time is between 11am to 8pm Mon-Sat</h6>
        </div>
        <div class="container">
            <div class="cart-wrapper">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card-body summary-order">

                            @foreach($cart['orders'] as $order)
                            <?php $varation_data = json_decode(@$order['variation_data'], true) ?? []; ?>
                            <div class="order-card cart-item cart-{{ @$order['id'] }}">
                                <div class="order-header">
                                    <div class="row align-items-center">
                                        <div class="col-lg">
                                            <div class="date">
                                                <p>{{ date('F d', strtotime(@$order['delivery_date'])) }} - {{ date('d Y', strtotime(@$order['return_date'])) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-lg">
                                            <div class="deliver">
                                                <p>SPECIAL DELIVERY (P150)</p>
                                            </div>
                                        </div>
                                        <div class="col-lg">
                                            <div class="delivery-time">
                                                <select class="form-control delivery_time" data-id="{{ @$order['id'] }}">
                                                    <option value="">Please specify a time</option>
                                                    @foreach(delivery_fee() as $df_k => $df_v)
                                                    <option value="{{ $df_k }}" {{  selected($df_k, @$order['delivery_time']) }}>{{ $df_v['desc'] }}</option>
      												@endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-body">
                                    <div class="text-right">
                                        <a href="{{ route('shop.remove-item', @$order['id']) }}" class="remove-to-cart" data-id="{{ @$order['id'] }}">x</a>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <img src="{{ has_image(str_replace('-large', '-medium', @$order['image'])) }}" class="img-fluid mb-2"> 
                                        </div>
                                        <div class="col-lg-7">
                                            <h3>{{ ucfirst(@$order['name']) }}</h3>

                                            @if( @$order['sku'] )
                                            <h6>sku: {{ @$order['sku'] }}</h6>
                                            @endif

                                            <div class="row">   
	                                            <div class="col-md-6">
	                                            <p>Quantity : {{ @$order['quantity'] }}</p>
	                                            @if( @$order['variation_data'] )
	                                                @foreach( $varation_data as $vd_k => $vd_v )
	                                                <p><span class="text-muted">{{  code_to_text($vd_k) }}</span> : {{ $vd_v }}</p>
	                                                @endforeach
	                                            @endif    
	                                            </div>
	                                            <div class="col-md-6">
		                                            <p class="text-primary"><span>Delivery Date :</span> {{ date('M d', strtotime(@$order['delivery_date'])) }}</p>
		                                            <p><span class="text-muted">Event Date :</span> 
													<?php 
														$s = date('Y-m-d H:i:s', strtotime('+1 day', strtotime(@$order['delivery_date']))); 
														$e = date('Y-m-d H:i:s', strtotime('-1 day', strtotime(@$order['return_date']))); 
													?>
													{{ implode(', ', dates_from_range($s, $e, 'M d')) }}
		                                            </p>
		                                            <p class="text-danger"><span>Return Date :</span> {{ date('M d', strtotime(@$order['return_date'])) }}</p>
	                                            </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-3 text-right">
                                            <h3 class="total-price">{{ amount_formatted(@$order['total_price']) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <div class="card-body">
                                <div class="order-card">

                                    <div class="order-header">
                                        <div class="row">
                                            <div class="col-lg">
                                                <h5>Rental Cart Summary</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="cart-totals rounded bg-white">
                                    @include('shop.inc.total')
                                    </div>

                                    <div class="order-body">
				                        @include('shop.inc.coupon-form')

                                        <a class="btn-checkout py-3 mx-0" href="{{ route('shop.checkout') }}">CHECKOUT</a>
                                        <a class="btn-shop" href="{{ route('shop.index') }}">CONTINUE BROWSING</a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</form>
@else
<div class="alert alert-info mb-5 px-4 pt-4 pb-3">
    <a href="{{ route('shop.index') }}" class="float-right text-uppercase"><b>Start Shopping!</b></a>
    <h6>There is no item in your cart!</h6>
</div>
@endif
</div>

@endsection

@section('style')
<style>
.checkbox, .form-control {
    font-size: .95em;
}    
</style>
@stop

@section('plugin_script')
@stop

@section('script')
<script>
$("form").submit(function() { $('.o-loader').show() });
$(document).on('click', '[name="same_as_billing"]', function() {
    $('.shipping-details, .shipping-alert').toggle('fast');
});  
$(document).on('click', '[name="create_account"]', function() {
    $('.create-account').toggle('fast');
});  

$(document).on('change', '.delivery_time', function(){
	var $this = $(this), 
		time = $(this).val(),
		id = $(this).attr('data-id');
	if( time ) {
		data = { 'time':time, 'id':id }
		$('.o-loader').show();
		$.get('{{ route('shop.cart.update-delivery') }}', data, function(res){
			get_cart_totals();		
		});
	}
});
</script>
@stop
