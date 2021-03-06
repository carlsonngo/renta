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
                        <span class="input-group-text d-none d-md-inline-block" id="basic-addon3">{{ url('/') }}/</span>
                    </div>
                    <input type="text" name="slug" class="form-control no-space to-slug" data-type="slugy" value="{{ Input::old('slug') }}">
                    <a href="" class="ml-3">{{ trans('backend.view') }}</a>
                </div>
                {!! $errors->first('slug','<p class="text-danger my-2">:message</p>') !!}  
            </div>

            @include('backend.partials.content.add')

            @if( in_array($post_type, ['event']) )
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.details') }}</div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('backend.organizer') }}</label>
                                <input type="text" name="organizer" class="form-control" value="{{ Input::old('organizer') }}"> 
                            </div>                        
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('backend.location') }}</label>
                                <input type="text" name="location" class="form-control" value="{{ Input::old('location') }}"> 
                            </div>                          
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('backend.event_type') }}</label>
                                {{ Form::select('event_type', event_type(), $event_type = Input::old('event_type'), ['class' => 'form-control'] ) }}
                            </div>
                        </div>
                    </div>

                    <div class="row ticket {{ $event_type=='ticket'?'':'d-none' }}">
                        @foreach(attendees() as $att_k => $att_v)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('backend.amount') }} / {{ $att_v }}</label>
                                <input type="number" name="amount[{{ $att_k }}]" class="form-control" value="{{ Input::old('amount.women') }}"  min="0" step="any"> 
                            </div>                          
                        </div>
                        @endforeach

                    </div>


                </div>
            </div>
            @endif

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

                    @if($post_type=='event')
                    <label>{{ trans('backend.event_date') }}</label>
                    <div class="input-group">
                        <input type="text" name="date_start" class="form-control w-50 date-format datepicker" placeholder="mm-dd-yyyy" 
                        value="{{ Input::old('date_start') }}">  
                        <input type="text" name="time_start" class="form-control w-25 time-format timepicker" placeholder="00:00" 
                        value="{{ Input::old('time_start') }}">  
                    </div>
                    @endif

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.status') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('status', post_status(), Input::old('status'), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>

                    @if($post_type=='event')
                    <div class="custom-control custom-checkbox align-items-center d-flex">
                        <input type="hidden" name="allow_booking" value="0">
                        {{ Form::checkbox('allow_booking', 1, Input::old('allow_booking', 1), ['class' => 'custom-control-input', 'id' => 'allow_booking']) }}
                        <label class="custom-control-label" for="allow_booking">Enable Booking Form</label> 
                    </div>
                    @endif

                </div>
            </div>


            @if( App\Setting::get_setting('fsk18') )
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">Mark as FSK18</div>
                <div class="card-body">
                	<?php $fsk18 = Input::old('fsk18'); ?>
                    <div class="custom-control custom-checkbox align-items-center d-flex">
                        <input type="hidden" name="fsk18[featured_image]" value="">
                        {{ Form::checkbox('fsk18[featured_image]', 'featured_image', @$fsk18['featured_image'], ['class' => 'custom-control-input', 'id' => 'fsk18_featured_image']) }}
                        <label class="custom-control-label" for="fsk18_featured_image">{{ trans('backend.featured_image') }}</label> 
                    </div>
                    <div class="custom-control custom-checkbox align-items-center d-flex">
                        <input type="hidden" name="fsk18[content]" value="">
                        {{ Form::checkbox('fsk18[content]', 'content', @$fsk18['content'], ['class' => 'custom-control-input', 'id' => 'fsk18_content']) }}
                        <label class="custom-control-label" for="fsk18_content">Content</label> 
                    </div>

                </div>
            </div>
            @endif

            @if( in_array($post_type, ['post', 'event']) )
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
            @endif

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.template') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::select('template', theme_templates(), Input::old('template'), ['class' => 'form-control'] ) }}
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
<script type="text/javascript">
$(document).on('change', '[name=event_type]', function(){
    $('.ticket').toggleClass('d-none');
});  
</script>
@stop
