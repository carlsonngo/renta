@extends('layouts.frontend-fullwidth')

@section('header')
<div class="bg-white">
    <div class="container py-3">
        <h1 class="font-weight-bold text-uppercase m-0 h4">Account Details</h1>
    </div>
</div>     
@stop

@section('content')

@include('notification')

<div class="row mb-4">
    <div class="col-md-3">
        @include('shop.customer.menu')     
    </div>
    <div class="col-md-9">
        <form method="POST">
            {{ csrf_field() }}            
            <div class="bg-white rounded py-4 px-4 mb-4">

                <div class="row">
                    <div class="col form-group">
                        <label>First Name <span class="text-danger">*</span></label>
                        <input type="text" name="firstname" class="form-control" value="{{ Input::old('firstname', @$info->firstname) }}">
                        {!! $errors->first('firstname','<p class="text-danger my-2">:message</p>') !!}  
                    </div>
                    <div class="col form-group">
                        <label>Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="lastname" class="form-control" value="{{ Input::old('lastname', @$info->lastname) }}">
                        {!! $errors->first('lastname','<p class="text-danger my-2">:message</p>') !!}  
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group">
                        <label>Email Address <span class="text-danger">*</span></label>
                        <input type="text" name="email" class="form-control" value="{{ Input::old('email', @$info->email) }}">
                        {!! $errors->first('email','<p class="text-danger my-2">:message</p>') !!}  
                    </div>
                </div>

            </div>        

            <div class="card mb-3">
                <div class="card-header bg-dark text-white text-uppercase">{{ trans('backend.change_password') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Current password (leave blank to leave unchanged)</label>
                        <input type="text" name="current_password" class="form-control" value="{{ Input::old('current_password') }}">
                        {!! $errors->first('current_password','<p class="text-danger my-2">:message</p>') !!}  
                    </div>
                    <div class="form-group">
                        <label>New password (leave blank to leave unchanged)</label>
                        <input type="text" name="new_password" class="form-control" value="{{ Input::old('new_password') }}">
                        {!! $errors->first('new_password','<p class="text-danger my-2">:message</p>') !!}  
                    </div>
                    <div class="form-group">
                        <label>Confirm new password</label>
                        <input type="text" name="new_password_confirmation" class="form-control" value="{{ Input::old('new_password_confirmation') }}">
                        {!! $errors->first('new_password_confirmation','<p class="text-danger my-2">:message</p>') !!}  
                    </div>
                    
                    <button class="btn btn-outline-primary">{{ trans('backend.save_changes') }}</button>

                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
