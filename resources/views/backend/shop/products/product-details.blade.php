<div class="form-group">
    <label>Short Item Desciption</label>
    <textarea name="short_description" class="form-control" rows="5">{{ Input::old('short_description', @$info->short_description) }}</textarea>
    {!! $errors->first('short_description','<p class="text-danger my-2">:message</p>') !!}                  
</div>
                    
<div class="form-group">
    <label class="text-muted">SKU</label>
    <input type="text" name="sku" class="form-control" value="{{ Input::old('sku', @$info->sku) }}"> 
    {!! $errors->first('sku','<p class="text-danger my-2">:message</p>') !!}                  
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            <label class="text-muted">{{ trans('backend.regular_price') }} ({{ currency_symbol($currency) }})</label>
            <input type="number" name="regular_price" class="form-control get-price numeric" min="1" step="any" value="{{ Input::old('regular_price', @$info->regular_price) }}"> 
            {!! $errors->first('regular_price','<p class="text-danger my-2">:message</p>') !!}                  
        </div>                            
    </div>
    <div class="col">
        <div class="form-group">
            <label class="text-muted">{{ trans('backend.sale_price') }} ({{ currency_symbol($currency) }})</label>
            <input type="number" name="sale_price" class="form-control get-price numeric" min="0" step="any" value="{{ Input::old('sale_price', @$info->sale_price) }}"> 
             <input type="hidden" name="price" class="form-control" value="{{ Input::old('price', @$info->price) }}"> 
            {!! $errors->first('sale_price','<p class="text-danger my-2">:message</p>') !!}                  
        </div>                            
    </div>
</div>

<hr>
<label class="text-muted">{{ trans('backend.sale_price_dates') }}</label>
<div class="row">
    <div class="form-group col">
        <label class="text-muted">{{ trans('backend.date_start') }}</label>  
        <div class="input-group">
            <input type="text" name="sale_date_start" class="form-control w-50 datepicker date-format" placeholder="mm-dd-yyyy" 
            value="{{ Input::old('sale_date_start', @$info->sale_date_start) }}">  
            <input type="text" name="sale_time_start" class="form-control w-25 timepicker time-format" placeholder="00:00" 
            value="{{ Input::old('sale_time_start', @$info->sale_time_start) }}">  
        </div>                            
    </div>
    <div class="form-group col">
        <label class="text-muted">{{ trans('backend.date_end') }}</label> 
        <div class="input-group">
            <input type="text" name="sale_date_end" class="form-control w-50 datepicker date-format" placeholder="mm-dd-yyyy" 
            value="{{ Input::old('sale_date_end', @$info->sale_date_end) }}">  
            <input type="text" name="sale_time_end" class="form-control w-25 timepicker time-format" placeholder="00:00" 
            value="{{ Input::old('sale_time_end', @$info->sale_time_end) }}">  
        </div>                            
    </div>
</div>

<div class="form-group">
    <label class="text-muted">Stocks</label> 
    <div class="input-group">
        <input type="text" name="stocks" class="form-control numeric" 
        value="{{ Input::old('stocks', @$info->stocks) }}">  
    </div>                            
</div>

<div class="custom-control custom-checkbox align-items-center d-flex">
    <input type="hidden" name="enable_reviews" value="0">
    {{ Form::checkbox('enable_reviews', 1, @$info->enable_reviews, ['class' => 'custom-control-input', 'id' => 'enable_reviews']) }}
    <label class="custom-control-label" for="enable_reviews">Enable reviews</label> 
</div>

<div class="custom-control custom-checkbox align-items-center d-flex">
    <input type="hidden" name="sold_individually" value="0">
    {{ Form::checkbox('sold_individually', 1, @$info->sold_individually, ['class' => 'custom-control-input', 'id' => 'sold_individually']) }}
    <label class="custom-control-label" for="sold_individually">Sold individually / <span class="text-muted">Enable this to only allow one of this item to be bought in a single order</span></label> 
</div>


<div class="form-group">

    <div class="custom-control custom-checkbox align-items-center d-flex mb-2">
        <input type="hidden" name="rental_product" value="0">
        {{ Form::checkbox('rental_product', 1, @$info->rental_product, ['class' => 'custom-control-input', 'id' => 'rental_product', 'data-toggle' => 'form', 'data-target' => '.form-rental_product']) }}
        <label class="custom-control-label" for="rental_product">Rental Product</label> 
    </div>

    <div class="form-rental_product" style="{{ $info->rental_product ? '' : 'display:none;' }}">
        <?php $rental = Input::old('rental', json_decode(@$info->rental, true)); ?>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="text-muted">Min Days</label>
                <input type="text" name="rental[min_days]" class="form-control numeric" value="{{ Input::old('rental.min_days', @$rental['min_days']) }}">            
            </div>        
            <div class="form-group col-md-6">
                <label class="text-muted">Max Days</label>
                <input type="text" name="rental[max_days]" class="form-control numeric" value="{{ Input::old('rental.max_days', @$rental['max_days']) }}">       
            </div>       
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label class="text-muted">Excess days Rate ({{ currency_symbol($currency) }})</label>
                <input type="text" name="rental[excess_rate]" class="form-control numeric" value="{{ Input::old('rental.excess_rate', @$rental['excess_rate']) }}">
            </div>        
        </div>           
    </div>              
</div>
