
<div class="mb-3 coupon-form" style="{{ !@$cart['coupon_code'] && @App\Setting::get_setting('enable_coupon') ? '' : 'display:none;' }}">
    <label>{{ trans('messages.have_coupon') }}</label>
    <div class="input-group">
        <input type="text" name="coupon_code" class="form-control" value="{{ @$cart['coupon_code'] }}" placeholder="{{ trans('messages.enter_code_here') }}">
        <div class="input-group-append">
            <button type="submit" class="btn text-uppercase btn-apply-coupon" data-url="{{ route('backend.coupons.apply') }}" disabled>{{ trans('backend.apply_coupon') }}</button>  
        </div>
    </div>
    <div class="coupon-msg"></div>
</div>