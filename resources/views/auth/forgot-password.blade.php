@extends('layouts.frontend-fullwidth')

@section('content')

<div class="container mt-5 ">
@include('notification')
<div class="bg-white border rounded mb-5">


    <form class="form-signin my-5 align-items-middle no-space" method="post">
      {{ csrf_field() }}


        @if($token)
          <div class="alert alert-info">
            <strong>Note:</strong> You must complete this last step to access your account.
          </div>

    <div class="form-group">
          <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password" value="{{ Input::old('new_password') }}">
          {!! $errors->first('new_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
    </div>

      <div class="form-group">
        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation') }}">
        {!! $errors->first('new_password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
      </div>

        <div class="row align-items-middle">
          <div class="col-md-6">
              <button type="submit" class="btn btn-primary">Change Password</button>       
          </div>
          <div class="col-md-6 text-right">    
            <a href="{{ URL::route('auth.login') }}" class="btn"> Login </a>            
          </div>
        </div>

        @else


    <h3>Forget Password ?</h3>
    <p>We will find your account.</p>

    <div class="form-group">
        <input type="text" id="inputEmail" name="email" class="form-control" placeholder="Email" value="{{ Input::old('email') }}">
      {!! $errors->first('email','<p class="text-danger mt-2">:message</p>') !!}
    </div>


        <div class="row align-items-middle">
          <div class="col-md-6">
              <button type="submit" class="btn btn-primary">Submit</button>       
          </div>
          <div class="col-md-6 text-right">    
            <a href="{{ URL::route('auth.login') }}" class="btn"> Login </a>            
          </div>
        </div>


        @endif
          <input type="hidden" name="op" value="1">

      </form>

</div>
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




