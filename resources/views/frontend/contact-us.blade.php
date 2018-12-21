@extends('layouts.frontend-with-sidebar')

@section('content')
<section class="px-4 py-3 mb-4 bg-white rounded border">
    <div class="mt-3 mb-4">
        <h2 class="font-weight-bold text-uppercase">{{ trans('backend.contact_us') }}</h2>
        <p class="font-italic text-muted">{{ trans('messages.get_in_touch', ['variable' => App\Setting::get_setting('site_title')]) }}</p>
    </div>
    <form>
        <div class="form-group">
            <label>{{ trans('backend.name') }}</label>
            <input type="text" name="name" class="form-control">                    
        </div>
        <div class="form-group">
            <label>{{ trans('backend.email') }}</label>
            <input type="email" name="email" class="form-control">                    
        </div>
        <div class="form-group">
            <label>{{ trans('backend.message') }}</label>
            <textarea name="message" class="form-control" class="form-control" rows="6"></textarea>                    
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">{{ trans('backend.submit') }}</button>
        </div>                    
    </form>
</section>
@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
