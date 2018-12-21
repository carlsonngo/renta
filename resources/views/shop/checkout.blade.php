@extends('layouts.frontend-container')


@section('content')
<?php extract(session('checkout') ?? []); ?>

@if( @$cart['orders'] )
<form method="POST" action="{{ route('shop.checkout') }}" autocomplete="off" class="checkout-form">
    {{ csrf_field() }}

<div class="checkout my-5">
    <h2 class="text-center">Checkout</h2>

    @include('notification')
    <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <a class="btn" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Delivery Address
                    </a>
                </h5>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">

                    <div class="row p-0">
                        <div class="form-group col-md-6">
                            <input type="text" name="firstname" class="form-control form-control-lg" placeholder="First Name" value="{{ Input::old('firstname', @$firstname) }}">
                            {!! $errors->first('firstname','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="lastname" class="form-control form-control-lg"" placeholder="Last Name" value="{{ Input::old('lastname', @$lastname) }}">
                            {!! $errors->first('lastname','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                    </div>

                    <div class="row p-0">
                        <div class="form-group col-md-6">
                            <input type="text" name="email_address" class="form-control form-control-lg" placeholder="Email Address" value="{{ Input::old('email_address', @$email_address) }}">
                            {!! $errors->first('firstname','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="phone" class="form-control form-control-lg"" placeholder="Phone" value="{{ Input::old('phone', @$phone) }}">
                            {!! $errors->first('phone','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                    </div>

                    <div class="row p-0">
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_floor" class="form-control form-control-lg" placeholder="Floor" value="{{ Input::old('delivery_floor', @$delivery_floor) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_unit_no" class="form-control form-control-lg"" placeholder="Unit No. *" value="{{ Input::old('delivery_unit_no', @$delivery_unit_no) }}">
                            {!! $errors->first('delivery_unit_no','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row p-0">
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_building_name" class="form-control form-control-lg"" placeholder="Building Name" value="{{ Input::old('delivery_building_name', @$delivery_building_name) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_street_address_1" class="form-control form-control-lg"" placeholder="Street Address 1 *" value="{{ Input::old('delivery_street_address_1', @$delivery_street_address_1) }}">
                                {!! $errors->first('delivery_street_address_1','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row p-0">
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_street_address_2" class="form-control form-control-lg"" placeholder="Street Address 2" value="{{ Input::old('delivery_street_address_2', @$delivery_street_address_2) }}">
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::select('delivery_country', countries(), Input::old('delivery_country', @$delivery_country), ['class' => 'form-control form-control-lg']) }}
                             {!! $errors->first('delivery_country','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row p-0">
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_region" class="form-control form-control-lg"" placeholder="Region *" value="{{ Input::old('delivery_region', @$delivery_region) }}">
                            {!! $errors->first('delivery_region','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_city" class="form-control form-control-lg"" placeholder="City *" value="{{ Input::old('delivery_city', @$delivery_city) }}">
                              {!! $errors->first('delivery_city','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row p-0">
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_barangay" class="form-control form-control-lg"" placeholder="Barangay *" value="{{ Input::old('delivery_barangay', @$delivery_barangay) }}">
                             {!! $errors->first('delivery_barangay','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="delivery_zipcode" class="form-control form-control-lg"" placeholder="Zip Code *" value="{{ Input::old('delivery_zipcode', @$delivery_zipcode) }}">
                            {!! $errors->first('delivery_zipcode','<p class="text-danger my-2">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <a class="btn" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Mode of Payment
                        </a>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse"  aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <div class="rounded">
                            <div class="bg-white p-2">
                                @if( $payment_methods = json_decode($setting->get_setting('payment_methods')) )
                                @foreach($payment_methods as $payment_method)
                                <?php $default = Input::get('payment_method', $payment_method==$setting->get_setting('payment_method')); ?>
                                <div class="custom-control custom-radio custom-control my-2">
                                    {{ Form::radio('payment_method', $payment_method, $default, ['class' => 'custom-control-input', 'id' => $payment_method]) }} 
                                    <label class="custom-control-label" for="{{ $payment_method }}">{{ payment_methods($payment_method)['name'] }}</label>
                                </div>
                                <div style="display:none;" class="pm-desc text-muted ml-4 desc-{{ $payment_method }}">{{ payment_methods($payment_method)['description'] }}</div>
                                @endforeach
                                @else
                                <div class="p-3 bg-warning rounded">Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.</div>
                                @endif
                            </div>

                            <input type="hidden" name="card_type" value="{{ $card_type = Input::old('card_type') }}">
                            <div class="bg-white p-2" id="creditcard-form" style="display: none;">
                                <div class="form-group">
                                    <label class="text-uppercase">
                                    <b>Card number</b></label>
                                    <div class="cc-group">
                                        <div class="card-logo" data-toggle="tooltip" data-type="{{ $card_type }}"></div>
                                        <input type="text" name="card_number" class="form-control card-number numeric" placeholder="•••• •••• •••• ••••" maxlength="40" value="{{ Input::old('card_number') }}">                              
                                    </div>
                                    {!! $errors->first('card_number','<p class="text-danger my-2">:message</p>') !!}             
                                </div>
                                <div class="row pt-0">
                                    <div class="col-6">
                                        <label class="text-uppercase"><b>Expiry (MM / YY)</b></label>
                                        <input type="text" name="card_expiry" class="form-control form-control-lg expiry" placeholder="MM / YY" value="{{ Input::old('card_expiry') }}">
                                        {!! $errors->first('card_expiry','<p class="cc-expiry-error text-danger my-2">:message</p>') !!}                    
                                    </div>
                                    <div class="col-6">
                                        <label class="text-uppercase"><b>CVV</b></label>
                                        <input type="text" name="card_cvv" class="form-control form-control-lg cvv" placeholder="CVV" value="{{ Input::old('card_cvv') }}">
                                        {!! $errors->first('card_cvv','<p class="text-danger my-2">:message</p>') !!}                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <a class="btn" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Review and Place Rental Order
                        </a>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">
                        <div class="container summary-order">
                            @foreach($cart['orders'] as $order)
                            <?php $varation_data = json_decode(@$order['variation_data'], true) ?? []; ?>
                            <div class="order-card cart-item cart-{{ @$order['id'] }}">
                                <div class="order-header clearfix">
                                    <div class="date">
                                    <p>{{ date('F d', strtotime(@$order['delivery_date'])) }} - {{ date('d Y', strtotime(@$order['return_date'])) }}</p>
                                    </div>
                                    <div class="deliver">
                                        <p>DELIVERY TIME : {{ date('M d', strtotime(@$order['delivery_date'])) }},  {{ delivery_fee(@$order['delivery_time'])['desc'] }}</p>
                                    </div>
                                </div>
                                <div class="order-body">
                                    <div class="text-right">
                                        <a href="{{ route('shop.remove-item', $order['id']) }}" class="remove-to-cart" data-id="{{ @$order['id'] }}">x</a>
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
                                            </div>

                                        </div>
                                        <div class="col-lg-3 text-right">
                                            <h3>{{ amount_formatted(@$order['total_price']) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">

            @include('shop.inc.coupon-form')

            <div class="cart-totals bg-white rounded mt-3">
                @include('shop.inc.total')
            </div>


            <div class="mt-3">
                <button type="submit" class="text-uppercase btn btn-block btn-lg btn-info py-3 px-5 mb-4">{{ trans('backend.place_order') }}</button>     
            </div>
                
            </div>
        </div>


    </div>
</div>

</form>
@else
<div class="alert alert-info mb-5 px-4 pt-4 pb-3">
	<a href="{{ route('shop.index') }}" class="float-right text-uppercase"><b>{{ trans('backend.start_shopping') }}</b></a>
	<h6>{{ trans('messages.cart_is_empty') }}</h6>
</div>
@endif
@endsection

@section('style')
<style>
.summary-order td, .summary-order th {
    padding: .5rem;    
}
.summary-order {
    font-size: .7rem;
}    
</style>
@stop

@section('plugin_script')
<link rel="stylesheet" href="{{ asset('plugins/cc-checker/style.css') }}?v={{ filemtime(public_path('plugins/cc-checker/style.css')) }}">
<script type="text/javascript" src="{{ asset('plugins/cc-checker/script.js') }}?v={{ filemtime(public_path('plugins/cc-checker/script.js')) }}"></script>
@stop

@section('script')
<script>
$("form").submit(function() { $('.o-loader').show() });
$(".expiry").inputmask({mask: "99 / 99"});
$(".cvv").inputmask({mask: "999"});     
$(document).on('click', '[name="same_as_billing"]', function() {
    $('.shipping-details, .shipping-alert').toggle('fast');
});  
$(document).on('click', '[name="create_account"]', function() {
    $('.create-account').toggle('fast');
});  

$(document).on('click', '[name="payment_method"]', function() {
    via_cc();
});  

function via_cc() {
    var val = $('[name="payment_method"]:checked').val();
    $('#creditcard-form, .pm-desc').hide();
    if($('[name="payment_method"]:checked').val() == 'paymaya') {
        $('#creditcard-form').show();
    }    
    $('.desc-'+val).show();
}
via_cc();

$('.card-number').creditCardTypeDetector({ 'credit_card_logos' : '.card-logo' });
</script>
@stop