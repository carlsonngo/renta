
<!-- Nav tabs -->
<ul class="nav nav-pills border nav-justified">
@foreach(languages() as $lang_k => $lang_v)
<li class="nav-item">
<a class="nav-link text-uppercase small {{ actived($lang_k, $lang) }}" data-toggle="tab" href="#{{ $lang_k }}"><img src="{{ asset('assets/img/flags/'.$lang_k.'.png') }}" class="mr-2 rounded-shadow mb-2 mb-md-0"> <span class="mt-2 mt-md-0">{{ $lang_v }}</span></a>
</li>
@endforeach
</ul>

<!-- Tab panes -->
<div class="tab-content mt-4">
    <div class="tab-pane {{ actived('en', $lang) }}" id="en">

    <div class="form-group">
        <input type="text" name="title" class="form-control form-control-lg" placeholder="{{ trans('backend.enter_title', [], 'en') }}" value="{{ Input::old('title', $info->post_title) }}"> 
        {!! $errors->first('title','<p class="text-danger my-2">:message</p>') !!}                  
    </div>

    @if( in_array($post_type, ['event', 'page']) )
    <div class="form-group">
        <input type="text" name="sub_title" class="form-control" placeholder="{{ trans('backend.enter_sub_title', [], 'en') }}" value="{{ Input::old('sub_title', $info->sub_title) }}"> 
    </div>
    @endif

    <button type="button" class="filemanager btn btn-outline-primary btn-sm mb-2"
    data-href="{{ route('backend.media.frame', ['mode' => 'editor', 'target' => 'en-content']) }}">
      {{ trans('backend.add_media', [], 'en') }}
    </button>

    <div class="form-group">
        <textarea name="content" class="form-control tinymce" id="en-content" rows="6">{{ Input::old('content', $info->post_content) }}</textarea> 
        {!! $errors->first('content','<p class="text-danger my-2">:message</p>') !!} 
    </div>

    </div>

    @foreach(array_except(languages(), ['en']) as $lang_k => $lang_v)
    <div class="tab-pane {{ actived($lang_k, $lang) }}" id="{{ $lang_k }}">

    <div class="form-group">
        <input type="text" name="{{ $lang_k }}_title" class="form-control form-control-lg" placeholder="{{ trans('backend.enter_title', [], $lang_k) }}" value="{{ Input::old($lang_k.'_title', $info->{$lang_k.'_title'}) }}"> 
        {!! $errors->first($lang_k.'_title','<p class="text-danger my-2">:message</p>') !!}      
    </div>

    @if( in_array($post_type, ['event', 'page']) )
    <div class="form-group">
        <input type="text" name="{{ $lang_k }}_sub_title" class="form-control" placeholder="{{ trans('backend.enter_sub_title', [], $lang_k) }}" value="{{ Input::old($lang_k.'_sub_title', $info->{$lang_k.'_sub_title'}) }}"> 
    </div>
    @endif

    <button type="button" class="filemanager btn btn-outline-primary btn-sm mb-2"
    data-href="{{ route('backend.media.frame', ['mode' => 'editor', 'target' => $lang_k.'-content']) }}">
      {{ trans('backend.add_media', [], $lang_k) }}
    </button>

    <div class="form-group">
        <textarea name="{{ $lang_k }}_content" class="form-control tinymce" id="{{ $lang_k }}-content" rows="6">{{ Input::old($lang_k.'_content', $info->{$lang_k.'_content'}) }}</textarea> 
    </div>
      
    </div>
    @endforeach
</div>