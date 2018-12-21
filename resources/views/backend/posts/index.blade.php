@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }}</span>

@if( has_access($module, ['add_edit']) )
<a href="{{ route('backend.posts.add', ['post_type' => $post_type]) }}" class="btn btn-sm btn-dark float-right"> {{ trans('backend.add_new') }}</a>   
@endif
<hr>
@stop

@section('content')

<form method="get">
@include('backend.partials.basic-search')

<div class="table-responsive">
<table class="table table-hover table-striped border bg-white table-bordered">
    <thead>
        <tr>
            @if( has_access($module, ['trash_restore']) )
            <td width="1" class="align-middle py-0">
                <div class="custom-control custom-checkbox text-center ml-2">
                    <input class="custom-control-input" id="check_all" name="" type="checkbox" value="1">
                    <label class="custom-control-label" for="check_all"></label> 
                </div>
            </td>
            @endif

            <th>{{ trans('backend.title') }}</th>
            <th width="160" class="text-center">{{ trans('backend.author') }}</th>

            @if( in_array($post_type, ['post', 'event']) )
            <th width="200" class="text-center">{{ trans('backend.category') }}</th>
            @endif
            
            <th width="120" class="text-center">{{ trans('backend.status') }}</th>

            @if( in_array($post_type, ['event']) )
            <th width="145" class="text-center">{{ trans('backend.event_date') }}</th>
            @endif

            @if($localization = App\Setting::get_setting('localization'))
            <th width="110" class="text-center">{{ trans('backend.translation') }}</th>
            @endif

            <th width="130" class="text-center">{{ trans('backend.created_at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>

        <tr class="has-actions">
            @if( has_access($module, ['trash_restore']) )
            <td class="align-middle text-center">
                <div class="custom-control custom-checkbox text-center ml-2">
                    <input class="custom-control-input checkboxes" id="c-{{ $row->id }}" name="ids[]" type="checkbox" value="{{ $row->id }}">
                    <label class="custom-control-label" for="c-{{ $row->id }}"></label> 
                </div>
            </td>
            @endif

            <td>
                {{ $row->post_title }}

                <div class="table-actions small text-uppercase mt-2">
                    <span class="text-muted">ID : {{ $row->id }}</span>
                    @if( Input::get('type') == 'trash' )
                    | <a href="#" class="popup"
                        data-url="{{ URL::route($view.'.restore', [$row->id, query_vars()]) }}" 
                        data-toggle="confirm-modal" 
                        data-target=".confirm-modal" 
                        data-title="{{ trans('backend.confirm_restore') }}"
                        data-body="{{ trans('messages.confirm_restore') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.restore') }}</a> 

                    | <a href="#" class="text-danger"
                        data-url="{{ URL::route($view.'.destroy', [$row->id, query_vars()]) }}" 
                        data-toggle="confirm-modal" 
                        data-target=".confirm-modal" 
                        data-title="{{ trans('backend.confirm_delete_permanently') }}"
                        data-body="{{ trans('messages.confirm_destroy') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.delete_permanently') }}</a>
                    @else
                        @if( has_access($module, ['add_edit']) )
                        | <a href="{{ URL::route($view.'.edit', [$row->id, 'post_type' => $post_type]) }}">{{ trans('backend.edit') }}</a> 
                        @endif

                        | <a href="{{ URL::route('frontend.post', $row->post_name) }}" target="_blank">{{ trans('backend.view') }}</a> 
                    

                        @if( in_array($post_type, ['event']) && is_bookable($postmeta) && has_access('bookings', ['book_now'] ))
                        | <a href="{{ route('backend.bookings.add', $row->id) }}" class="text-info">+ {{ trans('backend.book_now') }}</a> 
                        @endif

                        @if( has_access($module, ['trash_restore']) )
                        | <a href="#" class="text-danger"
                            data-url="{{ URL::route($view.'.delete', [$row->id, query_vars()]) }}" 
                            data-toggle="confirm-modal" 
                            data-target=".confirm-modal" 
                            data-title="{{ trans('backend.confirm_move_trash') }}"
                            data-body="{{ trans('messages.confirm_move_trash') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.move_trash') }}</a>
                        @endif

                        @if( has_access($module, ['duplicate']) )
                        | <a href="#" class="text-success"
                            data-url="{{ URL::route($view.'.clone', $row->id) }}" 
                            data-toggle="confirm-modal" 
                            data-target=".confirm-modal" 
                            data-title="Confirm Duplicate"
                            data-body="Are you sure you want to duplcate <b>#{{ $row->id }}</b>?">{{ trans('backend.duplicate') }}</a>
                        @endif
                    @endif
                </div>
            </td>
            <td class="align-middle text-center">{!! $row->authorName !!}</td>

            @if( in_array($post_type, ['post', 'event']) )
            <td class="align-middle text-center"><span class="text-muted category-list">{{ $row->categoryList }}</span></td>
            @endif

            <td class="align-middle text-center">
                {{ status_ico($row->post_status) }}
                @if( is_fsk18($postmeta) )
                <div class="mt-1 small text-danger">FSK18</div>
                @endif
            </td>

            @if( in_array($post_type, ['event']) )
            <td class="align-middle text-center">
                @if( is_past_date(@$postmeta->date_start.' '.@$postmeta->time_start) )
                <div class="text-danger">
                    @if( @$postmeta->date_start ) 
                        <i class="far fa-calendar mr-1"></i> {{ date_formatted($postmeta->date_start) }} <br>
                        @if( @$postmeta->time_start )
                        <i class="far fa-clock mr-1"></i> {{ $postmeta->time_start }}
                        @endif
                        <div class="text-uppercase small mt-2">Past Event</div>
                    @endif
                </div>
                @else
                    @if( @$postmeta->date_start ) 
                        <i class="far fa-calendar text-muted mr-1"></i> {{ date_formatted($postmeta->date_start) }} <br>
                        @if( @$postmeta->time_start )
                        <i class="far fa-clock text-muted mr-1"></i> {{ $postmeta->time_start }}
                        @endif
                    @else
                        <div class="text-uppercase small mt-2">Not Set</div>                    
                    @endif                    
                @endif
            </td>
            @endif

            @if( $localization )
            <td  class="align-middle text-center">
                @if( $row->post_title )
                <img src="{{ asset('assets/img/flags/en.png') }}" class="rounded-shadow mx-1 mb-2" data-toggle="tooltip" title="English" width="25">
                @endif

                @foreach( array_except(languages(), ['en']) as $lang_k => $lang_v)
                    @if( @$postmeta->{$lang_k.'_title'} )                
                    <img src="{{ asset('assets/img/flags/'.$lang_k.'.png') }}" class="rounded-shadow mb-2 mx-1" data-toggle="tooltip" title="{{ $lang_v }}" width="25">
                    @endif
                @endforeach
            </td>
            @endif

            <td class="align-middle text-center">
                <div>{{ date_formatted($row->created_at) }}</div>
                <span class="text-muted">{{ time_ago($row->created_at) }}</span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

@if( ! count($rows) )
    <div class="alert alert-warning">No {{ strtolower($label) }} found.</div>
@else
    <?php parse_str(query_vars(), $q); ?>
    {{ $rows->appends($q)->links() }}
@endif
@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
