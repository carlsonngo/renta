@extends('layouts.frontend-container')

@section('header')
<div class="bg-white">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col">
        <h1 class="font-weight-bold text-uppercase m-0 h5">Thank you for your order</h1>
                
            </div>
            <div class="col text-right">
                <strong>Order # {{ $info->id }}</strong><br>
                <a href="{{ route('shop.index') }}">Back to shopping</a>                
            </div>
        </div>
    </div>
</div>     
@stop

@section('content')
<div class="bg-white rounded p-3 my-3">
    <p class="alert alert-info">Your order has been recieved and is now being processed. Your order details are shown below for your reference:</p>

    <div class="table-responsive">
    <table class="table table-striped border table-hover">
        <thead>
            <tr>
                <th width="70"></th>
                <th width="40%">Product</th>
                <th class="text-right">QTY</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach( $cart->orders as $order )
             <?php $varation_data = json_decode(@$order->variation_data, true) ?? []; ?>
            <tr>
                <td>
                    <img src="{{ has_image(str_replace('-large', '-medium', $order->image)) }}" width="80" class="rounded mb-2">   
                </td>
                <td>
                    <p class="mb-2">{{ $order->name }}</p>

                    @if( @$order->sku )
                    <h6>{{ @$order->sku }}</h6>
                    @endif

                    <div class="row">   
                        <div class="col-md-6">
                        @if( @$order->variation_data )
                            @foreach( $varation_data as $vd_k => $vd_v )
                            <div><span class="text-muted">{{  code_to_text($vd_k) }}</span> : {{ $vd_v }}</div>
                            @endforeach
                        @endif    
                        </div>
                        <div class="col-md-6">
                            <div class="text-primary"><span>Delivery Date :</span> {{ date('M d', strtotime(@$order->delivery_date)) }}</div>
                            <div><span class="text-muted">Event Date :</span> 
                            <?php 
                                $s = date('Y-m-d H:i:s', strtotime('+1 day', strtotime(@$order->delivery_date))); 
                                $e = date('Y-m-d H:i:s', strtotime('-1 day', strtotime(@$order->return_date))); 
                            ?>
                            {{ implode(', ', dates_from_range($s, $e, 'M d')) }}
                            </div>
                            <div class="text-danger"><span>Return Date :</span> {{ date('M d', strtotime(@$order->return_date)) }}</div>
                        </div>
                    </div>


                </td>
                <td class="text-right">{{ $order->quantity }}</td>
                <td class="text-right">{{ amount_formatted($order->item_price) }}</td>
                <td class="text-right">{{ amount_formatted($order->total_price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    
    <hr>
    <div class="row">
        <div class="col-md-3">
            <h6>Delivery Address :</h6>
            <hr>
            <p><span class="text-muted">Name:</span><br>
            {{ ucwords($info->firstname.' '.$info->lastname) }}
            </p>

            <p><span class="text-muted">Address:</span><br>
            {{ ucwords($info->delivery_floor.' '.$info->delivery_unit_no.', '.$info->delivery_street_address_1.', '.$info->delivery_barangay.', '.$info->delivery_city.', '.$info->delivery_region) }} 
            {{ $info->delivery_zipcode }}
            {{ countries($info->delivery_country) }}
            </p>    

            <p><span class="text-muted">Email address:</span><br>
                <a href="">{{ $info->email_address }}</a>
            </p>
            <p><span class="text-muted">Phone:</span><br>
                {{ $info->phone }}
            </p>
        </div>

        <div class="col-md-6">

            <div class="row">
                <div class="col-md-6">
                    <p><span class="text-muted">Payment Method :</span><br> <b class="text-info">{{ payment_methods($info->payment_method)['name'] }}</b></p>
                    <p><span class="text-muted">Date Ordered :</span><br> <b class="text-info">{{ date_formatted($info->created_at) }}</b></p>
                </div>
                <div class="col-md-6">
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td class="text-right"><strong>Sub Total :</strong> 
                                </td>
                                <td class="text-right"><strong>{{ amount_formatted($cart->subtotal) }}</strong></td>
                            </tr>
                            @if( $cart->delivery_fee )
                            <tr>
                                <td class="text-right">Shipping : 
                                </td>
                                <td class="text-right">{{ amount_formatted($cart->delivery_fee) }}</td>
                            </tr>
                            @endif
                            @if( $cart->discount_fee )
                            <tr class="text-danger">
                                <td class="text-right">Discount : 
                                </td>
                                <td class="text-right">- {{ amount_formatted($cart->discount_fee) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-right"><strong>Total :</strong> </td>
                                <td class="text-right h5 pt-2"><strong>{{ amount_formatted($cart->total) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if( $info->notes )
            <label class="text-muted">Order Notes</label>
            <p>{{ $info->notes }}</p>
            @endif
            
        </div>
    </div>
</div>

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
