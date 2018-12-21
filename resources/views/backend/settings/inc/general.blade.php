
<div class="row">
    <div class="col-lg-9">

        <div class="card mb-4">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.general_settings') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('backend.site_title') }}</label>
                            <input type="text" name="site_title" class="form-control" value="{{ @$info->site_title }}">                    
                        </div>
                        <div class="form-group">
                            <label>{{ trans('backend.footer_title') }}</label>
                            <input type="text" name="footer_title" class="form-control" value="{{ @$info->footer_title }}">                    
                        </div>
                        <div class="form-group">
                            <label>{{ trans('backend.admin_email') }}</label>
                            <input type="email" name="admin_email" class="form-control no-space" value="{{ @$info->admin_email }}">                    
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group site-lang" style="{{ @$info->localization ? '' : 'display:none;' }}">
                            <label>{{ trans('backend.site_language') }}</label>
                            {{ Form::select('site_language', site_languages(), @$info->site_language, ['class' => 'form-control'] ) }}
 
                        </div>

                        <div class="form-group">
                            <label>{{ trans('backend.current_theme') }}</label>
                            {{ Form::select('current_theme', themes(), @$info->current_theme, ['class' => 'form-control'] ) }}
                        </div>     
                        <div class="form-group">
                            <label>Front Page</label>
                            {{ Form::select('front_page', theme_templates('frontend/templates/front-page'), @$info->front_page, ['class' => 'form-control'] ) }}
                        </div>     

                    </div>

                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-dark text-white text-uppercase">SMTP</div>
            <div class="card-body no-space">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Encryption</label>
                            {{ Form::select('mail_encryption', mail_encryption(), @$info->mail_encryption, ['class' => 'form-control'] ) }}
                    
                        </div>
                        <div class="form-group">
                            <label>Host</label>
                            <input type="text" name="mail_host" class="form-control" value="{{ @$info->mail_host }}">                    
                        </div>
                        <div class="form-group">
                            <label>Port</label>
                            <input type="text" name="mail_port" class="form-control" value="{{ @$info->mail_port }}">                    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('backend.username') }}</label>
                            <input type="text" name="mail_username" class="form-control" value="{{ @$info->mail_username }}">
 
                        </div>
                        <div class="form-group">
                            <label>{{ trans('backend.password') }}</label>
                            <input type="password" name="mail_password" class="form-control" value="{{ @$info->mail_password }}">
                        </div>     
                                         
                    </div>

                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-header bg-dark text-white text-uppercase">Bank Accounts</div>
            <div class="card-body">
                <table class="table-ba-copy d-none">
                    <?php $b = 0; ?>
                    <tbody class="table-ba-body">
                        @include('backend.settings.bank-account')        
                    </tbody>
                </table>

                <div class="table-responsive">
                <table class="table-ba mb-3" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Account Name</th>
                        <th>Account Number</th>
                        <th>Bank Name</th>
                        <th>Bank Address</th>
                        <th>IBAN</th>
                        <th>BIC / Swift</th>
                        <th>Location Address</th> 
                        <th></th>       
                    </tr>    
                </thead>
                <tbody class="table-ba-body sortable">
                    <?php 
                        $b = 1; 
                        $bas = Input::old('back_account', json_decode(@$info->bank_account, true));
                    ?>
                    @if( $bas )
                        @foreach($bas as $ba)
                        @include('backend.settings.bank-account')
                        <?php $b++; ?>
                        @endforeach
                    @else
                        @include('backend.settings.bank-account')
                    @endif
                </tbody>
                </table>
                </div>
                
                <a href="" class="btn btn-sm btn-outline-primary btn-add-ba" data-target=".table-ba">Add More</a>
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
                                <input type="text" name="meta_title" class="form-control" value="{{ Input::old('meta_title', @$info->meta_title) }}">                    
                            </div>
                            <div class="form-group">
                                <label>Meta {{ trans('backend.keywords') }}</label>
                                <input type="text" name="meta_keywords" class="form-control" value="{{ Input::old('meta_keywords', @$info->meta_keywords) }}">                    
                            </div>
                            <div class="form-group">
                                <label>Meta {{ trans('backend.description') }}</label>
                                <textarea name="meta_description" class="form-control" rows="6">{{ Input::old('meta_description', @$info->meta_description) }}</textarea>                
                            </div>
                        </div>
                        <div class="tab-pane container p-0 fade" id="tab2">
                            <div class="form-group">
                                <label>Facebook {{ trans('backend.title') }}</label>
                                <input type="text" name="facebook_title" class="form-control" value="{{ Input::old('facebook_title', @$info->facebook_title) }}">  
                                <div class="text-muted mt-2">{{ trans('messages.social_title', ['variable' => 'facebook']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Facebook {{ trans('backend.description') }}</label>
                                <textarea name="facebook_description" class="form-control" rows="6">{{ Input::old('facebook_description', @$info->facebook_description) }}</textarea>
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
                                <input type="text" name="twitter_title" class="form-control" value="{{ Input::old('twitter_title', @$info->twitter_title) }}">  
                                <div class="text-muted mt-2">{{ trans('messages.social_title', ['variable' => 'twitter']) }}</div>
                            </div>
                            <div class="form-group">
                                <label>Twitter {{ trans('backend.description') }}</label>
                                <textarea name="twitter_description" class="form-control" rows="6">{{ Input::old('twitter_description', @$info->twitter_description) }}</textarea>
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
    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.enabled_modules') }}</div>
            <div class="card-body">
                <div class="form-group">

                    @foreach(site_modules() as $module)
                    <div class="custom-control custom-checkbox align-items-center d-flex">
                    <input type="hidden" name="{{ $module['name'] }}" value="0">
                    {{ Form::checkbox($module['name'], 1, @$info->{$module['name']}, ['class' => 'enabled-modules custom-control-input', 'id' => $module['name'], 'data-target' =>  @$module['target']]) }} 
                    <label class="custom-control-label" for="{{ $module['name'] }}">{!! $module['label'] !!}</label>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>


        <div class="card mb-3">
            <div class="card-header bg-dark text-white text-uppercase">
                {{ trans('backend.maintenance') }}
                <div class="float-right">
                    {{ status_ico(@$info->maintenance_mode?'actived':'inactived') }}                
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">


                <div class="custom-control custom-checkbox align-items-center d-flex">
                <input type="hidden" name="maintenance_mode" value="0">
                {{ Form::checkbox('maintenance_mode', 1, @$info->maintenance_mode, ['class' => 'custom-control-input', 'id' => 'maintenance_mode']) }} 
                <label class="custom-control-label" for="maintenance_mode">{{ trans('backend.maintenance_mode') }}</label>
                </div>

                <div class="my-3">
                    <label class="text-muted">{{ trans('backend.background_image') }}</label>
                    <div class="media-single mb-2">
                    <input type="hidden" name="maintenance_bg" value="">
                    @if( $maintenance_bg = @$info->maintenance_bg )
                    <li class="list-unstyled">
                        <div class="media-thumb img-thumbnail">
                        <img src="{{ has_image($maintenance_bg) }}" class="img-fluid w-100">
                        <input type="hidden" name="maintenance_bg" value="{{ $maintenance_bg }}">
                        <a href="" class="delete-media"><i class="fas fa-trash"></i></a>
                        </div>
                    </li>
                    @endif
                    </div>
                    
                    <button type="button" class="filemanager btn btn-sm btn-outline-primary btn-block" 
                    data-href="{{ route('backend.media.frame', ['format' => 'image', 'mode' => 'single', 'target' => '.media-single', 'name' => 'maintenance_bg']) }}">{{ trans('backend.select') }}</button>
                        
                </div>

                <div class="form-group custom-control custom-checkbox align-items-center d-flex">
                <input type="hidden" name="debug_mode" value="0">
                {{ Form::checkbox('debug_mode', 1, @$info->debug_mode, ['class' => 'custom-control-input', 'id' => 'debug_mode']) }}
                    <label class="custom-control-label" for="debug_mode">{{ trans('backend.debug_mode') }} <small class="text-muted">/ will send report when disabled</small></label>               
                </div>

                <a href="{{ url('minified/css') }}" target="_blank" class="text-uppercase"><i class="far fa-file-archive mr-1"></i> Minified CSS</a>
                <div class="mt-2 small">
                    @if( file_exists('css/backend.min.css') )
                    <div class="text-muted">{{ date('F m, d H:i:s', filemtime(public_path('css/backend.min.css')))  }}</div>
                    <a href="{{ asset('css/backend.min.css') }}" target="_blank">backend.min.css</a><br>
                    @endif
                    @if( file_exists('css/frontend.min.css') )                            
                    <a href="{{ asset('css/frontend.min.css') }}" target="_blank">frontend.min.css</a>                        
                    @endif
                </div>
                </div>
            </div>
        </div>



        <div class="card mb-3">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.site_logo') }}</div>
            <div class="card-body">

                <div class="media-single mb-2">
                <input type="hidden" name="site_logo" value="">
                @if( $site_logo = @$info->site_logo )
                <li class="list-unstyled">
                    <div class="media-thumb img-thumbnail">
                    <img src="{{ asset($site_logo) }}" class="img-fluid w-100">
                    <input type="hidden" name="site_logo" value="{{ $site_logo }}">
                    <a href="" class="delete-media"><i class="fas fa-trash"></i></a>
                    </div>
                </li>
                @endif
                </div>

                <div class="mb-2 small text-muted"><b>150x40</b> <span class="text-uppercase">({{ trans('backend.recommended') }})</span></div>
                
                <button type="button" class="filemanager btn btn-sm btn-outline-primary btn-block" 
                data-href="{{ route('backend.media.frame', ['format' => 'image', 'mode' => 'single', 'target' => '.media-single', 'name' => 'site_logo']) }}">{{ trans('backend.select_logo') }}</button>

            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.footer_logo') }}</div>
            <div class="card-body">

                <div class="footer-logo mb-2">
                <input type="hidden" name="footer_logo" value="">
                @if( $footer_logo = @$info->footer_logo )
                <li class="list-unstyled">
                    <div class="media-thumb img-thumbnail">
                    <img src="{{ asset($footer_logo) }}" class="img-fluid w-100">
                    <input type="hidden" name="footer_logo" value="{{ $footer_logo }}">
                    <a href="" class="delete-media"><i class="fas fa-trash"></i></a>
                    </div>
                </li>
                @endif
                </div>

                <button type="button" class="filemanager btn btn-sm btn-outline-primary btn-block" 
                data-href="{{ route('backend.media.frame', ['format' => 'image', 'mode' => 'single', 'target' => '.footer-logo', 'name' => 'footer_logo']) }}">{{ trans('backend.select_logo') }}</button>

            </div>
        </div>


    </div>

</div>