@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.settings') }}</span>
<hr>
@stop

@section('content')
<form method="GET">
<div class="bg-white border mb-3 p-2">
    <div class="input-group d-flex align-items-center">
        <span class="mr-2">{{ trans('messages.select_menu_edit') }}:</span>
        {{ Form::select('menu', menus(), Input::get('menu', 'header'), ['class' => 'form-control', 'style' => 'min-width: 200px;max-width: 200px;'] ) }}

        <button class="btn btn-primary mx-2">{{ trans('backend.select') }}</button>

    </div>
</div>	
</form>

<div class="row">
    <div class="col-lg-4 col-md-6">


    	@foreach($menus as $post_k => $posts)
        <div class="card mb-3">
            <div class="card-header bg-dark text-white text-uppercase" data-event="collapse" data-target=".m-{{ $post_k }}">
                <div class="row">
                    <div class="col-6">{{ code_to_text($post_k) }}</div>
                    <div class="col-6 text-right">
                        <i class="fa fa-caret-down"></i>                
                    </div>
                </div>
            </div>
            <div class="card-body e-collapse m-{{ $post_k }}" style="{{ $post_k=='pages'?'':'display: none;' }}">
                <input type="text" name="s" class="form-control" placeholder="{{ trans('backend.enter_search') }}">

                <form method="POST" action="{{ route('backend.settings.menus-add') }}" data-request="ajax-add-menu">
                {{ csrf_field() }}
    
                    <input type="hidden" name="type" value="post">              
                    <div class="my-3 form-checkbox">
                        @foreach($posts as $post)
                        <div class="form-check" data-val="{{ $post->post_title }}">
                            <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="menu-id[]" value="{{ $post->id }}"> {{ $post->post_title }}</label>
                        </div>
                        @endforeach
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <a href="#">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input select-all d-none">
                            {{ trans('backend.select_all') }}           
                            </label>            
                            </a>           
                        </div>
                        <div class="col-6 text-right">
                            <button type="submit" class="btn btn-outline-primary btn-sm" name="add-post-to-menu">{{ trans('backend.add_to_menu') }}</button>                
                        </div>
                    </div>
                </form>

            </div>
        </div>
        @endforeach


        <div class="card">
            <div class="card-header bg-dark text-white text-uppercase" data-event="collapse" data-target=".m-links">
                <div class="row">
                    <div class="col-6">{{ trans('backend.custom_links') }}</div>
                    <div class="col-6 text-right">
                        <i class="fa fa-caret-down"></i>                
                    </div>
                </div>
            </div>
            <div class="card-body e-collapse m-links" style="display: none;">
                <form method="POST" action="{{ route('backend.settings.menus-add') }}" data-request="ajax-add-menu">
                    {{ csrf_field() }}

                    <input type="hidden" name="type" value="custom-link">
                    <div class="form-group">
                        <label>URL</label>
                        <input type="text" name="value" class="form-control" placeholder="http://">                 
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.link_text') }}</label>
                        <input type="text" name="text" class="form-control"">                 
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-outline-primary btn-sm" name="add-link-to-menu">{{ trans('backend.add_to_menu') }}</button>                
                        </div>
                    </div>                  
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8  col-md-6">
        <form method="POST" action="{{ route('backend.settings.menus-save') }}" data-request="ajax-save-menu">
        <input type="hidden" name="id" value="{{ @$info->id }}">
        {{ csrf_field() }}

        <div class="card">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.menu_structure') }}</div>
            <div class="bg-light border-bottom p-2">
                <div class="row">
                    <div class="col-9">
                        <div class="input-group">
                        	<input type="hidden" name="name" value="{{ $menu_name }}">
                            <h5 class="mt-2 ml-2">{{ trans('backend.'.$menu_name) }}</h5>                                                    
                        </div>
                    </div>
                    <div class="col-3 text-right">
                        <button type="submit" class="btn btn-primary float-right">{{ trans('backend.save_menu') }}</button>                           
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ trans('messages.menu_help') }}</p>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="dd">
                        	<ol class="dd-list">
                            @if( @$info->post_content )
                            	@each('backend.settings.menus.menu',  json_decode($info->post_content), 'menu')
                            @endif
							</ol>   
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light border-top">
                <div class="row align-items-center p-2">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary float-right">{{ trans('backend.save_menu') }}</button>                           
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="mb-5 pb-4"></div>

@endsection

@section('style')

<style type="text/css">
/**
 * Nestable
 */

.dd {
    position: relative;
    display: block;
    margin: 0;
    padding: 0;
    max-width: 100%;
    list-style: none;
    font-size: 13px;
    line-height: 20px;
}

.dd-list {
    display: block;
    position: relative;
    margin: 0;
    padding: 0;
    list-style: none;
}

.dd-list .dd-list {
    padding-left: 30px;
}

.dd-collapsed .dd-list {
    display: none;
}

.dd-item,
.dd-empty,
.dd-placeholder {
    display: block;
    position: relative;
    margin: 0;
    padding: 0;
    min-height: 20px;
    font-size: 13px;
    line-height: 20px;
}

.dd-handle {
    display: block;
    height: 40px;
    padding: 9px 15px;
    margin: 7px 0;
    color: #333;
    text-decoration: none;
    font-weight: bold;
    border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background: linear-gradient(top, #fafafa 0%, #eee 100%);
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    cursor: all-scroll;
}

.dd-handle:hover {
    color: #2ea8e5;
    background: #fff;
}

.dd-item>button {
    display: block;
    position: relative;
    cursor: pointer;
    float: left;
    width: 25px;
    height: 20px;
    margin: 11px 0;
    padding: 0;
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
    border: 0;
    background: transparent;
    font-size: 12px;
    line-height: 1;
    text-align: center;
    font-weight: bold;
}

.dd-item>button:before {
    content: '+';
    display: block;
    position: absolute;
    width: 100%;
    text-align: center;
    text-indent: 0;
}

.dd-item>button[data-action="collapse"]:before {
    content: '-';
}

.dd-placeholder,
.dd-empty {
    margin: 5px 0;
    padding: 0;
    min-height: 30px;
    background: #f2fbff;
    border: 1px dashed #b6bcbf;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}

.dd-empty {
    border: 1px dashed #bbb;
    min-height: 100px;
    background-color: #e5e5e5;
    background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image: -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image: linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-size: 60px 60px;
    background-position: 0 0, 30px 30px;
}

.dd-dragel {
    position: absolute;
    pointer-events: none;
    z-index: 9999;
}

.dd-dragel>.dd-item .dd-handle {
    margin-top: 0;
}

.dd-dragel .dd-handle {
    -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
    box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
}


/**
 * Nestable Extras
 */

.nestable-lists {
    display: block;
    clear: both;
    padding: 30px 0;
    width: 100%;
    border: 0;
    border-top: 2px solid #ddd;
    border-bottom: 2px solid #ddd;
}

#nestable-menu {
    padding: 0;
    margin: 20px 0;
}

#nestable-output,
#nestable2-output {
    width: 100%;
    height: 7em;
    font-size: 0.75em;
    line-height: 1.333333em;
    font-family: Consolas, monospace;
    padding: 5px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}

#nestable2 .dd-handle {
    color: #fff;
    border: 1px solid #999;
    background: #bbb;
    background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
    background: -moz-linear-gradient(top, #bbb 0%, #999 100%);
    background: linear-gradient(top, #bbb 0%, #999 100%);
}

#nestable2 .dd-handle:hover {
    background: #bbb;
}

#nestable2 .dd-item>button:before {
    color: #fff;
}

.dd-hover>.dd-handle {
    background: #2ea8e5 !important;
}


.dd-item {
    position: relative;
}
.sort-icon {
    color: #19191b;
    right: 3px;
    position: absolute;
    z-index: 1;
    top: 3px;
    padding: 6px 0 0 2px;
    height: 33px;
    width: 33px;
    text-align: center;
}    
.form-checkbox {
    height: 150px;
    overflow-y: auto;
}
[data-event="collapse"] {cursor: pointer;}
[data-event="collapse"]:hover {
    background-color: #007bff !important;
}
</style>

@stop

@section('plugin_script')
@stop

@section('script')

<script src="{{ asset('plugins/nestable/jquery.nestable.js') }}"></script>

<script>
$('.dd').nestable({'maxDepth': 2});

$(document).on('click', '[data-event="collapse"]', function() {
    var target = $(this).data('target');
    if( $(target).is(':hidden') ) {
        $('.e-collapse').slideUp('fast');
        $(target).slideDown('fast');
    }
});

$(document).on('click', '.remove-menu', function(e) {
    e.preventDefault();
    $(this).closest('.dd-item').slideUp('slow').delay(500)
    .queue(function() {
        $(this).remove();
    });
});

$('[data-request="ajax-save-menu"]').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this),
        url      = formData.attr('action'),
        id       = $('[name="id"]').val(),
        name     = $('[name="name"]').val(),
        data     = $('.dd').nestable('serialize'),
        token    = $('meta[name="csrf-token"]').attr('content');

    $('.o-loader').show();

    $.post(url, { 
        'data'   : data, 
        'id'     : id, 
        'name'   : name, 
        '_token' : token 
    }, function(response){
        console.log(response);
        $('.o-loader').delay(2000).fadeOut(0);
    });
});

$('[data-request="ajax-add-menu"]').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this),
        url      = formData.attr('action');

    var sa = formData.serializeArray();
    var nsa = $.map( sa, function(v){
      if( v.name != 'type' ) {
          return v.value === "" ? null : v;
      }
    });

    if( formData.find('[name=type]').val() == 'custom-link') {
        if( nsa.length < 2 ) return false;
    } else {
        if( ! nsa.length ) return false;
    }

    $.ajax({
        url: url,
        type: "POST",         
        data: new FormData(this), 
        // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,    
        cache: false,         
        processData:false,     
        success: function(response)   
        {
            $('.dd > .dd-list').append(response);
            formData[0].reset();
        }
    });
});

$(document).on('keyup', '[name="label"]', function() {
    var label = $(this).val();
    $(this).closest('.dd-item').find('.dd-label:first').html(label);
    $(this).closest('.dd-item').attr('data-label', label);
}); 

$(document).on('keyup', '[name="value"]', function() {
    var value = $(this).val();
    $(this).closest('.dd-item').attr('data-value', value);
}); 

$(document).on('keyup', '[name="s"]', function() {
    $(this).closest('.card-body').find('.form-check').hide();
    var txt = $(this).val();
    $(this).closest('.card-body').find('.form-check').each(function(){
        if( $(this).attr('data-val').toUpperCase().indexOf(txt.toUpperCase()) != -1){
           $(this).show();
        }
    });
}); 

$(".select-all").change(function (e) {
    $(this).closest('.card-body').find(".form-check-input").prop("checked", this.checked);
});   
</script>
@stop
