<div class="form-group-extra">
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col">
                <input type="text" class="form-control form-control-sm input-f" name="extra[{{ $e }}][title]" placeholder="{{ trans('backend.title') }}" value="{{ @$ex['title'] }}">                
            </div>
             <div class="col-auto">
                <a href="" class="text-uppercase small text-danger btn-remove-extra">{{ trans('backend.remove') }}</a>
            </div>
        </div>
    </div>
    <div class="form-group">
        <textarea class="form-control form-control-sm input-f" rows="4" name="extra[{{ $e }}][description]" placeholder="{{ trans('backend.description') }}">{{ @$ex['description'] }}</textarea>
    </div>         
</div>