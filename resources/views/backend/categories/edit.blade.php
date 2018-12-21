@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase  mr-3">{{ $label }}</span> 
<span class="badge text-uppercase p-2">/ {{ code_to_text($post_type) }}</span>

<a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark">
    <i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_categories') }}
</a>
<hr>
@stop

@section('content')
<form method="post" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}
    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.edit_category') }}</div>
                <div class="card-body">


            <!-- Nav tabs -->
            <ul class="nav nav-pills border nav-justified">
              @foreach(languages() as $lang_k => $lang_v)
              <li class="nav-item">
                <a class="nav-link text-uppercase {{ actived($lang_k, $lang) }}" data-toggle="tab" href="#{{ $lang_k }}"><img src="{{ asset('assets/img/flags/'.$lang_k.'.png') }}" class="mr-2"> {{ $lang_v }}</a>
              </li>
              @endforeach
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-4">
              <div class="tab-pane {{ actived('en', $lang) }}" id="en">

                    <div class="form-group">
                        <label>{{ trans('backend.name', [], 'en') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ Input::old('name', $info->post_title) }}" placeholder="e.g. {{ trans('backend.movie', [], 'en') }}">
                        <div class="mt-2 text-muted">{{ trans('messages.category_name', [], 'en') }}</div>
                        {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.plural_name', [], 'en') }}</label>
                        <input type="text" name="plural_name" class="form-control" value="{{ Input::old('plural_name', $info->plural_name) }}" placeholder="e.g. {{ trans('backend.movies', [], 'en') }}">
                        {!! $errors->first('plural_name','<span class="help-block text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.description', [], 'en') }}</label>
                        <textarea name="description" class="form-control" rows="5">{{ Input::old('description', $info->post_content) }}</textarea>
                        <div class="mt-2 text-muted">{{ trans('messages.category_description', [], 'en') }}</div>
      
                    </div>

              </div>

              @foreach( array_except(languages(), ['en']) as $lang_k => $lang_v)
              <div class="tab-pane {{ actived($lang_k, $lang) }}" id="{{ $lang_k }}">

                    <div class="form-group">
                        <label>{{ trans('backend.name', [], $lang_k) }}</label>
                        <input type="text" name="{{ $lang_k }}_name" class="form-control" value="{{ Input::old($lang_k.'_name', $info->{$lang_k.'_name'}) }}" placeholder="e.g. {{ trans('backend.movie', [], $lang_k) }}">
                        <div class="mt-2 text-muted">{{ trans('messages.category_name', [], $lang_k) }}</div>
                        {!! $errors->first($lang_k.'_name','<span class="help-block text-danger">:message</span>') !!}
                    </div>
                   <div class="form-group">
                        <label>{{ trans('backend.plural_name', [], $lang_k) }}</label>
                        <input type="text" name="{{ $lang_k }}_plural_name" class="form-control" value="{{ Input::old($lang_k.'_plural_name', $info->{$lang_k.'_plural_name'}) }}" placeholder="e.g. {{ trans('backend.movies', [], $lang_k) }}">
                        {!! $errors->first($lang_k.'_plural_name','<span class="help-block text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.description', [], $lang_k) }}</label>
                        <textarea name="{{ $lang_k }}_description" class="form-control" rows="5">{{ Input::old($lang_k.'_description', $info->{$lang_k.'_description'}) }}</textarea>
                        <div class="mt-2 text-muted">{{ trans('messages.category_description', [], $lang_k) }}</div>
 
                    </div>
                </div>
                @endforeach

            </div>


                    <div class="form-group">
                        <label>{{ trans('backend.slug') }}</label>
                        <input type="text" name="slug" class="form-control slug-field" value="{{ Input::old('slug', $info->post_name) }}">
                        <div class="mt-2 text-muted">{{ trans('messages.category_slug') }}</div>
                        {!! $errors->first('slug','<span class="help-block text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.parent_category') }}</label>
                        {{ Form::select('post_parent', ['' => 'Uncategorised'] + $post->select_posts(['post_type' => $post_type]), Input::old('post_parent', $info->post_parent), ['class' => 'form-control select2'] ) }}
                        <div class="mt-2 text-muted">{{ trans('messages.category_parent') }}</div>
                    </div>



                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-12">

                        <label>{{ trans('backend.image') }}</label>

                        <div class="media-single mb-2">
                        <input type="hidden" name="image" value="">
                        @if( $image = Input::old('image', $info->image) )
                        <li class="list-unstyled">
                            <div class="media-thumb img-thumbnail">
                            <img src="{{ asset(str_replace('large', 'medium', $image)) }}" class="img-fluid w-100">
                            <input type="hidden" name="image" value="{{ $image }}">
                            <a href="" class="delete-media"><i class="fas fa-trash"></i></a>
                            </div>
                        </li>
                        @endif
                        </div>

                        <button type="button" class="filemanager btn btn-sm btn-outline-primary" 
                        data-href="{{ route('backend.media.frame', ['format' => 'image', 'mode' => 'single', 'target' => '.media-single']) }}">{{ trans('backend.select_featured_image') }}</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
                        {{ Form::select('status', active_status(), Input::old('status', $info->post_status), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>
                </div>
            </div>

            @if( in_array($info->post_type, ['product-attribute']) )
            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.terms') }}
                </div>
                <div class="card-body">

                    <div class="custom-control custom-checkbox align-items-center d-flex">
                        <input type="hidden" name="is_color" value="0">
                        {{ Form::checkbox('is_color', 1, Input::old('is_color', @$info->is_color), ['class' => 'custom-control-input', 'id' => 'color']) }}
                        <label class="custom-control-label" for="color">{{ trans('backend.color_picker') }}</label> 
                    </div>

                    <table class="w-100">
                        <thead>
                        <tr>
                            <th width="1" class="col-color">{{ trans('backend.color') }}</th>
                            <th>{{ trans('backend.name') }}</th>
                            <th width="30"></th>
                        </tr>                            
                        </thead>
                        <tbody>
                        <?php $t=0;?>
                        @if(@$info->term)
                        @foreach(json_decode($info->term) as $term)
                        <tr>
                            <td class="col-color">
                                <div>
                                    <span class="color-picker" style="background:{{ $term->color }};"></span>
                                    <input type="hidden" name="term[{{ $t }}][color]" class="form-control" value="{{ $term->color }}">                                    
                                </div>
                            </td>
                            <td><input type="text" name="term[{{ $t }}][name]" class="form-control" value="{{ $term->name }}"></td>
                            <td class="text-center align-middle">
                                <a href="" class="text-danger remove-term"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>   
                        <?php $t++;?>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                    <hr>
                    <a href="" class="btn btn-outline-primary btn-add-term btn-sm"><i class="fa fa-plus"></i> {{ trans('backend.add_term') }}</a>
                </div>
            </div>
            @endif


      
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary mr-3" type="submit"> {{ trans('backend.save_changes') }}</button>
        @if( $info->post_status == 'inactived' )
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

@include('backend.partials.media-modal')

@endsection

@section('style')
<style>
@if(!Input::old('is_color', @$info->is_color))
.col-color { display: none; }
@endif
.color-picker {
    cursor: pointer;
    display: inline-block;
    width: 37px;
    height: 37px;
    border: 1px solid;
    border-radius: 5px;
    background: url({{ asset('assets/img/transparent-bg.png') }});
    background-size: 250%;
}    
</style>
@stop

@section('plugin_script') 
<link rel="stylesheet" media="screen" type="text/css" href="{{ asset('plugins/colorpicker/css/colorpicker.css') }}" />
<script type="text/javascript" src="{{ asset('plugins/colorpicker/js/colorpicker.js') }}"></script>
@stop

@section('script')
<script type="text/javascript">

function init_colorpicker() {
    var $pickerInput;
    $('.color-picker').ColorPicker({
        color: '#0000ff',
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            $pickerInput = $(this);
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            var hex = $(colpkr).find('.colorpicker_hex input').val();
            $pickerInput.closest('div').find('input').val('#' + hex);
            $pickerInput.closest('div').find('span').css('background', '#' + hex);
            return false;
        },
    });      
}    
init_colorpicker();

$(document).on('click', '[name=is_color]', function(){
    $('.col-color').toggle();
});
$(document).on('click', '.remove-term', function(e){
    e.preventDefault();
    $(this).closest('tr').hide('fast', function(){
        $(this).remove();
    });
});
$(document).on('click', '.btn-add-term', function(e){
    e.preventDefault();
    var term = $('tbody tr').length;
    var row = '<tr><td class="col-color"><div>'
            + '<span class="color-picker"></span>'
            + '<input type="hidden" name="term['+term+'][color]" class="form-control"></div></td>'
            + '<td><input type="text" name="term['+term+'][name]" class="form-control"></td>'
            + '<td class="text-center align-middle">'
            + '<a href="" class="text-danger remove-term"><i class="fa fa-times"></i></a></td></tr>';  
    $('tbody').append(row);

    if( $('[name=is_color]').is(':checked') ) {
       $('.col-color').show();
    }

    init_colorpicker();
});
</script>
@stop