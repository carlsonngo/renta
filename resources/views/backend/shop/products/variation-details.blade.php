<?php 
    $vd = json_decode($info->variation_data); 
    $vid =  $info->id.sprintf('%06d', $v_k+1);
?>
<div class="float-right text-muted small">ID : {{ $vid }}</div>
<input type="hidden" name="variation_data[{{ $v_k }}][field][id]" value="{{ $vid }}">

<div class="form-group">
    <label class="text-muted">SKU</label>
    <input type="text" name="variation_data[{{ $v_k }}][field][sku]" class="form-control variable_sku" value="{{ Input::old('variation_data.'.$v_k.'.field.sku', @$vd[$v_k]->field->sku) }}">               
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            <label class="text-muted">{{ trans('backend.regular_price') }} ({{ currency_symbol($currency) }})</label>
            <input type="number" name="variation_data[{{ $v_k }}][field][regular_price]" class="form-control variable_regular_price numeric" min="1" step="any" value="{{ Input::old('variation_data.'.$v_k.'.field.regular_price', @$vd[$v_k]->field->regular_price) }}">                
        </div>                            
    </div>
    <div class="col">
        <div class="form-group">
            <label class="text-muted">{{ trans('backend.sale_price') }} ({{ currency_symbol($currency) }})</label>
            <input type="number" name="variation_data[{{ $v_k }}][field][sale_price]" class="form-control variable_sale_price numeric" min="0" step="any" value="{{ Input::old('variation_data.'.$v_k.'.field.sale_price', @$vd[$v_k]->field->sale_price) }}">               
        </div>                            
    </div>
</div>
<hr>
<h6>{{ trans('backend.sale_price_dates') }}</h6>
<div class="row">
    <div class="col">
        <label class="text-muted">{{ trans('backend.date_start') }}</label>  
        <div class="input-group">
            <input type="text" name="variation_data[{{ $v_k }}][field][sale_date_start]" class="form-control w-50 datepicker date-format variable_sale_date_start" placeholder="mm-dd-yyyy" 
            value="{{ Input::old('variation_data.'.$v_k.'.field.sale_date_start', @$vd[$v_k]->field->sale_date_start) }}">  
            <input type="text" name="variation_data[{{ $v_k }}][field][sale_time_start]" class="form-control w-25 timepicker time-format" placeholder="00:00" 
            value="{{ Input::old('variation_data.'.$v_k.'.field.sale_time_start', @$vd[$v_k]->field->sale_time_start) }}">  
        </div>                            
    </div>
    <div class="col">
        <label class="text-muted">{{ trans('backend.date_end') }}</label> 
        <div class="input-group">
            <input type="text" name="variation_data[{{ $v_k }}][field][sale_date_end]" class="form-control w-50 datepicker date-format variable_sale_date_end" placeholder="mm-dd-yyyy" 
            value="{{ Input::old('variation_data.'.$v_k.'.field.sale_date_end', @$vd[$v_k]->field->sale_date_end) }}">  
            <input type="text" name="variation_data[{{ $v_k }}][field][sale_time_end]" class="form-control w-25 timepicker time-format" placeholder="00:00" 
            value="{{ Input::old('variation_data.'.$v_k.'.field.sale_time_end', @$vd[$v_k]->field->sale_time_end) }}">  
        </div>                            
    </div>
</div>