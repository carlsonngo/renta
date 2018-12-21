<div class="form-group form-row align-items-center">

	<div class="col-auto">
		{{ trans('backend.default_from_values') }}:
	</div>
	<div class="col-auto">
	@if(@$info->attribute_data)
		@foreach(json_decode($info->attribute_data) as $attr_k => $attr)
			@if( @$attr->is_variation && @$attr->name && $attr->values) 
		          <select name="attribute_data[{{ $attr_k }}][default]" class="p-1">
	              @if($attr->is_term)
	                <?php 
	                $attribute_data = App\Post::find( $attr->id );
	                foreach ($attribute_data->postmetas as $postmeta) {
	                    $attribute_data[$postmeta->meta_key] = $postmeta->meta_value;
	                }
	                $slug = text_to_slug($attribute_data->post_title);
	                ?>
	                @foreach( json_decode($attribute_data->term) as $term)
	                  <?php $term_slug = text_to_slug($term->name); ?>
	                  @if( in_array($term_slug, $attr->values) )
	                  <option value="{{ $term_slug }}" {{ selected(@$attr->default, $term_slug) }}>{{ $term->name }}</option>
	                  @endif
	                @endforeach
	                @else
	                @foreach(explode('|', $attr->values) as $val)                
	                <option value="{{ $val_slug = text_to_slug($val) }}" {{ selected(@$attr->default, $val_slug) }}>{{ $val }}</option>
	                @endforeach                
	              @endif
	            </select>
			@endif
		@endforeach
	@endif
	</div>
</div>

<div class="row form-group">
	<div class="col-auto pr-0">
		<select class="variation_actions form-control form-control-sm">
			<option value="variable_sku">Set SKU</option>
			<option value="variable_regular_price">{{ trans('backend.set_regular_prices') }}</option>
			<option value="variable_sale_price">{{ trans('backend.set_sale_prices') }}</option>
			<option value="variable_sale_date">{{ trans('backend.set_schedule_sales_date') }}</option>
		</select>		
	</div>	
	<div class="col-auto">
		<button type="button" class="btn btn-sm btn-outline-primary btn-set-variable">{{ trans('backend.go') }}</button>
	</div>	
</div>


@if(@$info->variation_attributes)
<div id="variations">

  @foreach(json_decode($info->variation_attributes) as $v_k => $variation)

  <div class="card mb-2">
    <div class="card-header px-3 pt-2 pb-1" id="h-{{ $v_k }}">

          <div class="row align-items-center">
            <div class="col">
          <?php $t_k=0; ?>
          @foreach(json_decode(@$info->attribute_data) as $attr_data) 
            @if( @$attr_data->is_variation && @$attr_data->name && $attr_data->values )
            <select name="variation_data[{{ $v_k }}][key][]" class="rounded mb-1 disabled-click px-2">
                @foreach($variation as $val)                
                  <option value="{{ $val }}" {{ selected($val, $variation[$t_k]) }}>{{ code_to_text($val) }}</option>
                @endforeach   

            </select>
            <?php $t_k++; ?>
            @endif
          @endforeach


            </div>
            <div class="col-auto small text-right text-uppercase">


        <div class="mb-2">
                  <b class="text-dark">#{{ $v_k+1 }}</b> | 
                  <a href="#" class="collapsed" data-toggle="collapse" data-target="#val-{{ $v_k }}" aria-expanded="false" aria-controls="val-{{ $v_k }}">{{ trans('backend.toggle') }}</a>
        </div>


            </div>
          </div>



    </div>
    <div id="val-{{ $v_k }}" class="collapse" aria-labelledby="h1-{{ $v_k }}" data-parent="#variations">
      <div class="card-body">
       @include('backend.shop.products.variation-details')  
      </div>
    </div>
  </div>

  @endforeach

</div>
@endif