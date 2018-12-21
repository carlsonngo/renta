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
        <div class="bg-white rounded py-4 px-4 mb-4">
            @if( count($rows) )
            <table class="table table-striped border table-hover">
                <thead>
                    <tr>
                        <th width="70">Order</th>
                        <th width="250">Date</th>
                        <th>Status</th>
                        <th class="text-right">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $rows as $order )
                    <?php $ordermeta = get_meta( $order->postMetas()->get() ); ?>
                    <tr>
                        <td><a href="{{ route('shop.customer.order-view', $order->id) }}">#{{ $order->id }}</a></td>
                        <td>{{ date_formatted($order->created_at) }}</td>
                        <td>{{ status_ico($order->post_status) }}</td>
                        <td class="text-right">
                            <b>{{ amount_formatted($ordermeta->total) }}</b> 
                            for {{ $qty = $ordermeta->quantity }} item{{ is_plural($qty) }}
                        </td>
                        <td class="text-center"><a href="{{ route('shop.customer.order-view', $order->id) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert bg-light">
                No order has been made yet. <a href="{{ route('shop.index') }}" class="float-right text-uppercase"><b>Go Shop</b></a>
            </div>
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
