@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }}</span>

@if( has_access($module, ['add_edit']) )
<a href="{{ route($view.'.add') }}" class="btn btn-sm btn-dark float-right"> {{ trans('backend.add_new') }}</a>   
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

            <th width="60"></th>
            <th width="300">{{ trans('backend.name') }}</th>
            <th class="text-center">{{ trans('backend.group') }}</th>
            <th>{{ trans('backend.email') }}</th>
            <th width="120" class="text-center">{{ trans('backend.status') }}</th>
            <th width="160" class="text-center">{{ trans('backend.last_login') }}</th>
            <th width="160" class="text-center">{{ trans('backend.created_at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <?php $usermeta = get_meta( $row->userMetas()->get() ); ?>
        <tr class="has-actions">

            @if( has_access($module, ['trash_restore']) )
            <td class="align-middle text-center">
                <div class="custom-control custom-checkbox text-center ml-2">
                    <input class="custom-control-input checkboxes" id="c-{{ $row->id }}" name="ids[]" type="checkbox" value="{{ $row->id }}">
                    <label class="custom-control-label" for="c-{{ $row->id }}"></label> 
                </div>
            </td>
            @endif
            
            <td class="p-0 text-center align-middle" style="min-width:60px;">
            <a href="{{ has_image(@$usermeta->profile_picture) }}" class="btn-img-preview" data-title="{{ $row->fullname }} ( {{ $row->email }} )">
                <img src="{{ has_image(@$usermeta->profile_picture) }}" class="img-fluid img-icon-md"> 
            </a>                
            </td>
            <td>
                {{ ucwords($row->firstname.' '.$row->lastname) }}

                <div class="table-actions small text-uppercase mt-2">
                    <span class="text-muted">ID : {{ $row->id }}</span> 
                    @if( Input::get('type') == 'trash' )
                    | <a href="#" class="popup"
                        data-url="{{ URL::route($view.'.restore', [$row->id, query_vars()]) }}" 
                        data-toggle="confirm-modal" 
                        data-target=".confirm-modal" 
                        data-title="Confirm Restore"
                        data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.restore') }}</a>
                    | <a href="#" class="text-danger"
                        data-url="{{ URL::route($view.'.destroy', [$row->id, query_vars()]) }}" 
                        data-toggle="confirm-modal" 
                        data-target=".confirm-modal" 
                        data-title="Confirm Delete Permanently"
                        data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.delete_permanently') }}</a>
                    @else
                        @if( has_access($module, ['add_edit']) )
                        | <a href="{{ URL::route($view.'.edit', $row->id) }}">{{ trans('backend.edit') }}</a>
                        @endif

                        @if( has_access($module, ['login_as']) )
                        | <a href="{{ URL::route($view.'.login', $row->id) }}" class="text-success">Login As</a>
                        @endif

                        @if( has_access($module, ['trash_restore']) )
                        | <a href="#" class="text-danger"
                            data-url="{{ URL::route($view.'.delete', [$row->id, query_vars()]) }}" 
                            data-toggle="confirm-modal" 
                            data-target=".confirm-modal" 
                            data-title="Confirm Move to Trash"
                            data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.move_trash') }}</a>
                        @endif

                    @endif
                </div>

            </td>
            <td class="text-center align-middle">{{ user_group($row->group) }}</td>
            <td class="align-middle">{{ $row->email }}</td>
            <td class="align-middle text-center">{{ status_ico($row->status) }}</td>
            <td class="align-middle text-center">
                <div>{{ date_formatted(@$usermeta->last_login) }}</div>
                <span class="text-muted">{{ time_ago(@$usermeta->last_login) }}</span>
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

@include('backend.partials.preview-modal')
@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
