@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase  mr-3">{{ $label }}</span> 
<span class="badge text-uppercase p-2">/ {{ code_to_text($post_type) }}</span>
<hr>
@stop

@section('content')

<div class="row mb-5">
    <div class="col-md-4">    

        <div class="card mb-3">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.add_new_category') }}</div>
            <div class="card-body">

            <form method="post" enctype="multipart/form-data" action="{{ URL::route($view.'.add', query_vars()) }}">

                {{ csrf_field() }}


            <!-- Nav tabs -->
            <ul class="nav nav-pills border nav-justified">

              @foreach(languages() as $lang_k => $lang_v)
              <li class="nav-item">
                <a class="nav-link text-uppercase small text-center {{ actived($lang_k, $lang) }}" data-toggle="tab" href="#{{ $lang_k }}">
                    <img src="{{ asset('assets/img/flags/'.$lang_k.'.png') }}" class="mb-2"> 
                {{ $lang_v }}</a>
              </li>
              @endforeach
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-4">
              <div class="tab-pane {{ actived('en', $lang) }}" id="en">

                <div class="form-group">
                    <label>{{ trans('backend.name', [], 'en') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ Input::old('name') }}" data-type="slug" data-slug=".to-slug" placeholder="e.g. {{ trans('backend.movie', [], 'en') }}">
                    <div class="mt-2 text-muted">{{ trans('messages.category_name', [], 'en') }}</div>
                    {!! $errors->first('name','<span class="text-danger">:message</span>') !!}                    
                </div>

                    <div class="form-group">
                        <label>{{ trans('backend.plural_name', [], 'en') }}</label>
                        <input type="text" name="plural_name" class="form-control" value="{{ Input::old('plural_name') }}" placeholder="e.g. {{ trans('backend.movies', [], 'en') }}">
                        {!! $errors->first('plural_name','<span class="help-block text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.description', [], 'en') }}</label>
                        <textarea name="description" class="form-control" rows="5">{{ Input::old('description') }}</textarea>
                        <div class="mt-2 text-muted">{{ trans('messages.category_description', [], 'en') }}</div>
      
                    </div>

              </div>

              @foreach(array_except(languages(), ['en']) as $lang_k => $lang_v)
              <div class="tab-pane {{ actived($lang_k, $lang) }}" id="{{ $lang_k }}">

                    <div class="form-group">
                        <label>{{ trans('backend.name', [], $lang_k) }}</label>
                        <input type="text" name="{{ $lang_k }}_name" class="form-control" value="{{ Input::old($lang_k.'_name') }}" placeholder="e.g. {{ trans('backend.movie', [], $lang_k) }}">
                        <div class="mt-2 text-muted">{{ trans('messages.category_name', [], $lang_k) }}</div>
                        {!! $errors->first($lang_k.'_name','<span class="help-block text-danger">:message</span>') !!}
                    </div>
                   <div class="form-group">
                        <label>{{ trans('backend.plural_name', [], $lang_k) }}</label>
                        <input type="text" name="{{ $lang_k }}_plural_name" class="form-control" value="{{ Input::old($lang_k.'_plural_name') }}" placeholder="e.g. {{ trans('backend.movies', [], $lang_k) }}">
                        {!! $errors->first($lang_k.'_plural_name','<span class="help-block text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('backend.description', [], $lang_k) }}</label>
                        <textarea name="{{ $lang_k }}_description" class="form-control" rows="5">{{ Input::old($lang_k.'_description') }}</textarea>
                        <div class="mt-2 text-muted">{{ trans('messages.category_description', [], $lang_k) }}</div>
 
                    </div>
                  
              </div>
              @endforeach

            </div>

                
                <div class="form-group">
                    <label>{{ trans('backend.slug') }}</label>
                    <input type="text" name="slug" class="form-control to-slug" data-type="slugy" value="{{ Input::old('slug') }}">
                    <div class="mt-2 text-muted">{{ trans('messages.category_slug') }}</div>
                    {!! $errors->first('slug','<span class="text-danger">:message</span>') !!}
                </div>


                <div class="form-group">

                    <div class="media-single mb-2">
                    <input type="hidden" name="image" value="">
                    @if( $image = Input::old('image') )
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

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">{{ trans('backend.add_new_category') }}</button>                            
                </div>

            </form>

            </div>
        </div>
    </div>
    <div class="col-md-8">

        <form method="get">
        @include('backend.partials.category-search')
        <div class="card mb-3">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.categories') }}</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover border">
                        <thead>
                            <tr>
                                <th width="1" class="align-middle py-0">
                                    <div class="custom-control custom-checkbox text-center" style="margin-left: 12px;">
                                        <input class="custom-control-input" id="check_all" name="" type="checkbox" value="1">
                                        <label class="custom-control-label" for="check_all"></label> 
                                    </div>
                                </th>
                                <th width="1"></th>
                                <th width="400">{{ trans('backend.name') }}</th>
                                @if($localization = App\Setting::get_setting('localization'))
                                <th width="150" class="text-center">{{ trans('backend.translation') }}</th>
                                @endif
                                <th width="130" class="text-right">{{ trans('backend.count') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $row)
                            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
                            <tr class="has-actions">
                                <td class="align-middle text-center">
                                    <div class="custom-control custom-checkbox text-center ml-2">
                                        <input class="custom-control-input checkboxes" id="c-{{ $row->id }}" name="ids[]" type="checkbox" value="{{ $row->id }}">
                                        <label class="custom-control-label" for="c-{{ $row->id }}"></label> 
                                    </div>
                                </td>
                                <td class="p-0 text-center align-middle" style="width:50px;">
                                    <a href="{{ has_image($postmeta->image) }}" class="btn-img-preview" data-title="{{ $row->post_title }}">
                                    <img src="{{ has_image(str_replace('large', 'thumb', $postmeta->image)) }}" class="img-fluid img-icon-md"> 
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ URL::route($view.'.edit', [$row->id, 'post_type' => $post_type]) }}" class="text-dark">{{ $row->post_title }}</a>
                                    <div class="table-actions small text-uppercase mt-2">
                                        <span class="text-muted">ID : {{ $row->id }}</span> | 
                                        @if( Input::get('type') == 'trash' )
                                        <a href="#" class="popup"
                                            data-url="{{ URL::route($view.'.restore', [$row->id, query_vars()]) }}" 
                                            data-toggle="confirm-modal" 
                                            data-target=".confirm-modal" 
                                            data-title="{{ trans('backend.confirm_restore') }}"
                                            data-body="{{ trans('backend.confirm_restore') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.restore') }}</a> | 
                                        <a href="#" class="text-danger"
                                            data-url="{{ URL::route($view.'.destroy', [$row->id, query_vars()]) }}" 
                                            data-toggle="confirm-modal" 
                                            data-target=".confirm-modal" 
                                            data-title="{{ trans('backend.confirm_delete_permanently') }}"
                                            data-body="{{ trans('backend.confirm_delete_permanently') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.delete_permanently') }}</a>
                                        @else
                                        <a href="{{ URL::route($view.'.edit', [$row->id, 'post_type' => $post_type]) }}">{{ trans('backend.edit') }}</a> |   

                                        <a href="#" class="text-danger"
                                            data-url="{{ URL::route($view.'.delete', [$row->id, query_vars()]) }}" 
                                            data-toggle="confirm-modal" 
                                            data-target=".confirm-modal" 
                                            data-title="{{ trans('backend.confirm_move_trash') }}"
                                            data-body="{{ trans('messages.confirm_move_trash') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.move_trash') }}h</a>

                                        @endif
                                    </div>
                                </td>

                                @if( $localization )
					            <td  class="align-middle text-center">
					                @if( $row->post_title )
					                <img src="{{ asset('assets/img/flags/en.png') }}" class="rounded-shadow mb-2 mx-1" data-toggle="tooltip" title="English" width="25">
					                @endif

                                    @foreach( array_except(languages(), ['en']) as $lang_k => $lang_v)
                                        @if( @$postmeta->{$lang_k.'_name'} )                
                                        <img src="{{ asset('assets/img/flags/'.$lang_k.'.png') }}" class="rounded-shadow mb-2 mx-1" data-toggle="tooltip" title="{{ $lang_v }}" width="25">
                                        @endif
                                    @endforeach
					            </td>
                                @endif

                                <td class="text-right">{{  $row->categoryCount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if( ! count($rows) )
                    <div class="alert alert-warning">No {{ strtolower(code_to_text($post_type)) }} found.</div>
                    @else
                    {{ $rows->appends(['post_type' => Input::get('post_type')])->links() }}
                    @endif
                </div>
            </div>
        </div>
        </form>

    </div>    
</div>

@include('backend.partials.media-modal')
@include('backend.partials.preview-modal')

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
$(document).on('click', '.btn-submit', function(){ 
    $('#form-category').submit();
});
</script>
@stop
