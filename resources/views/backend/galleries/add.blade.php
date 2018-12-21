@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.add_new') }}</span>

<div class="float-right">
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_galleries') }}</a>
</div>

<hr>
@stop

@section('content')
<form method="POST">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-lg-8 col-md-7">

            <div class="form-group">
                <input type="text" name="title" class="form-control form-control-lg" placeholder="{{ trans('backend.enter_title') }}" value="{{ Input::old('title') }}"> 
                {!! $errors->first('title','<p class="text-danger my-2">:message</p>') !!}                  
            </div>

            @if( App\Setting::get_setting('fsk18') )
            <div class="custom-control custom-checkbox mb-3">
                <input class="custom-control-input" id="check_all" name="" type="checkbox" value="1">
                <label class="custom-control-label" for="check_all">Mark all as FSK18</label> 
            </div>
            @endif

		    <div class="row media-gallery sortable">
		    <input type="hidden" name="gallery" value="">
		    @if( $gallery = Input::old('gallery') )
		        @foreach($gallery['slider'] as $slider_k => $slider_v)
				<div class="m-list col-lg-3 col-sm-6 mb-3 px-2">             
					<div class="media-thumb img-thumbnail">
						<img src="{{ has_image(str_replace('large', 'medium', $slider_v)) }}" class="img-fluid w-100">
						<input type="hidden" name="gallery[slider][{{ $slider_k }}]" value="{{ $slider_v }}">
						<input type="text" name="gallery[name][{{ $slider_k }}]" class="form-control" value="{{ $gallery['name'][$slider_k] }}">
                        <label class="mt-2 mb-0 fsk18">
                            <input type="hidden" name="gallery[fsk18][{{ $slider_k }}]" value="0"> 
                            {{ Form::checkbox('gallery[fsk18]['.$slider_k.']', '1', @$gallery['fsk18'][$slider_k], ['class' => 'checkboxes']) }}
                        Mark as FSK18</label>
						<a href="" class="delete-media"><i class="fas fa-trash"></i></a>
					</div>
				</div>
		        @endforeach
		    @endif
		    </div>

    		<button type="button"
    		class="filemanager btn btn-sm btn-outline-primary mb-4" 
			data-href="{{ route('backend.media.frame', ['name' => 'slider', 'format' => 'image', 'mode' => 'gallery', 'target' => '.media-gallery']) }}">
			<i class="fas fa-plus"></i> Select Image</button>

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
                        {{ Form::select('status', post_status(), Input::old('status'), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>
                </div>
            </div>



            <div class="card">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.featured_image') }}</div>
                <div class="card-body">

                    @if( App\Setting::get_setting('fsk18') )
                    <?php $fsk18 = Input::old('fsk18'); ?>
                    <div class="custom-control custom-checkbox align-items-center d-flex mb-2">
                        <input type="hidden" name="fsk18[featured_image]" value="">
                        {{ Form::checkbox('fsk18[featured_image]', 'featured_image', @$fsk18->featured_image, ['class' => 'custom-control-input', 'id' => 'fsk18_content']) }}
                        <label class="custom-control-label" for="fsk18_content">Mark as FSK18</label> 
                    </div>
                    @endif

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
<style>
@if( ! App\Setting::get_setting('fsk18') )
.fsk18 { display: none; }
@endif    
</style>
@stop

@section('plugin_script')
@stop

@section('script')
@stop
