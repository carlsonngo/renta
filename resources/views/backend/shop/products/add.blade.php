@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.add_new') }}</span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_'.str_plural($post_type)) }}</a>
</div>

<hr>
@stop

@section('content')
<form method="POST">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-lg-8 col-md-7">


            <div class="form-group">
                <div class="input-group input-group-sm  align-items-center mb-3">
                    <div class="input-group-prepend  align-items-center">
                        <span class="mr-3">Permalink</span>
                        <span class="input-group-text d-none d-md-inline-block" id="basic-addon3">{{ url('/') }}/shop/</span>
                    </div>
                    <input type="text" name="slug" class="form-control no-space to-slug" data-type="slugy" value="{{ Input::old('slug') }}">
                    <a href="" class="ml-3">{{ trans('backend.view') }}</a>
                </div>
                {!! $errors->first('slug','<p class="text-danger my-2">:message</p>') !!}  
            </div>

            @include('backend.partials.content.add')

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.details') }}</div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Short Item Desciption</label>
                        <textarea name="short_description" class="form-control" rows="5">{{ Input::old('short_description') }}</textarea>
                        {!! $errors->first('short_description','<p class="text-danger my-2">:message</p>') !!}                  
                    </div>

                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ Input::old('sku') }}"> 
                        {!! $errors->first('sku','<p class="text-danger my-2">:message</p>') !!}                  
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>{{ trans('backend.regular_price') }} ({{ currency_symbol($currency) }})</label>
                                <input type="number" name="regular_price" class="form-control get-price" value="{{ Input::old('regular_price') }}"> 
                                {!! $errors->first('regular_price','<p class="text-danger my-2">:message</p>') !!}                  
                            </div>                            
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>{{ trans('backend.sale_price') }} ({{ currency_symbol($currency) }})</label>
                                <input type="number" name="sale_price" class="form-control get-price" value="{{ Input::old('sale_price') }}"> 
                                <input type="hidden" name="price" class="form-control" value="{{ Input::old('price') }}"> 
                                {!! $errors->first('sale_price','<p class="text-danger my-2">:message</p>') !!}                  
                            </div>                            
                        </div>
                    </div>

                    <h6>{{ trans('backend.sale_price_dates') }}</h6>
                    <div class="row">
                        <div class="col">
                            <label>{{ trans('backend.date_start') }}</label>  
                            <div class="input-group">
                                <input type="text" name="sale_date_start" class="form-control w-50 date-format" placeholder="mm-dd-yyyy" 
                                value="{{ Input::old('sale_date_start') }}">  
                                <input type="text" name="sale_time_start" class="form-control w-25 time-format" placeholder="00:00" 
                                value="{{ Input::old('sale_time_start') }}">  
                            </div>                            
                        </div>
                        <div class="col">
                            <label>{{ trans('backend.date_end') }}</label> 
                            <div class="input-group">
                                <input type="text" name="sale_date_end" class="form-control w-50 date-format" placeholder="mm-dd-yyyy" 
                                value="{{ Input::old('sale_date_end') }}">  
                                <input type="text" name="sale_time_end" class="form-control w-25 time-format" placeholder="00:00" 
                                value="{{ Input::old('sale_time_end') }}">  
                            </div>                            
                        </div>
                    </div>

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
                                <input type="text" name="meta_title" class="form-control" value="{{ Input::old('meta_title') }}">                    
                            </div>
                            <div class="form-group">
                                <label>Meta {{ trans('backend.keywords') }}</label>
                                <input type="text" name="meta_keywords" class="form-control" value="{{ Input::old('meta_keywords') }}">                    
                            </div>
                            <div class="form-group">
                                <label>Meta {{ trans('backend.description') }}</label>
                                <textarea name="meta_description" class="form-control" rows="6">{{ Input::old('meta_description') }}</textarea>                
                            </div>
                        </div>
                        <div class="tab-pane container p-0 fade" id="tab2">
                            <div class="form-group">
                                <label>Facebook {{ trans('backend.title') }}</label>
                                <input type="text" name="facebook_title" class="form-control" value="{{ Input::old('facebook_title') }}">  
                                <div class="text-muted mt-2">{{ trans('messages.social_title', ['variable' => 'facebook']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Facebook {{ trans('backend.description') }}</label>
                                <textarea name="facebook_description" class="form-control" rows="6">{{ Input::old('facebook_description') }}</textarea>
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
                                <input type="text" name="twitter_title" class="form-control" value="{{ Input::old('twitter_title') }}">  
                                <div class="text-muted mt-2">{{ trans('messages.social_title', ['variable' => 'twitter']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Twitter {{ trans('backend.description') }}</label>
                                <textarea name="twitter_description" class="form-control" rows="6">{{ Input::old('twitter_description') }}</textarea>
                                <div class="text-muted mt-2">{{ trans('messages.social_description', ['variable' => 'twitter']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Twitter {{ trans('backend.image') }}</label>  
                                <div class="text-muted mt-2">{{ trans('messages.social_image', ['variable' => 'twitter']) }}</div>
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

                    <label>{{ trans('backend.created_on') }}</label>
                    <div class="form-group">
                        <strong>{{ date_formatted(date('Y-m-d')) }}</strong> @ 
                        <strong>{{ time_formatted(date('Y-m-d H:i:s')) }}</strong>                          
                    </div>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.status') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', active_status(), Input::old('status'), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">Membership
                </div>
                <div class="card-body">
                    <div class="custom-control custom-checkbox align-items-center d-flex">
                        <input type="hidden" name="premium" value="0">
                        {{ Form::checkbox('premium', 1, Input::old('premium'), ['class' => 'custom-control-input', 'id' => 'premium']) }}
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
                                <input type="checkbox" value="0" name="category[]" {{ checked_in_array('0', Input::old('category', ['0'])) }}> {{ trans('backend.uncategorised') }}
                            </label>
                            {!! checkbox_ordered_menu($categories, 0, Input::old('category')) !!}
                        </div>

                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.tags') }}</div>
                <div class="card-body">
                    <div class="form-group m-0">
                    	<div class="form-control">
	                    	<textarea name="tags" class="tags-group" style="display:none;">{{ Input::old('tags') }}</textarea>                		
                    	</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.featured_image') }}</div>
                <div class="card-body">

					<div class="media-single mb-2">
						<input type="hidden" name="image" value="">
						@if( $image = Input::old('image') )
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
				    @if( $galleries = Input::old('gallery') )
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
					<i class="fas fa-plus"></i> Select Image</button>

                </div>
            </div>

        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ trans('backend.add_new') }}</button>                     
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
