<div class="row">
    <div class="col-md-8">

        <div class="card mb-4">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.general_settings') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('backend.site_title') }}</label>
                            <input type="text" name="site_title" class="form-control" value="{{ Input::old('site_title', @$setting->site_title) }}">                    
                        </div>
                        <div class="form-group">
                            <label>{{ trans('backend.footer_title') }}</label>
                            <input type="text" name="footer_title" class="form-control" value="{{ Input::old('footer_title', @$setting->footer_title) }}">                    
                        </div>
                        <div class="form-group no-space">
                            <label>{{ trans('backend.admin_email') }}</label>
                            <input type="email" name="admin_email" class="form-control no-space" value="{{ Input::old('admin_email', @$setting->admin_email) }}">                    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('backend.site_language') }}</label>
                            {{ Form::select('site_language', languages(), Input::old('site_language', @$setting->site_language), ['class' => 'form-control'] ) }}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('backend.current_theme') }}</label>
                            {{ Form::select('current_theme', themes(), Input::old('current_theme', @$setting->current_theme), ['class' => 'form-control'] ) }}
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.enabled_modules') }}</div>
            <div class="card-body">
                <div class="form-group">

                    @foreach(site_modules() as $module)
                    <div class="custom-control custom-checkbox align-items-center d-flex">
                    <input type="hidden" name="{{ $module['name'] }}" value="0">
                    {{ Form::checkbox($module['name'], 1, @$setting->{$module['name']}, ['class' => 'custom-control-input', 'id' => $module['name']]) }} 
                    <label class="custom-control-label" for="{{ $module['name'] }}">{!! $module['label'] !!}</label>
                    </div>
                    @endforeach

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
                    <input type="text" name="meta_title" class="form-control" value="{{ Input::old('meta_title', @$setting->meta_title) }}">                    
                </div>
                <div class="form-group">
                    <label>Meta {{ trans('backend.keywords') }}</label>
                    <input type="text" name="meta_keywords" class="form-control" value="{{ Input::old('meta_keywords', @$setting->meta_keywords) }}">                    
                </div>
                <div class="form-group">
                    <label>Meta {{ trans('backend.description') }}</label>
                    <textarea name="meta_description" class="form-control" rows="6">{{ Input::old('meta_description', @$setting->meta_description) }}</textarea>                
                </div>
            </div>
            <div class="tab-pane container p-0 fade" id="tab2">
                <div class="form-group">
                    <label>Facebook {{ trans('backend.title') }}</label>
                    <input type="text" name="facebook_title" class="form-control" value="{{ Input::old('facebook_title', @$setting->facebook_title) }}">  
                    <div class="text-muted mt-2">{{ trans('messages.social_title', ['variable' => 'facebook']) }}</div>
                </div>
                <div class="form-group">
                    <label>Facebook {{ trans('backend.description') }}</label>
                    <textarea name="facebook_description" class="form-control" rows="6">{{ Input::old('facebook_description', @$setting->facebook_description) }}</textarea>
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
                    <input type="text" name="twitter_title" class="form-control" value="{{ Input::old('twitter_title', @$setting->twitter_title) }}">  
                    <div class="text-muted mt-2">{{ trans('messages.social_title', ['variable' => 'twitter']) }}</div>
                </div>
                <div class="form-group">
                    <label>Twitter {{ trans('backend.description') }}</label>
                    <textarea name="twitter_description" class="form-control" rows="6">{{ Input::old('twitter_description', @$setting->twitter_description) }}</textarea>
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

