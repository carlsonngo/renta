@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="text-uppercase p-2 mr-3">/ {{ trans('backend.order') }} <b>#{{ $info->id }}</b></span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark">
        <i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_'.str_plural($post_type)) }}
    </a>
</div>

<hr>
@stop

@section('content')
<form method="POST">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-lg-8 col-md-7">


            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">
                    {{ trans('backend.details') }}
                    <a href="{{ route('shop.completed', $info->post_name) }}" class="text-white float-right" target="_blank">{{ trans('backend.view_reciept') }}</a>
                </div>
                <div class="card-body">
                

                <div class="row">
                    <div class="col-lg-6">

                        <h6 class="mb-4">{{ trans('backend.billing_details') }}</h6>

                        <div class="billing-details" style="{{ count($errors->all()) ? 'display: none;' : '' }}">

                            @if( has_access($module, ['edit']) ) 
                            <a href="#" class="edit-details float-right" data-target=".billing"><i class="far fa-edit"></i> {{ trans('backend.edit') }}</a>
                            @endif

                            <p><span class="text-muted">{{ trans('backend.name') }}:</span><br>
                            {{ ucwords($info->firstname.' '.$info->lastname) }}
                            </p>

                            <p><span class="text-muted">{{ trans('backend.address') }}:</span><br>
                            {{ ucwords($info->billing_address_1.' '.$info->billing_city.', '.$info->billing_state) }} 
                            {{ $info->billing_zipcode }}
                            {{ $info->billing_country ? countries($info->billing_country) : '' }}
                            </p>    

                            <p><span class="text-muted">{{ trans('backend.email') }}:</span><br>
                                <a href="">{{ $info->billing_email_address }}</a>
                            </p>
                            <p><span class="text-muted">{{ trans('backend.phone') }}:</span><br>
                                {{ $info->billing_phone }}
                            </p>
                        </div>

                        <div class="billing-form" style="{{ count($errors->all()) ? '' : 'display: none;' }}">

                            <div class="row">
                                <div class="col form-group">
                                    <label>{{ trans('backend.firstname') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_firstname" class="form-control" value="{{ Input::old('billing_firstname', $info->billing_firstname) }}">
                                    {!! $errors->first('billing_firstname','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>{{ trans('backend.lastname') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_lastname" class="form-control" value="{{ Input::old('billing_lastname', $info->billing_lastname) }}">
                                    {!! $errors->first('billing_lastname','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>{{ trans('backend.email') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_email_address" class="form-control" value="{{ Input::old('billing_email_address', $info->billing_email_address) }}">
                                    {!! $errors->first('billing_email_address','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>{{ trans('backend.phone') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_phone" class="form-control" value="{{ Input::old('billing_phone', $info->billing_phone) }}">
                                    {!! $errors->first('billing_phone','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('backend.address') }} 1 <span class="text-danger">*</span></label>
                                <input type="text" name="billing_address_1" class="form-control" placeholder="Street Address or P.O Box" value="{{ Input::old('billing_address_1', $info->billing_address_1) }}">
                                {!! $errors->first('billing_address_1','<p class="text-danger my-2">:message</p>') !!}
                            </div>
                            <div class="form-group">
                                <label>{{ trans('backend.address') }} 2 <span class="text-muted small">({{ trans('backend.optional') }})</span></label>
                                <input type="text" name="billing_address_2" class="form-control" placeholder="Company, C/O, Apt, Suite, Unit, Building, Floor" value="{{ Input::old('billing_address_2', $info->billing_address_2) }}">
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>{{ trans('backend.city') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_city" class="form-control" value="{{ Input::old('billing_city', $info->billing_city) }}">
                                    {!! $errors->first('billing_city','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>{{ trans('backend.state') }} / {{ trans('backend.province') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_state" class="form-control" value="{{ Input::old('billing_state', $info->billing_state) }}">
                                    {!! $errors->first('billing_state','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>{{ trans('backend.zip') }} / {{ trans('backend.postal_code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_zipcode" class="form-control" value="{{ Input::old('billing_zipcode', $info->billing_zipcode) }}">
                                    {!! $errors->first('billing_zipcode','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>{{ trans('backend.country') }} <span class="text-danger">*</span></label>
                                    {{ Form::select('billing_country', ['' => 'Select Country'] + countries(), Input::old('billing_country', $info->billing_country), ['class' => 'form-control'] ) }}
                                    {!! $errors->first('billing_country','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            
                        </div>

                    </div>
                    <div class="col-lg-6">
                        
                        <h6 class="mb-4">{{ trans('backend.shipping_details') }}</h6>                

                        <div class="shipping-details" style="{{ count($errors->all()) ? 'display: none;' : '' }}">

                            @if( has_access($module, ['edit']) ) 
                            <a href="#" class="edit-details float-right" data-target=".shipping"><i class="far fa-edit"></i> {{ trans('backend.edit') }}</a> 
                            @endif

                            <p><span class="text-muted">{{ trans('backend.name') }}:</span><br>
                            {{ ucwords($info->shipping_firstname.' '.$info->shipping_lastname) }}
                            </p>

                            <p><span class="text-muted">{{ trans('backend.address') }}:</span><br>
                            {{ ucwords($info->shipping_address_1.' '.$info->shipping_city.', '.$info->shipping_state) }} 
                            {{ $info->shipping_zipcode }}
                            {{ $info->shipping_country ? countries($info->shipping_country) : '' }}
                            </p>    

                            <p><span class="text-muted">{{ trans('backend.email') }}:</span><br>
                                <a href="">{{ $info->shipping_email_address }}</a>
                            </p>
                            <p><span class="text-muted">{{ trans('backend.phone') }}:</span><br>
                                {{ $info->shipping_phone }}
                            </p>
                        </div>

                        <div class="shipping-details" style="{{ count($errors->all()) ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col form-group">
                                    <label>{{ trans('backend.firstname') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_firstname" class="form-control" value="{{ Input::old('shipping_firstname', $info->shipping_firstname) }}">
                                    {!! $errors->first('shipping_firstname','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>{{ trans('backend.lastname') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_lastname" class="form-control" value="{{ Input::old('shipping_lastname', $info->shipping_lastname) }}">
                                    {!! $errors->first('shipping_lastname','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>{{ trans('backend.email') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_email_address" class="form-control" value="{{ Input::old('shipping_email_address', $info->shipping_email_address) }}">
                                    {!! $errors->first('shipping_email_address','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>{{ trans('backend.phone') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_phone" class="form-control" value="{{ Input::old('shipping_phone', $info->shipping_phone) }}">
                                    {!! $errors->first('shipping_phone','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('backend.address') }} 1 <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_address_1" class="form-control" placeholder="Street Address or P.O Box" value="{{ Input::old('shipping_address_1', $info->shipping_address_1) }}">
                                {!! $errors->first('shipping_address_1','<p class="text-danger my-2">:message</p>') !!}
                            </div>
                            <div class="form-group">
                                <label>{{ trans('backend.address') }} 2 <span class="text-muted small">({{ trans('backend.optional') }})</span></label>
                                <input type="text" name="shipping_address_2" class="form-control" placeholder="Company, C/O, Apt, Suite, Unit, Building, Floor" value="{{ Input::old('shipping_address_2', $info->shipping_address_2) }}">
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>{{ trans('backend.city') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_city" class="form-control" value="{{ Input::old('shipping_city', $info->shipping_city) }}">
                                    {!! $errors->first('shipping_city','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>{{ trans('backend.state') }} / {{ trans('backend.province') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_state" class="form-control" value="{{ Input::old('shipping_state', $info->shipping_state) }}">
                                    {!! $errors->first('shipping_state','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group">
                                    <label>{{ trans('backend.zip') }} / {{ trans('backend.postal_code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_zipcode" class="form-control" value="{{ Input::old('shipping_zipcode', $info->shipping_zipcode) }}">
                                    {!! $errors->first('shipping_zipcode','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                                <div class="col form-group">
                                    <label>{{ trans('backend.country') }} <span class="text-danger">*</span></label>
                                    {{ Form::select('shipping_country', ['' => 'Select Country'] + countries(), Input::old('shipping_country', $info->shipping_country), ['class' => 'form-control'] ) }}
                                    {!! $errors->first('shipping_country','<p class="text-danger my-2">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>

            </div>


            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.orders') }}</div>
                <div class="card-body">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="80"></th>
                                <th width="250">{{ trans('backend.product') }}</th>
                                <th class="text-right">{{ trans('backend.quantity') }}</th>
                                <th class="text-right">{{ trans('backend.price') }}</th>
                                <th class="text-right">{{ trans('backend.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach( json_decode($info->post_content)->orders as $order )
                        <tr>
                            <td>
                                <img src="{{ has_image($order->image) }}" class="img-fluid"> 
                            </td>
                            <td>
                                <p class="mb-2">{{ $order->name }}</p>

                                @if( @$order->sku )
                                <span class="text-muted text-uppercase">sku:</span> {{ @$order->sku }}
                                @endif
                                <div class="small mt-2 text-uppercase">
                                @if( @$order->variation_data )
                                    @foreach( json_decode($order->variation_data) as $vd_k => $vd_v )
                                    <div><span class="text-muted">{{  $vd_k }}</span> : {{ $vd_v }}</div>
                                    @endforeach
                                @endif   
                                </div>


                            </td>
                            <td class="text-right">{{ $order->quantity }}</td>
                            <td class="text-right">{{ amount_formatted($order->item_price) }}</td>
                            <td class="text-right">{{ amount_formatted($order->total_price) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="offset-lg-5 col-lg-7">
                            <table class="table table-bordered table-striped mb-0">
                                <tr class="bg-dark text-white">
                                    <td align="right" width="50%">{{ trans('backend.payment_method') }}</td>
                                    <td align="right">{{ $info->payment_method ? payment_methods($info->payment_method)['name'] : 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td align="right">{{ trans('backend.subtotal') }}</td>
                                    <td align="right">{{ amount_formatted($info->subtotal) }}</td>
                                </tr>
                                @if($info->shipping_fee)
                                <tr>
                                    <td align="right">{{ trans('backend.shipping') }}</td>
                                    <td align="right">{{ amount_formatted($info->shipping_fee) }}</td>
                                </tr>
                                @endif
                                @if($info->discount_fee)
                                <tr class="text-danger">
                                    <td align="right">{{ trans('backend.discount') }}</td>
                                    <td align="right">- {{ amount_formatted($info->discount_fee) }}</td>
                                </tr>
                                @endif
                                <tr class="font-weight-bold h5">
                                    <td align="right">{{ trans('backend.total') }}</td>
                                    <td align="right">{{ amount_formatted($info->total) }} </td> 
                                </tr>
                            </table>                            
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-5">

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.date') }}</div>
                <div class="card-body">


                    <label>{{ trans('backend.created_on') }}</label>
                    <div class="form-group">
                        <strong>{{ date_formatted($info->created_at) }}</strong> @ 
                        <strong>{{ time_formatted($info->created_at) }}</strong>                          
                    </div>

  
                    <label>{{ trans('backend.date_ordered') }}</label>  
                    <div class="input-group">
                        <input type="text" name="date_ordered" class="form-control w-50 date-format datepicker" placeholder="mm-dd-yyyy" 
                        value="{{ Input::old('date_ordered', date_formatted_b($info->date_ordered)) }}">  
                        <input type="text" name="time_ordered" class="form-control w-25 time-format timepicker" placeholder="00:00" 
                        value="{{ Input::old('time_ordered', @$info->time_ordered) }}">  
                    </div>                            

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.status') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', order_status(), Input::old('status', @$info->post_status), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>
                    <div class="form-group">
                        <div class="mb-0 border p-3 bg-light rounded">
                            <p>{{ trans('backend.status') }} from <b>{{ $info->payment_method }}</b></p>
                            <div class="text-uppercase"><b>{{ $info->order_status }}</b></div>
                            <small>on {{ date_formatted(@$info->order_status_updated) }} @ {{ time_formatted(@$info->order_status_updated) }}</small> 
                        </div> 

                        @if( $info->payment_method == 'gotopay')
                        <a href="?gotopay-check-order=1" class="btn btn-block btn-sm btn-outline-dark mt-1">Check Status</a>
                        @endif

                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.notes') }}</div>
                <div class="card-body">


                <div class="order-notes mb-4">     

                    @if( $info->notes )
                    <div class="mb-4">
                        <div class="note-content">{{ $info->notes }}</div>
                        <p class="px-3 pt-2">added on <b class="text-info">{{ date('F d, Y', strtotime($info->created_at)) }}</b> at <b class="text-info">{{ time_formatted($info->created_at) }}</b> by <b class="text-info">Customer</b></p>    
                    </div>
                    @endif

                    @foreach($notes as $note)   
                        @include('backend.partials.note')
                    @endforeach
                </div>

                    <div class="form-group">
                        <textarea id="note" class="form-control" rows="5" placeholder="Enter your comment here."></textarea>   
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-note float-right" data-url="{{ route('backend.general.note', $info->id) }}">
                    <i class="fa fa-plus"></i> {{ trans('backend.add_note') }}
                    </button> 
                </div>
            </div>

        </div>
    </div>
    @if( has_access($module, ['edit']) ) 
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ trans('backend.save_changes') }}</button>                     
    </div>
    @endif
</form>

<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
<script>
$(document).on('click', '.edit-details', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    $(target+'-details, '+target+'-form').toggle('fast');
});  
</script>
@stop
