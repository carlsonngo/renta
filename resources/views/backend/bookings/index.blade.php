@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }}</span>
@if( $post_type )
/ <span class="badge text-uppercase p-2 mr-3">{{ event_type($post_type) }}</span>
@endif

<div class="float-right">
    @if( has_access($module, ['book_now']) )
    <a href="{{ route('backend.posts.index', ['post_type' => 'event']) }}" class="btn btn-sm btn-dark"> {{ trans('backend.book_now') }}</a> 
    @endif

    @if( has_access($module, ['calendar']) )     
    <a class="btn btn-sm btn-outline-dark mr-2" href="{{ route('backend.bookings.calendar') }}">{{ trans('backend.view_calendar') }}</a>   
    @endif
</div>
<hr>
@stop

@section('content')

<form method="get">
@include('backend.partials.booking-search')

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

            <th>{{ trans('backend.name') }}</th>
            <th>{{ trans('backend.book_by') }}</th>
            <th width="80" class="text-center">Code</th>            
            <th width="100" class="text-center">{{ trans('backend.type') }}</th>            
            <th width="110" class="text-center">{{ trans('backend.status') }}</th>
            <th width="130" class="text-center">{{ trans('backend.event_date') }}</th>
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
                <a href="{{ route($view.'.view', $row->id) }}" class="text-dark">
                    {{ $post->find($postmeta->event_id)->post_title }}
                </a>

                <div class="table-actions small text-uppercase mt-2">
                    <span class="text-muted">ID : {{ $row->id }}</span> 
                    @if( Input::get('type') == 'trash' )
                    | <a href="#" class="popup"
                        data-url="{{ URL::route($view.'.restore', [$row->id, 'post_type' => $row->post_type]) }}" 
                        data-toggle="confirm-modal" 
                        data-target=".confirm-modal" 
                        data-title="{{ trans('backend.confirm_restore') }}"
                        data-body="{{ trans('messages.confirm_restore') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.restore') }}</a> 
                    | <a href="#" class="text-danger"
                        data-url="{{ URL::route($view.'.destroy', [$row->id, 'post_type' => $row->post_type]) }}" 
                        data-toggle="confirm-modal" 
                        data-target=".confirm-modal" 
                        data-title="{{ trans('backend.confirm_delete_permanently') }}"
                        data-body="{{ trans('messages.confirm_destroy') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.delete_permanently') }}</a>
                    @else
                        @if( has_access($module, ['view']) )
                        | <a href="{{ URL::route($view.'.view', $row->id) }}">{{ trans('backend.view') }}</a>
                        @endif

                        @if( has_access($module, ['trash_restore']) )
                        | <a href="#" class="text-danger"
                            data-url="{{ URL::route($view.'.delete', [$row->id, 'post_type' => $row->post_type]) }}" 
                            data-toggle="confirm-modal" 
                            data-target=".confirm-modal" 
                            data-title="{{ trans('backend.confirm_move_trash') }}"
                            data-body="{{ trans('messages.confirm_move_trash') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.move_trash') }}</a>
                        @endif
                    @endif

                    @if( $row->post_type == 'ticket' )
                    | <a href="{{ route('frontend.events.ticket', $row->post_name) }}" target="_blank">Ticket</a>
                    @endif
                </div>
            </td>   
            <td>
                {{ $postmeta->name }}
                <div class="text-muted">{{ $row->post_title }}</div>
            </td>
            <td class="align-middle text-center">{{ $row->post_name }}</td>
            <td class="align-middle text-center">{{ event_type($row->post_type) }}</td>
            <td class="align-middle text-center">
                {{ status_ico($row->post_status) }}
                @if( @$postmeta->confirmed) 
                <div class="mt-1">{{ status_ico('confirmed') }}</div>
                @endif
            </td>
            <td class="align-middle text-center">
                @if( @$postmeta->date_start ) 
                <i class="far fa-calendar text-muted mr-1"></i> {{ date_formatted($postmeta->date_start) }} <br>
                @endif
                
                @if( @$postmeta->time_start )
                <i class="far fa-clock text-muted mr-1"></i> {{ $postmeta->time_start }}
                @endif
            </td>
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
