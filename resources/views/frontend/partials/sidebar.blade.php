@if( App\Setting::get_setting('google_translate') )
<aside class="bg-white mb-4 rounded">
  <div  class="pt-3 px-4">
      <div id="google_translate_element"></div>
  </div>
</aside>
@else 
  @if( App\Setting::get_setting('localization') )
  <aside class="bg-white mb-4 rounded">
    <div  class="pt-3 px-4">
      <label class="small text-uppercase">{{ trans('backend.select_language') }}</label>
      <div class="form-row align-items-center">
          <div class="col-auto">
              <?php $lang = Input::get('lang', App\Setting::get_setting('site_language')); ?>
              <img src="{{ asset('assets/img/flags/'.$lang.'.png') }}" width="25">                 
          </div>
          <div class="col">
              {{ Form::select('lang', languages(), $lang, ['class' => 'form-control form-control-sm switch-lang']) }}                
          </div>
      </div>
    </div>
    <hr>  
  </aside>
  @endif
@endif

<aside class="bg-white mb-4 rounded">

    <div class="pt-4 px-4">
      <h5 class="font-weight-bold text-uppercase">
        @if( App\Setting::get_setting('events_module') )
        {{ trans('backend.events') }}
        @else
        News
        @endif
      </h5>    
    </div>
    <hr class="dashed">

    <?php 
    $post_type = App\Setting::get_setting('events_module') ? 'event' : 'post';
    $rows = App\Post::where('post_type', $post_type)
                    ->site()
                    ->orderBy('id', 'DESC')
                    ->limit(6)
                    ->get();
    ?>

    @foreach($rows as $row)
    <div class="px-4">
      <div class="text-muted text-right my-2">{{ time_ago($row->created_at) }}</div>          
      <a href="{{ route('frontend.post', $row->post_name) }}">
        <strong>{{ trans_post($row, 'post_title', '_title') }}</strong>
       </a>
      <p class="mt-2">
        <?php $post_content = trans_post($row, 'post_content', '_content'); ?>
        {!! str_limit(strip_tags($post_content), 100, '...') !!}
      </p>
      <div class="text-right">
          @if( App\Setting::get_setting('events_module') )
          <a href="{{ route('frontend.post', ['events', $row->post_name]) }}" class="btn btn-sm btn-outline-primary">{{ trans('backend.read_more') }}</a>
          @else
          <a href="{{ route('frontend.post', $row->post_name) }}" class="btn btn-sm btn-outline-primary">{{ trans('backend.read_more') }}</a>
          @endif
      </div>
    </div>
    <hr class="dashed">
    @endforeach

    <div class="text-center pb-3">
        @if( App\Setting::get_setting('events_module') )
        <a href="{{ route('frontend.events.index') }}" class="btn-block btn-sm text-uppercase">
        {{ trans('backend.all_events') }}
        </a>
        @else
        <a href="{{ route('frontend.post', 'news') }}" class="btn-block btn-sm text-uppercase">
        All News
        </a>    
        @endif
    </div>
</aside>