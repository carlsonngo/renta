
<table class="table border">
    <tr>
        <td class="text-uppercase"><strong>{{ trans('backend.subtotal') }}</strong></td>
        <td class="text-right"><strong>{{ amount_formatted($cart['subtotal']) }}</strong></td>
    </tr>
    @if( @$cart['delivery_fee'] )
    <tr>
        <td class="text-uppercase"><strong>Delivery</strong></td>
        <td class="text-right">{{ amount_formatted($cart['delivery_fee']) }}</td>
    </tr>
    @endif

    @if( @$cart['discount_fee'] )
    <tr class="text-danger">
        <td class="align-middle text-uppercase">
            {{ trans('backend.discount') }}
        </td>
        <td class="text-right">
            - {{ amount_formatted($cart['discount_fee']) }}
        </td>
    </tr>
    <tr>
        <td class="text-uppercase">
            {{ trans('backend.coupon_code') }}
        </td>
        <td class="text-right">
            <div>{{ @$cart['coupon_code'] }}</div>
            <p class="text-muted mb-2">{{ @$cart['coupon_description'] }}</p>
            <a href="{{ route('backend.coupons.remove') }}" class="btn-remove-coupon text-primary">{{ trans('backend.remove_coupon') }}</a>
        </td>
    </tr>
    @endif
    <tr class="bg-light">
        <td class="align-middle text-uppercase"><strong>{{ trans('backend.total') }}</strong></td>
        <td class="text-right h4"><strong>{{ amount_formatted($cart['total']) }}</strong></td>
    </tr>
</table>