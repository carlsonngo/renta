@extends('layouts.frontend-fullwidth')

@section('header')
<div class="bg-white">
    <div class="container py-3">
        <h1 class="font-weight-bold text-uppercase m-0 h4">Dashboard</h1>
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
        <div class="bg-white rounded py-4 pb-2 px-4 mb-4">
            <div class="alert bg-light">
                Hello <b>{{ $info->firstname }}</b> (not <b>{{ $info->firstname }}</b>? <a href="{{ route('auth.logout') }}">Log out</a>)
            </div>
            <div class="alert bg-light">
                From your account dashboard you can view your <a href="{{ route('shop.customer.orders') }}" class="text-primary">recent orders</a>, 
                manage your <a href="{{ route('shop.customer.addresses') }}" class="text-primary">shipping and billing addresses</a>, 
                and <a href="{{ route('shop.customer.account') }}" class="text-primary">edit your password and account details</a>.
            </div>
        </div>        
    </div>
</div>


@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
