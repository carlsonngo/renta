@if( @$attribute_id )
<?php 
$attribute_data = App\Post::find( $attribute_id );
foreach ($attribute_data->postmetas as $postmeta) {
    $attribute_data[$postmeta->meta_key] = $postmeta->meta_value;
}
$slug = text_to_slug($attribute_data->post_title);
?>
<section class="bg-white border p-3 mb-2 attribute-{{ $attribute_data->id }}">

  <div class="row">
  <div class="col-md-4">
    <label class="text-muted small">{{ trans('backend.name') }}</label>
    <h6><strong>{{ $attribute_data->post_title }}</strong></h6>   
    <input type="hidden" name="attribute_data[{{ $slug }}][is_variation]" value="0">
    <label><input type="checkbox" name="attribute_data[{{ $slug }}][is_variation]" value="1" class="used_variation" {{ checked(1, @$attr_data->is_variation)}}> {{ trans('backend.used_for_variations') }}</label>     
  </div>
  <div class="col">
    <label class="text-muted small">{{ trans('backend.values') }}</label>
    <div class="mb-2">
      <input type="hidden" name="attribute_data[{{ $slug }}][is_color]" value="{{ $attribute_data->is_color }}">       
      <input type="hidden" name="attribute_data[{{ $slug }}][is_term]" value="1"> 
      <input type="hidden" name="attribute_data[{{ $slug }}][id]" value="{{ $attribute_data->id }}">      
      <input type="hidden" name="attribute_data[{{ $slug }}][name]" value="{{ $attribute_data->post_title }}">
      <select name="attribute_data[{{ $slug }}][values][]" class="form-control select2" multiple="multiple">
      @foreach( json_decode($attribute_data->term) as $term)
        <option value="{{ $term_slug = text_to_slug($term->name) }}" 
          {{ @in_array($term_slug, $attribute_values) ? 'selected' : '' }}>{{ $term->name }}</option>
      @endforeach
      </select>
    </div>
    <button type="button" class="btn btn-sm btn-outline-dark btn-select-all">{{ trans('backend.select_all') }}</button> 
    <button type="button" class="btn btn-sm btn-outline-dark btn-select-none">{{ trans('backend.select_none') }}</button>   
    <a href="" class="small text-danger btn-remove-attribute text-uppercase ml-2"  data-id="{{ $attribute_data->id }}">{{ trans('backend.remove') }}</a>
  </div>
  </div>
</section>
@else
<section class="bg-white border p-3 mb-2 attribute-{{ $rand = @$attr_data_k ?? strtolower(str_random(6)) }}">
  <input type="hidden" name="attribute_data[{{ $rand }}][is_color]" value="0">       
  <input type="hidden" name="attribute_data[{{ $rand }}][is_term]" value="0"> 
  <input type="hidden" name="attribute_data[{{ $rand }}][id]" value="0">      

  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label class="text-muted small">{{ trans('backend.name') }}</label>
        <input type="text" name="attribute_data[{{ $rand }}][name]" value="{{ @$attribute_name }}" class="form-control form-control-sm">   
        
      </div>
    <input type="hidden" name="attribute_data[{{ $rand }}][is_variation]" value="0">
    <label><input type="checkbox" name="attribute_data[{{ $rand }}][is_variation]" value="1" class="used_variation" {{ checked(1, @$attr_data->is_variation)}}> {{ trans('backend.used_for_variations') }}</label>     
      
    </div>
    <div class="col">
      <label class="text-muted small">{{ trans('backend.values') }}</label>
      <div class="mb-2">
        <textarea name="attribute_data[{{ $rand }}][values]" cols="5" rows="5" placeholder="Enter some text, or some attributes by &quot;|&quot; separating values." class="form-control form-control-sm mb-2">{{ @$attribute_values }}</textarea>             
        <a href="" class="small text-danger btn-remove-attribute text-uppercase" data-id="{{ $rand }}">{{ trans('backend.remove') }}</a>
      </div>
    </div>
</div>
</section>
@endif

