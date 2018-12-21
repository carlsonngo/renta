@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }}</span>

@if( has_access($module, ['add_edit']) )
<a href="{{ route('backend.shop.products.add') }}" class="btn btn-sm btn-dark float-right"> {{ trans('backend.add_new') }}</a>   
@endif

<hr>
@stop

@section('content')

<form method="get">
@include('backend.partials.product-search')

<div class="table-responsive">
<table class="table table-hover table-striped border bg-white table-bordered">
    <thead>
        <tr>
            @if( has_access($module, ['trash_restore', 'add_edit']) )
            <td width="1" class="align-middle py-0">
                <div class="custom-control custom-checkbox text-center ml-2">
                    <input class="custom-control-input" id="check_all" name="" type="checkbox" value="1">
                    <label class="custom-control-label" for="check_all"></label> 
                </div>
            </td>
            @endif

            <th>{{ trans('backend.title') }}</th>
            <th width="200" class="text-center">{{ trans('backend.category') }}</th>
            <th width="120" class="text-center">{{ trans('backend.status') }}</th>
            <th width="160" class="text-center">{{ trans('backend.updated_at') }}</th>
            <th width="130" class="text-center">{{ trans('backend.created_at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>

        <tr class="has-actions">
            @if( has_access($module, ['trash_restore', 'add_edit']) )
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
                        | <a href="{{ URL::route($view.'.edit', $row->id) }}">{{ trans('backend.edit') }}</a>
                        @endif

                        | <a href="{{ route('shop.single', $row->post_name) }}" target="_blank">{{ trans('backend.view') }}</a> 

                        @if( has_access($module, ['trash_restore']) )
                        | <a href="#" class="text-danger"
                            data-url="{{ URL::route($view.'.delete', [$row->id, query_vars()]) }}" 
                            data-toggle="confirm-modal" 
                            data-target=".confirm-modal" 
                            data-title="{{ trans('backend.confirm_move_trash') }}"
                            data-body="{{ trans('messages.confirm_move_trash') }} ID: <b>#{{ $row->id }}</b>?">{{ trans('backend.move_trash') }}</a>
                        @endif
                    @endif
                </div>
            </td>
            <td class="align-middle text-center"><span class="text-muted category-list">{{ $row->categoryList }}</span></td>
            <td class="align-middle text-center">{{ status_ico($row->post_status) }}</td>
            <td class="align-middle text-center">
                <div>{{ date_formatted($row->updated_at) }}</div>
                <span class="text-muted">{{ time_ago($row->updated_at) }}</span>
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
</form>

@if( ! count($rows) )
    <div class="alert alert-warning">No {{ strtolower($label) }} found.</div>
@else
    <?php parse_str(query_vars(), $q); ?>
    {{ $rows->appends($q)->links() }}
@endif

@include('backend.partials.import-modal')

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
