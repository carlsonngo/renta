<div class="row">
@if( @$info->attribute_data )
    @foreach( json_decode(@$info->attribute_data) as $attribute )

        <?php 
            $attribute_id = @$attribute->id;
            $attribute_name = @$attribute->name;
            $attribute_values = @$attribute->values;
        ?>    

        @if( @$attribute->is_variation && @$attribute->name && $attribute->values ) 

            @if( @$attribute_id )

                <?php 
                $attribute_data = App\Post::find( $attribute_id );
                foreach ($attribute_data->postmetas as $postmeta) {
                    $attribute_data[$postmeta->meta_key] = $postmeta->meta_value;
                }
                $slug = text_to_slug($attribute_data->post_title);
                ?>
                @if( @$attribute->is_color )    
                <div class="col-12 mb-3">                    
                    <div>
                        <label class="text-uppercase small">{{ $attribute->name }}</label>
                    </div>
                    <input type="hidden" name="{{ $slug }}" class="shop-calc" value="{{ Input::get($slug, @$attribute->default) }}">
                    @foreach( json_decode($attribute_data->term) as $term)
                    <?php $term_slug = text_to_slug($term->name); ?>
                    @if(  @in_array($term_slug, $attribute_values) )
                    <a href="#" 
                        class="shop-calc color-swatch is-{{ $term_slug }} {{ actived(Input::get($slug, @$attribute->default), $term_slug) }}" 
                        title="{{ $term->name }}" 
                        data-toggle="tooltip" 
                        data-val="{{ $term_slug }}"
                        style="background:{{ $term->color }};">
                    </a>
                    @endif
                    @endforeach                            
                </div>
                @else
                <div class="col-6 mb-3">
                    <label class="text-uppercase small">{{ $attribute->name }}</label>
                    <select name="{{ $slug }}" class="form-control form-control-sm shop-calc">
                    @foreach( json_decode($attribute_data->term) as $term)
                        <?php $term_slug = text_to_slug($term->name); ?>
                        @if(  @in_array($term_slug, $attribute_values) )
                        <option value="{{ $term_slug }}" {{ selected(Input::get($slug, @$attribute->default), $term_slug) }}>{{ $term->name }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                @endif
            @else
                <div class="col-6 mb-3">
                    <label class="text-uppercase small">{{ $attribute->name }}</label>    
                    <select name="{{ $slug = text_to_slug($attribute->name) }}" class="form-control form-control-sm shop-calc">
                    @foreach( explode('|', $attribute->values) as $term)
                    <?php $term_slug = text_to_slug($term); ?>
                    <option value="{{ $term_slug }}" {{ selected(Input::get($slug, @$attribute->default), $term_slug) }}>{{ $term }}</option>
                    @endforeach
                    </select>        
                </div>

            @endif

        @endif

    @endforeach
@endif
</div>

