@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.settings') }}</span>

<hr>
@stop

@section('content')

<form method="POST">
{{ csrf_field() }}

    <div class="row">
        <div class="col-lg-12">

            <div class="card mb-4">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.general_settings') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('backend.currency') }}</label>
                                {{ Form::select('currency', currencies(), @$info->currency, ['class' => 'form-control select2'] ) }}
                            </div>
                            <p>{{ amount_formatted(1234) }}</p>

                            <div class="form-group">
                                <input type="hidden" name="enable_coupon" value="0">
                                <div class="custom-control custom-checkbox align-items-center d-flex">
                                {{ Form::checkbox('enable_coupon', 1, @$info->enable_coupon, ['class' => 'custom-control-input', 'id' => 'enable_coupon']) }} 
                                <label class="custom-control-label" for="enable_coupon">Enable Coupon</label>
                                </div> 
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="payment_methods" value="0">
                                <label>{{ trans('backend.payment_method') }}</label>
                                
                                <div class="row">
                                @foreach(payment_methods() as $pm_k => $pm_v)
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox align-items-center d-flex">
                                        {{ Form::checkbox('payment_methods[]', $pm_k, @in_array($pm_k, json_decode($info->payment_methods)), ['class' => 'custom-control-input', 'id' => 'pm-'.$pm_k]) }} 
                                        <label class="custom-control-label" for="pm-{{ $pm_k }}">{!! $pm_v['name'] !!}</label>
                                        </div>                                    
                                    </div>
                                    <div class="col-6">
                                        @if(@in_array($pm_k, json_decode($info->payment_methods)))
                                        <div class="custom-control custom-radio align-items-center d-flex">
                                            {{ Form::radio('payment_method', $pm_k, @$info->payment_method==$pm_k , ['class' => 'custom-control-input', 'id' => $pm_k]) }} 
                                            <label class="custom-control-label" for="{{ $pm_k }}">{{ trans('backend.set_as_default') }}</label>
                                        </div>                                    
                                        @endif
                                    </div>
                                @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <?php $paypal = json_decode(@$info->paypal); ?>

                            <h5>Paypal </h5>
                            <p class="text-muted">{{ trans('messages.paypal_description') }}</p>
                            <div class="form-group custom-control custom-checkbox align-items-center d-flex">
                                <input type="hidden" name="paypal[status]" value="live">
                                {{ Form::checkbox('paypal[status]', 'sandbox', @$paypal->status=='sandbox'?1:0, ['class' => 'custom-control-input', 'id' => 'paypal_sandbox']) }}
                                <label class="custom-control-label" for="paypal_sandbox">Sanbox</label>
                            </div>

                            <div class="form-group">
                                <label>Sandbox Secret</label>
                                <input type="text" name="paypal[sandbox][secret]" class="form-control no-space" value="{{ @$paypal->sandbox->secret }}">               
                            </div>

                            <div class="form-group">
                                <label>Sandbox Client ID</label>
                                <input type="text" name="paypal[sandbox][client_id]" class="form-control no-space" value="{{ @$paypal->sandbox->client_id }}">               
                            </div>

                            <div class="form-group">
                                <label>Live Secret</label>
                                <input type="text" name="paypal[live][secret]" class="form-control no-space" value="{{ @$paypal->live->secret }}">               
                            </div>

                            <div class="form-group">
                                <label>Live Client ID</label>
                                <input type="text" name="paypal[live][client_id]" class="form-control no-space" value="{{ @$paypal->live->client_id }}">               
                            </div>                            
                        </div>

                    </div>
                </div>
            </div> 
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ trans('backend.save_changes') }}</button>                     
    </div>
</form>



<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
