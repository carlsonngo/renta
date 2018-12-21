@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span> 
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.edit') }}</span>

<div class="float-right">
    <a href="{{ route($view.'.add') }}" class="btn btn-sm btn-dark"> {{ trans('backend.add_new') }}</a>   
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_'.str_plural($post_type)) }}</a>
    
</div>

<hr>
@stop

@section('content')

<form method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{ $info->id }}">
    <div class="row">
        <div class="col-lg-8 col-md-7">

            <div class="form-group">
                <div class="input-group input-group-sm  align-items-center mb-3">
                    <div class="input-group-prepend  align-items-center">
                        <span class="mr-3">Permalink</span>
                        <span class="input-group-text d-none d-md-inline-block" id="basic-addon3">{{ url('/') }}/shop/</span>
                    </div>
                    <input type="text" name="slug" class="form-control no-space to-slug" data-type="slugy" value="{{ Input::old('slug', $info->post_name) }}">

                    @if( $info->post_status == 'actived' )
                    <a href="{{ route('shop.single', $info->post_name) }}" class="ml-3" target="_blank">{{ trans('backend.view') }}</a>
                    @endif

                </div>
                {!! $errors->first('slug','<p class="text-danger my-2">:message</p>') !!}  
            </div>

            @include('backend.partials.content.edit')

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.details') }}</div>
                <div class="card-body">

                <div class="form-row form-group align-items-center">
                    <div class="col-auto">{{ trans('backend.product_type') }}</div>
                    <div class="col-auto">
                    {{ Form::select('product_type', product_types(), $product_type = Input::old('product_type', @$info->product_type), ['class' => 'form-control form-control-sm']) }}                             
                    </div>               
                </div>
                                  
				<div class="loader-ui"></div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab">
                          <a class="nav-link general-pill active" data-toggle="pill" href="#v-pills-general">{{ trans('backend.general') }}</a>
                          <a class="nav-link variable-product" data-toggle="pill" href="#v-pills-attributes" style="{{ $product_type=='variable'?'':'display:none;' }}">{{ trans('backend.attributes') }}</a>
                          <a class="nav-link variable-product" data-toggle="pill" href="#v-pills-variations" style="{{ $product_type=='variable'?'':'display:none;' }}">{{ trans('backend.variations') }}</a>                                   
                        </div>        
                    </div>
                    <div class="col-lg-9">
                        <div class="tab-content" id="v-pills-tabContent">
                          <div class="tab-pane fade show active" id="v-pills-general">
                            @include('backend.shop.products.product-details')  
                          </div>
                          <div class="tab-pane fade" id="v-pills-attributes" role="tabpanel">

                            <div class="row mb-3">
                                <div class="col-auto">
                                  <select class="form-control form-control-sm" name="attribute">
                                    <option value="custom">{{ trans('backend.custom_product_attribute') }}</option>
                                    @foreach($attributes as $attr_k => $attr_v)
                                    <?php 
                                    $attr_disabled = '';
                                    if( @$info->attribute_data ) {                                        
                                        $attr_disabled = in_array(text_to_slug($attr_v), array_keys(json_decode(@$info->attribute_data, true))) ? 'disabled' : '';
                                    }
                                    ?>
                                    <option value="{{ $attr_k }}" 
                                    {{ $attr_disabled }}>{{ $attr_v }}</option>
                                    @endforeach
                                 </select>

                                </div>
                                <div class="col pl-0">
                                    <a href="{{ route('backend.shop.products.data-attributes') }}" 
                                    data-target=".attributes-data"
                                    data-type="add-attribute"
                                    class="btn btn-outline-primary btn-sm btn-save-data">{{ trans('backend.add_attribute') }}</a>
                                </div>
                            </div>

                            <div class="attributes-data sortable">
                                @if( @$info->attribute_data )
                                    @foreach(json_decode(@$info->attribute_data) as $attr_data_k => $attr_data)     
                                    <?php 
                                        $attribute_id = @$attr_data->id;
                                        $attribute_name = @$attr_data->name;
                                        $attribute_values = @$attr_data->values;
                                    ?>                        
                                    @include('backend.shop.products.data.attributes')  
                                    @endforeach
                                @endif
                            </div>
                            <hr>
                            <a href="{{ route('backend.shop.products.data-attributes') }}" 
                            data-target=".attributes-data"
                            data-type="save-attributes"
                            class="btn btn-outline-primary btn-sm btn-save-data">{{ trans('backend.save_attributes') }}</a>

                          </div>
                          <div class="tab-pane fade" id="v-pills-variations" role="tabpanel">

                          	<div class="variations-data">@include('backend.shop.products.data.variations') </div>
					        <hr>
                            <a href="{{ route('backend.shop.products.data-variations') }}" 
                            data-target=".variations-data"
                            data-type="save-variations"
                            class="btn btn-outline-primary btn-sm btn-save-data">{{ trans('backend.save_variations') }}</a>

                          </div>
                        </div>
                    </div>
                </div>



                </div>
            </div>

            @if( count($reviews) )
            <div class="card mb-3 border-0 p-0 bg-none">
                <div class="card-header bg-dark text-white text-uppercase">Reviews</div>
                <div class="card-body p-0">
                <iframe src="{{ route('shop.reviews', $info->id) }}" width="100%" frameborder="0" scrolling="no" onload="resizeIframe(this)"></iframe>
                </div>
            </div>
            @endif

            <div class="card mb-3 bg-white">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.additional_info') }}</div>
                <div class="card-body">

                    <div class="form-extra-o" style="display:none;">
                    <?php $e = 0; ?>
                    @include('backend.shop.products.data.extra')
                    </div>

                    <div class="form-extra">
                        <?php 
                            $extra = Input::old('extra', json_decode(@$info->extra, true)); 
                            $e = 1; 
                        ?>
                        @if( $extra )
                        @foreach( $extra as $ex)
                            @include('backend.shop.products.data.extra')
                            <?php $e++; ?>
                        @endforeach
                        @else
                            <?php $e = 1; ?>
                            @include('backend.shop.products.data.extra')                        
                        @endif
                    </div>

                    <button class="btn btn-sm btn-outline-primary btn-add-extra">{{ trans('backend.add_more') }}</button>   

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">SEO</div>
                <div class="card-body">
                    <!-- Nav pills -->
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#tab1"><i class="fas fa-tags mr-1"></i> Meta {{ trans('backend.tags') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab2"><i class="fab fa-facebook mr-1"></i> Facebook</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab3"><i class="fab fa-twitter mr-1"></i> Twitter</a>
                        </li>
                    </ul>
                    <hr>
                    <!-- Tab panes -->
                    <div class="tab-content mt-3">
                        <div class="tab-pane container p-0 active" id="tab1">
                            <div class="form-group">
                                <label>Meta {{ trans('backend.title') }}</label>
                                <input type="text" name="meta_title" class="form-control" value="{{ Input::old('meta_title', $info->meta_title) }}">                    
                            </div>
                            <div class="form-group">
                                <label>Meta {{ trans('backend.keywords') }}</label>
                                <input type="text" name="meta_keywords" class="form-control" value="{{ Input::old('meta_keywords', $info->meta_keywords) }}">                    
                            </div>
                            <div class="form-group">
                                <label>Meta {{ trans('backend.description') }}</label>
                                <textarea name="meta_description" class="form-control" rows="6">{{ Input::old('meta_description', $info->meta_description) }}</textarea>                
                            </div>
                        </div>
                        <div class="tab-pane container p-0 fade" id="tab2">
                            <div class="form-group">
                                <label>Facebook {{ trans('backend.title') }}</label>
                                <input type="text" name="facebook_title" class="form-control" value="{{ Input::old('facebook_title', $info->facebook_title) }}">  
                                <div class="text-muted mt-2">{{ trans('messages.social_title', ['variable' => 'facebook']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Facebook {{ trans('backend.description') }}</label>
                                <textarea name="facebook_description" class="form-control" rows="6">{{ Input::old('facebook_description', $info->facebook_description) }}</textarea>
                                <div class="text-muted mt-2">{{ trans('messages.social_description', ['variable' => 'facebook']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Facebook {{ trans('backend.image') }}</label>   
                                <div class="text-muted mt-2">{{ trans('messages.social_image', ['variable' => 'facebook']) }}</div>
                            </div>
                        </div>
                        <div class="tab-pane container p-0 fade" id="tab3">
                            <div class="form-group">
                                <label>Twitter {{ trans('backend.title') }}</label>
                                <input type="text" name="twitter_title" class="form-control" value="{{ Input::old('twitter_title', $info->twitter_title) }}">  
                                <div class="text-muted mt-2">{{ trans('messages.social_title', ['variable' => 'twitter']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Twitter {{ trans('backend.description') }}</label>
                                <textarea name="twitter_description" class="form-control" rows="6">{{ Input::old('twitter_description', $info->twitter_description) }}</textarea>
                                <div class="text-muted mt-2">{{ trans('messages.social_description', ['variable' => 'twitter']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Twitter {{ trans('backend.image') }}</label>  
                                <div class="text-muted mt-2">{{ trans('messages.social_image', ['variable' => 'twitter']) }}.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.date') }}</div>
                <div class="card-body pb-0">
                	<div class="row">
						<div class="col-lg col-md-12 col">               		 
		                    <label>{{ trans('backend.created_on') }}</label>
		                    <div class="form-group">
		                        <strong>{{ date_formatted($info->created_at) }}</strong> @ 
		                        <strong>{{ time_formatted($info->created_at) }}</strong>                          
		                    </div>
	                    </div> 
	                    <div class="col-lg col-md-12 col">
		                    <label>{{ trans('backend.updated_on') }}</label>
		                    <div class="form-group">
		                        <strong>{{ date_formatted($info->updated_at) }}</strong> @ 
		                        <strong>{{ time_formatted($info->updated_at) }}</strong>                          
		                    </div>                    	
	                    </div>
                	</div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.status') }}
					<div class="float-right">{{ status_ico($info->post_status) }}</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', ['on-hold' => 'On-Hold'] + active_status(), Input::old('status', $info->post_status), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>

                    <div class="custom-control custom-checkbox align-items-center d-flex">
                        <input type="hidden" name="featured_product" value="0">
                        {{ Form::checkbox('featured_product', 1, @$info->featured_product, ['class' => 'custom-control-input', 'id' => 'featured_product']) }}
                        <label class="custom-control-label" for="featured_product">{{ trans('backend.featured_product') }}</label> 
                    </div>

                </div>
            </div>


            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">Membership
                </div>
                <div class="card-body">
                    <div class="custom-control custom-checkbox align-items-center d-flex">
                        <input type="hidden" name="premium" value="0">
                        {{ Form::checkbox('premium', 1, Input::old('premium', @$info->premium), ['class' => 'custom-control-input', 'id' => 'premium']) }}
                        <label class="custom-control-label" for="premium">Premium</label> 
                    </div>
                </div>
            </div>


            <div class="card mb-3">
                <input type="hidden" name="category" value="">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.categories') }}</div>
                <div class="card-body">
                    <div class="form-group">

		                <div class="mt-checkbox-list" style="overflow-y:auto; max-height:200px;">
			                <label>
			                    <input type="checkbox" value="0" name="category[]" {{ checked_in_array('0', json_decode($info->category)) }}> {{ trans('backend.uncategorised') }}
			                </label>
			                {!! checkbox_ordered_menu($categories, 0, json_decode($info->category)) !!}
		                </div>

                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.tags') }}</div>
                <div class="card-body">
                    <div class="form-group m-0">
                        <div class="form-control">
                            <textarea name="tags" class="tags-group" style="display:none;">{{ Input::old('tags', $info->tags) }}</textarea>                     
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.featured_image') }}</div>
                <div class="card-body">

                    <div class="media-single mb-2">
                    <input type="hidden" name="image" value="">
                    @if( $image = Input::old('image', $info->image) )
                    <li class="list-unstyled">
                        <div class="media-thumb img-thumbnail">
                        <img src="{{ asset($image) }}" class="img-fluid w-100">
                        <input type="hidden" name="image" value="{{ $image }}">
                        <a href="" class="delete-media"><i class="fas fa-trash"></i></a>
                        </div>
                    </li>
                    @endif
                    </div>

                    <button type="button" class="filemanager btn btn-sm btn-outline-primary btn-block" 
                    data-href="{{ route('backend.media.frame', ['format' => 'image', 'mode' => 'single', 'target' => '.media-single']) }}">{{ trans('backend.select_featured_image') }}</button>
                </div>
            </div>


            <div class="card">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.gallery') }}</div>
                <div class="card-body">

				    <div class="row media-gallery sortable">
				    <input type="hidden" name="gallery" value="">
				    @if( $galleries = Input::old('gallery', json_decode(@$info->gallery)) )
				        @foreach($galleries as $galery)
						<div class="m-list col-lg-4 col-sm-6 mb-4">
							<div class="media-thumb img-thumbnail">
								<img src="{{ has_image(str_replace('large', 'medium', $galery)) }}" class="img-fluid w-100">
								<input type="hidden" name="gallery[]" value="{{ $galery }}">
								<a href="" class="delete-media"><i class="fas fa-trash"></i></a>
							</div>
						</div>
				        @endforeach
				    @endif
				    </div>

		    		<button type="button"
		    		class="filemanager btn btn-sm btn-outline-primary mb-4" 
					data-href="{{ route('backend.media.frame', ['name' => 'gallery', 'format' => 'image', 'mode' => 'gallery-simple', 'target' => '.media-gallery']) }}">
					<i class="fas fa-plus"></i> {{ trans('backend.select') }}</button>

                </div>
            </div>


        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary mr-3">{{ trans('backend.save_changes') }}</button>     

        @if( $info->post_status == 'draft' )
        <a href="#" 
        class="text-danger" 
        data-url="{{ URL::route($view.'.delete', [$info->id, query_vars()]) }}"
        data-toggle="confirm-modal" 
        data-target=".confirm-modal" 
        data-title="{{ trans('backend.confirm_move_trash') }}" 
        data-body="{{ trans('messages.confirm_move_trash') }} ID: <b>#{{ $info->id }}</b>?"> {{ trans('backend.move_trash') }}</a>     
        @endif

    </div>
</form>

<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
<style>

.select2-container { width: 100% !important; }
.loader-ui {
    display: none;
    background: #343a40b0;
    position: absolute;
    z-index: 1;
    width: 100%;
    top: 0;
    bottom: 0;
    left: 0;  
}    	
.attributes-data .ui-state-highlight { 
	height: 4rem; 
	border: 1px dashed #9E9E9E;
	margin-bottom: 10px;
}
</style>
@stop

@section('plugin_script')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.min.css') }}">
<script type="text/javascript" src="{{ asset('plugins/select2/select2.min.js') }}"></script>

		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

@stop

@section('script')
<script>
$( function() {
	$( ".sortable" ).sortable({
		placeholder: "ui-state-highlight"
	});
	$( ".sortable" ).disableSelection();
});

$('.select2').select2();

$(document).on('click', '.btn-save-data', function(e) {
    e.preventDefault();
    var data = {
    	formData : $(this).closest('form'),
        url      : $(this).attr('href'),
        target   : $(this).attr('data-target'),
        type     : $(this).attr('data-type') 
    }
    form_save(data, true);

    if( $('.used_variation').is(':checked') || $(this).attr('data-type') == 'save-attributes' ) {
        var data = {
            formData : $(this).closest('form'),
            url      : $('[data-type="save-variations"]').attr('href'),
            target   : '.variations-data',
            type     : 'add-variations' 
        }
        setTimeout(function () { form_save(data); }, 1000);
    }
});

function form_save($data, $append ='') {
    var formData = $data.formData,
        url      = $data.url,
        target   = $data.target,
        type     = $data.type;

    $('.loader-ui').show().html($('.o-loader').html());
    $.ajax({
        url: url, 
        type: "POST",       
        data: new FormData(formData[0]), 
        headers: { 'type':type },
        contentType: false,    
        cache: false,       
        processData:false,     
        success: function(response)  
        {
            try {
                var data = JSON.parse(response);

            } catch(err){
            	if( $append ) {
	                $(target).append(response);
            	} else {
	                $(target).html(response);
            	}
            } 
            $('.select2').select2();

            $('.loader-ui').hide().html('');
            var attr_id = $('[name="attribute"]').val();
            if( attr_id != 'custom' ) {
                $('[name="attribute"] option[value="'+attr_id+'"]').attr('disabled', 'disabled');
                $('[name="attribute"]').val('custom');
            }
        }
    });

}



$(document).on('click', '.btn-select-all', function(e){
    e.preventDefault();
    $(this).closest('div').find('select').select2('destroy').find('option').prop('selected', 'selected').end().select2();
});
$(document).on('click', '.btn-select-none', function(e){
    e.preventDefault();
    $(this).closest('div').find('select').select2('destroy').find('option').prop('selected', false).end().select2();
});

$(document).on('click', '.btn-remove-attribute', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $(this).closest('section').slideUp(function(){
        $(this).remove();
    });
    $('[name="attribute"] option[value="'+id+'"]').removeAttr('disabled');
});
$(document).on('change', '[name="product_type"]', function(){
    if( $(this).val() == 'variable') {
        $('.variable-product').show().removeClass('active');
    } else {
        $('.variable-product').hide();
        $('.general-pill').trigger('click');        
    }
});

$(document).on('click', '.btn-set-variable', function(){
    var key = $('.variation_actions').val();
    if( key == 'variable_sale_date' ) {
        var start = window.prompt("Sale start date (MM-DD-YYYY format or leave blank)", "");
        var end = window.prompt("Sale end date (MM-DD-YYYY format or leave blank)", "");
        $('.variable_sale_date_start').val(start);
        $('.variable_sale_date_end').val(end);
    } else if ( key == 'variable_sku' ) {
        var value = window.prompt("Enter SKU", "");        
        $('.'+key).val(value);        
    } else {
        var value = window.prompt("Enter a numeric value", "");        
        $('.'+key).val(value);
    }
});

$(document).on('click', '.btn-add-extra', function(e) {
    e.preventDefault();

    var form = $('.form-extra-o').html();
    $('.form-extra').append( form );

    var index = $('.form-extra .form-group-extra').length;
    $('.form-extra .form-group-extra:last-child .input-f').each(function() {
        name = $(this).attr('name');
        name = name.replace(/\[[0-9]+\]/g, '['+index+']');
        $(this).attr('name',name);
    });
         
});

$(document).on('click', '.btn-remove-extra', function(e) {
    e.preventDefault();
    $(this).closest('.form-group-extra').remove();            
});

$(document).on('click', '[data-toggle="form"]', function() {
    var target = $(this).attr('data-target');
    $(target).slideToggle();          
});

</script>
@stop
