        <div class="card mb-4">
            <div class="card-header bg-dark text-white text-uppercase">SMTP</div>
            <div class="card-body no-space">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Encryption</label>
                            {{ Form::select('mail_encryption', mail_encryption(), Input::old('mail_encryption', @$setting->mail_encryption), ['class' => 'form-control'] ) }}
                    
                        </div>
                        <div class="form-group">
                            <label>Host</label>
                            <input type="text" name="mail_host" class="form-control" value="{{ Input::old('mail_host', @$setting->mail_host) }}">                    
                        </div>
                        <div class="form-group">
                            <label>Port</label>
                            <input type="text" name="mail_port" class="form-control" value="{{ Input::old('mail_port', @$setting->mail_port) }}">                    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('backend.username') }}</label>
                            <input type="text" name="mail_username" class="form-control" value="{{ Input::old('mail_username', @$setting->mail_username) }}">
 
                        </div>
                        <div class="form-group">
                            <label>{{ trans('backend.password') }}</label>
                            <input type="password" name="mail_password" class="form-control" value="{{ Input::old('mail_password', @$setting->mail_password) }}">
                        </div>     
                                         
                    </div>

                </div>
            </div>
        </div>
