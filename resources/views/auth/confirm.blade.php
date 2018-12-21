@extends('layouts.frontend-fullwidth')

@section('content')
<div class="my-5">

    <form class="form-signin bg-white align-items-middle no-space py-4 border rounded" method="post">
        @include('notification')
    
        {{ csrf_field() }}
        <h1 class="h4 mb-4 font-weight-normal text-center">{{ App\Setting::get_setting('site_title') }} <b>{{ trans('backend.log_in') }}</b></h1>
        <div class="form-group">
        <h5>{{ $info->email }}</h5>
        </div>
        <div class="form-group">
          <label for="inputPassword" class="sr-only">{{ trans('backend.password') }}</label>
          <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Enter password" autocomplete="new-password">
          {!! $errors->first('password','<p class="text-danger my-2">:message</p>') !!}       
        </div>

        <button class="btn btn-lg btn-outline-primary btn-block mb-3 text-uppercase" type="submit">Confirm</button>
    </form>
</div>
@endsection

@section('style')
<style>
.form-signin {
  width: 100%;
  max-width: 400px;
  padding: 15px;
  margin: auto;
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
@stop

@section('plugin_script')
@stop

@section('script')
@stop
