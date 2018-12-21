<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>{{ trans('backend.product') }}</th>
            <th align="center">{{ trans('backend.quantity') }}</th>
            <th align="right">{{ trans('backend.price') }}</th>
            <th align="right">{{ trans('backend.total') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cart['orders'] as $order)
        <?php $varation_data = json_decode(@$order['variation_data'], true) ?? []; ?>
        <tr>
            <td class="py-2 px-3">
                <a href="{{ route('shop.single', @$order['slug']) }}?{{ http_build_query($varation_data) }}">{{ ucfirst(@$order['name']) }}</a>
                
                @if( @$order['sku'] )
                <br><span class="text-muted">sku:</span> {{ @$order['sku'] }}
                @endif

                <div class="small mt-2">
                @if( @$order['variation_data'] )
                    @foreach( $varation_data as $vd_k => $vd_v )
                    <div><span class="text-muted">{{  $vd_k }}</span> : {{ $vd_v }}</div>
                    @endforeach
                @endif                                    
                </div>
                
                <div class="text-primary"><span>Delivery Date :</span> {{ date('M d', strtotime(@$order['delivery_date'])) }}</div>
                <div><span class="text-muted">Event Date :</span> 
                <?php 
                    $s = date('Y-m-d H:i:s', strtotime('+1 day', strtotime(@$order['delivery_date']))); 
                    $e = date('Y-m-d H:i:s', strtotime('-1 day', strtotime(@$order['return_date']))); 
                ?>
                {{ implode(', ', dates_from_range($s, $e, 'M d')) }}
                </div>
                <div class="text-danger"><span>Return Date :</span> {{ date('M d', strtotime(@$order['return_date'])) }}</div>

            </td>
            <td align="center">{{ @$order['quantity'] }}</td>
            <td align="right" width="90">{{ amount_formatted(@$order['item_price']) }}</td>
            <td align="right" width="105">{{ amount_formatted(@$order['total_price']) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <td><strong>{{ trans('backend.subtotal') }}</strong></td>
        <td align="right"><strong>{{ amount_formatted($cart['subtotal']) }}</strong></td>
    </tr>
    @if( @$cart['shipping_fee'] )
    <tr>
        <td>{{ trans('backend.shipping') }} : 
        </td>
        <td  align="right" width="100">{{ amount_formatted($cart['shipping_fee']) }}</td>
    </tr>
    @endif
    @if( @$cart['discount_fee'] )
    <tr class="text-danger">
        <td>{{ trans('backend.discount') }} : 
        </td>
        <td  align="right" width="100">- {{ amount_formatted($cart['discount_fee']) }}</td>
    </tr>
     @endif
    <tr>
        <td><strong>{{ trans('backend.total') }}</strong></td>
        <td align="right"><strong>{{ amount_formatted($cart['total']) }}</strong></td>
    </tr>
</table>
