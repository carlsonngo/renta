@extends('layouts.frontend-fullwidth')

@section('header')
<div class="bg-white">
    <div class="container py-3">
        <h1 class="font-weight-bold text-uppercase m-0 h4">Addresses</h1>
    </div>
</div>     
@stop

@section('content')

@include('notification')

<div class="row mb-4">
    <div class="col-md-3">
        @include('shop.customer.menu')     
    </div>
    <div class="col-md-9">
        <div class="bg-white rounded py-4 px-4 mb-4">
            <form method="POST">
            {{ csrf_field() }}      
                <div class="row">
                    <div class="col-lg-6">
      
                        <h5 class="mb-4">Billing Details</h5>
                        <hr>
                        <div class="billing-form">

                            <div class="row">
                                <div class="col form-group">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_firstname" class="form-control form-control-sm" value="{{ Input::old('billing_firstname', @$info->billing_firstname) }}">
                                    {!! $errors->first('billing_firstname','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_lastname" class="form-control form-control-sm" value="{{ Input::old('billing_lastname', @$info->billing_lastname) }}">
                                    {!! $errors->first('billing_lastname','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_email_address" class="form-control form-control-sm" value="{{ Input::old('billing_email_address', @$info->billing_email_address) }}">
                                    {!! $errors->first('billing_email_address','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_phone" class="form-control form-control-sm" value="{{ Input::old('billing_phone', @$info->billing_phone) }}">
                                    {!! $errors->first('billing_phone','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address 1 <span class="text-danger">*</span></label>
                                <input type="text" name="billing_address_1" class="form-control form-control-sm" placeholder="Street Address or P.O Box" value="{{ Input::old('billing_address_1', @$info->billing_address_1) }}">
                                {!! $errors->first('billing_address_1','<p class="text-danger my-2">:message</p>') !!}
                            </div>
                            <div class="form-group">
                                <label>Address 2 <span class="text-muted small">(Optional)</span></label>
                                <input type="text" name="billing_address_2" class="form-control form-control-sm" placeholder="Company, C/O, Apt, Suite, Unit, Building, Floor" value="{{ Input::old('billing_address_2', @$info->billing_address_2) }}">
                                {!! $errors->first('billing_address_2','<p class="text-danger my-2">:message</p>') !!}
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>City <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_city" class="form-control form-control-sm" value="{{ Input::old('billing_city', @$info->billing_city) }}">
                                    {!! $errors->first('billing_city','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>State / Province <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_state" class="form-control form-control-sm" value="{{ Input::old('billing_state', @$info->billing_state) }}">
                                    {!! $errors->first('billing_state','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>Zip / Postal Code <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_zipcode" class="form-control form-control-sm" value="{{ Input::old('billing_zipcode', @$info->billing_zipcode) }}">
                                    {!! $errors->first('billing_zipcode','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>Country <span class="text-danger">*</span></label>
                                    {{ Form::select('billing_country', ['' => 'Select Country'] + countries(), Input::old('billing_country', @$info->billing_country), ['class' => 'form-control form-control-sm select2'] ) }}
                                    {!! $errors->first('billing_country','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            
                        </div>

                    </div>
                    <div class="col-lg-6">
                        
                        <h5 class="mb-4">Shipping Details</h5>                
                        <hr>
                        <div class="shipping-details">
           
                                       <div class="row">
                                <div class="col form-group">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_firstname" class="form-control form-control-sm" value="{{ Input::old('shipping_firstname', @$info->shipping_firstname) }}">
                                    {!! $errors->first('shipping_firstname','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_lastname" class="form-control form-control-sm" value="{{ Input::old('shipping_lastname', @$info->shipping_lastname) }}">
                                    {!! $errors->first('shipping_lastname','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_email_address" class="form-control form-control-sm" value="{{ Input::old('shipping_email_address', @$info->shipping_email_address) }}">
                                    {!! $errors->first('shipping_email_address','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_phone" class="form-control form-control-sm" value="{{ Input::old('shipping_phone', @$info->shipping_phone) }}">
                                    {!! $errors->first('shipping_phone','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address 1 <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_address_1" class="form-control form-control-sm" placeholder="Street Address or P.O Box" value="{{ Input::old('shipping_address_1', @$info->shipping_address_1) }}">
                                {!! $errors->first('shipping_address_1','<p class="text-danger my-2">:message</p>') !!}
                            </div>
                            <div class="form-group">
                                <label>Address 2 <span class="text-muted small">(Optional)</span></label>
                                <input type="text" name="shipping_address_2" class="form-control form-control-sm" placeholder="Company, C/O, Apt, Suite, Unit, Building, Floor" value="{{ Input::old('shipping_address_2', @$info->shipping_address_2) }}">
                                {!! $errors->first('shipping_address_2','<p class="text-danger my-2">:message</p>') !!}
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>City <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_city" class="form-control form-control-sm" value="{{ Input::old('shipping_city', @$info->shipping_city) }}">
                                    {!! $errors->first('shipping_city','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>State / Province <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_state" class="form-control form-control-sm" value="{{ Input::old('shipping_state', @$info->shipping_state) }}">
                                    {!! $errors->first('shipping_state','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>Zip / Postal Code <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_zipcode" class="form-control form-control-sm" value="{{ Input::old('shipping_zipcode', @$info->shipping_zipcode) }}">
                                    {!! $errors->first('shipping_zipcode','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>Country <span class="text-danger">*</span></label>
                                    {{ Form::select('shipping_country', ['' => 'Select Country'] + countries(), Input::old('shipping_country', @$info->shipping_country), ['class' => 'form-control form-control-sm select2'] ) }}
                                    {!! $errors->first('shipping_country','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>

                        </div>
                    </div>

                    </div>

                    <hr>

                    <button class="btn btn-outline-primary">{{ trans('backend.save_changes') }}</button>
                </div>

            </form>
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
