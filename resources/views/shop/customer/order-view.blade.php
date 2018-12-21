@extends('layouts.frontend-fullwidth')

@section('header')
<div class="bg-white">
    <div class="container py-3">
        <h1 class="font-weight-bold text-uppercase m-0 h4">Orders</h1>
    </div>
</div>     
@stop

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        @include('shop.customer.menu')     
    </div>
    <div class="col-md-9">

        <div class="bg-white rounded p-3 mb-3">

            <div class="row align-items-center mb-2">
                <div class="col">
                    <div class="p-3">Order <b class="bg-dark text-white px-2 rounded">#{{ $info->id }}</b> was placed on <b class="bg-dark text-white px-2 rounded">{{ date_formatted($info->created_at) }}</b> and is currently <b class="bg-dark text-white px-2 rounded">{{ $info->post_status }}</b></div>                    
                </div>
                <div class="col-auto">
                    <a href="{{ route('shop.customer.orders') }}" class="btn btn-outline-primary btn-sm">All Orders</a>
                </div>
            </div>

            <div class="table-responsive">
            <table class="table table-striped border table-hover">
                <thead>
                    <tr>
                        <th width="70"></th>
                        <th width="250">Product</th>
                        <th class="text-right">QTY</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $cart->orders as $order )

                    <tr>
                        <td>
                            <img src="{{ has_image(str_replace('-large', '-medium', $order->image)) }}" width="80" class="rounded mb-2">   
                        </td>
                        <td>
                            <p class="mb-2">{{ $order->name }}</p>
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
                    <h6>Billing Address :</h6>
                    <hr>
                    <p><span class="text-muted">Name:</span><br>
                    {{ ucwords($info->billing_firstname.' '.$info->billing_lastname) }}
                    </p>

                    <p><span class="text-muted">Address:</span><br>
                    {{ ucwords($info->billing_address_1.' '.$info->billing_city.', '.$info->billing_state) }} 
                    {{ $info->billing_zipcode }}
                    {{ countries($info->billing_country) }}
                    </p>    

                    <p><span class="text-muted">Email address:</span><br>
                        <a href="">{{ $info->billing_email_address }}</a>
                    </p>
                    <p><span class="text-muted">Phone:</span><br>
                        {{ $info->billing_phone }}
                    </p>
                </div>
                <div class="col-md-3">
                    <h6>Shipping Address :</h6>
                    <hr>
                    <p><span class="text-muted">Name:</span><br>
                    {{ ucwords($info->shipping_firstname.' '.$info->shipping_lastname) }}
                    </p>

                    <p><span class="text-muted">Address:</span><br>
                    {{ ucwords($info->shipping_address_1.' '.$info->shipping_city.', '.$info->shipping_state) }} 
                    {{ $info->shipping_zipcode }}
                    {{ countries($info->shipping_country) }}
                    </p>    

                    <p><span class="text-muted">Email address:</span><br>
                        <a href="">{{ $info->shipping_email_address }}</a>
                    </p>
                    <p><span class="text-muted">Phone:</span><br>
                        {{ $info->shipping_phone }}
                    </p>
                </div>
                <div class="col-md-6">

                    <table class="table border">
                        <tbody>
                            <tr class="text-info">
                                <td class="text-right"><strong>Sub Total :</strong> 
                                </td>
                                <td class="text-right" width="150"><strong>{{ amount_formatted($cart->subtotal) }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-right">Shipping : 
                                </td>
                                <td class="text-right">{{ amount_formatted($cart->shipping_fee) }}</td>
                            </tr>
                            <tr class="text-info">
                                <td class="text-right"><strong>Total :</strong> </td>
                                <td class="text-right"><strong>{{ amount_formatted($cart->total) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <p><span class="text-muted">Payment Method :</span><br> <b class="text-info">{{ @$info->payment_method }}</b></p>
                    <p><span class="text-muted">Date Ordered :</span><br> <b class="text-info">{{ date_formatted($info->created_at) }}</b></p>

        
                </div>
            </div>
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
