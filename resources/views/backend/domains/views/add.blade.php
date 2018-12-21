    <div class="row">
        <div class="col-lg-9">

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.details') }}</div>
                <div class="card-body">

                    <div class="form-group no-space">
                        <label>{{ trans('backend.domain_name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ Input::old('name') }}" placeholder="ex. {{ \Request::getHost() }}">
                        <div class="text-muted my-2 small">Do not include protocol ( http:// or https:// )</div>   
                        {!! $errors->first('name','<p class="text-danger my-2">:message</p>') !!}                   
                    </div>
                    <div class="form-group no-space">
                        <label>URL</label>
                        <input type="text" name="url" class="form-control" value="{{ Input::old('url') }}" placeholder="ex. {{ url('/') }}">  
                        {!! $errors->first('url','<p class="text-danger my-2">:message</p>') !!}                    
                    </div>
                    <div class="form-group">
                        <label> {{ trans('backend.description') }}</label>
                        <textarea name="description" class="form-control" rows="6">{{ Input::old('description') }}</textarea>                
                    </div>               
               
                </div>
            </div>

        </div>
        <div class="col-lg-3">

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
                        {{ Form::select('status', active_status(), Input::old('status'), ['class' => 'form-control'] ) }}
                        {!! $errors->first('status','<p class="text-danger my-2">:message</p>') !!}     
                    </div>
                </div>
            </div>
        </div>
    </div>