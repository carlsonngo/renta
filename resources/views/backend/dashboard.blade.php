@extends('layouts.backend')

@section('content')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>

<hr>
<div class="row">

    @if( has_access('users') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-users mr-2"></i> {{ $users }} {{ trans('backend.users') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.users.index') }}" class="text-info">{{ trans('backend.all_users') }}</a></div>

                    @if( has_access('users', ['add_edit']) )
                    <div class="col-6 text-right"><i class="fas fa-plus"></i> <a href="{{ route('backend.users.add') }}" class="text-muted">{{ trans('backend.add_new') }}</a></div>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @endif

    @if( has_access('groups') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-file-alt mr-2"></i> {{ $groups }} {{ trans('backend.groups') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.groups.index') }}" class="text-info">{{ trans('backend.all_groups') }}</a></div>

                    @if( has_access('groups', ['add_edit']) )
                    <div class="col-6 text-right"><i class="fas fa-plus"></i> <a href="{{ route('backend.groups.add') }}" class="text-muted">{{ trans('backend.add_new') }}</a></div>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @endif

    @if( has_access('pages') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-file-alt mr-2"></i> {{ $pages }} {{ trans('backend.pages') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.posts.index', ['post_type' => 'page']) }}" class="text-info">{{ trans('backend.all_pages') }}</a></div>

                    @if( has_access('pages', ['add_edit']) )
                    <div class="col-6 text-right"><i class="fas fa-plus"></i> <a href="{{ route('backend.posts.add', ['post_type' => 'page']) }}" class="text-muted">{{ trans('backend.add_new') }}</a></div>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @endif

    @if( has_access('posts') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-thumbtack mr-2"></i> {{ $posts }} {{ trans('backend.posts') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.posts.index', ['post_type' => 'post']) }}" class="text-info">{{ trans('backend.all_posts') }}</a></div>

                    @if( has_access('posts', ['add_edit']) )
                    <div class="col-6 text-right"><i class="fas fa-plus"></i> <a href="{{ route('backend.posts.add', ['post_type' => 'post']) }}" class="text-muted">{{ trans('backend.add_new') }}</a></div>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @endif

    @if( App\Setting::get_setting('events_module') && has_access('events') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-calendar-alt mr-2"></i> {{ $events }} {{ trans('backend.events') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.posts.index', ['post_type' => 'event']) }}" class="text-info">{{ trans('backend.all_events') }}</a></div>

                    @if( has_access('events', ['add_edit']) )
                    <div class="col-6 text-right"><i class="fas fa-plus"></i> <a href="{{ route('backend.posts.add', ['post_type' => 'event']) }}" class="text-muted">{{ trans('backend.add_new') }}</a></div>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @endif

    @if( App\Setting::get_setting('gallery_module') && has_access('galleries') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-images mr-2"></i> {{ $galleries }} {{ trans('backend.galleries') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.galleries.index') }}" class="text-info">{{ trans('backend.all_galleries') }}</a></div>

                    @if( has_access('galleries', ['add_edit']) )
                    <div class="col-6 text-right"><i class="fas fa-plus"></i> <a href="{{ route('backend.galleries.add') }}" class="text-muted">{{ trans('backend.add_new') }}</a></div>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @endif

    @if( App\Setting::get_setting('bookings_module') && App\Setting::get_setting('events_module') && has_access('bookings') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-calendar-check mr-2"></i> {{ $bookings }} {{ trans('backend.new_bookings') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left">
                        {{ $reservations }} <a href="{{ route('backend.bookings.index', ['post_type' => 'reservation']) }}" class="text-info">{{ trans('backend.reservations') }}</a>
                    </div>
                    <div class="col-6 text-right">
                        {{ $tickets }} <a href="{{ route('backend.bookings.index', ['post_type' => 'ticket']) }}" class="text-info">{{ trans('backend.ticket_orders') }}</a>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    @endif
    
</div>

@if( App\Setting::get_setting('shop_module') )
<div class="h4 font-weight-normal text-uppercase">{{ trans('backend.shop') }}</div>

<div class="row">

    @if( has_access('products') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-boxes mr-2"></i> {{ $products }} {{ trans('backend.products') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.shop.products.index') }}" class="text-info">{{ trans('backend.all_products') }}</a></div>

                    @if( has_access('products', ['add_edit']) )
                    <div class="col-6 text-right"><i class="fas fa-plus"></i> <a href="{{ route('backend.shop.products.add') }}" class="text-muted">{{ trans('backend.add_new') }}</a></div>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @endif

    @if( has_access('coupons') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-ticket-alt mr-2"></i> {{ $coupons }} {{ trans('backend.coupons') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.shop.coupons.index') }}" class="text-info">{{ trans('backend.all_coupons') }}</a></div>

                    @if( has_access('coupons', ['add_edit']) )
                    <div class="col-6 text-right"><i class="fas fa-plus"></i> <a href="{{ route('backend.shop.coupons.add') }}" class="text-muted">{{ trans('backend.add_new') }}</a></div>
                    @endif
                </div>
            </div>
        </div>    
    </div>
    @endif

    @if( has_access('orders') )
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4 text-uppercase">
            <div class="card-header"><i class="fas fa-shopping-basket mr-2"></i> {{ $orders }} {{ trans('backend.orders') }}</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold">
                    <div class="col-6 text-left"><a href="{{ route('backend.shop.orders.index', ['post_type' => 'pending']) }}" class="text-info">{{ trans('backend.all_orders') }}</a></div>

                </div>
            </div>
        </div>    
    </div>
    @endif

</div>
@endif

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
