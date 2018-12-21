@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }}</span>
/ <span class="badge text-uppercase p-2 mr-3">{{ $single }}</span>

<div class="float-right">
    @if( has_access($module, ['book_now']) ) 
    <a href="{{ route('backend.posts.index', ['post_type' => 'event']) }}" class="btn btn-sm btn-dark mr-2"> {{ trans('backend.book_now') }}</a>   
    @endif

    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark">
        <i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_bookings') }}
    </a>    
</div>
<hr>
@stop

@section('content')
<form method="GET">
    <div class="row form-group">
        <div class="col-auto pr-0">
            {{ Form:: select('m', get_c_months(), Input::get('m'), ['class' => 'form-control form-control-sm']) }}
        </div>
        <div class="col-auto pr-0">
            {{ Form:: select('y', get_cc_years(), Input::get('y'), ['class' => 'form-control form-control-sm']) }}
        </div>    
        <div class="col-auto">
            <button class="btn btn-primary btn-sm mr-2">{{ trans('backend.go') }}</button>
            <a href="{{ route($view.'.calendar') }}" class="btn btn-outline-primary btn-sm">{{ trans('backend.today') }}</a>       
        </div>
    </div>    
</form>
<div id="calendar" class="mb-4"></div>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/fullcalendar/fullcalendar.min.css') }}">
<style type="text/css">    
.fc-head { 
    background: #343a40;
    color: #fff;
}
</style>
@stop

@section('plugin_script')
<script type="text/javascript" src="{{ asset('plugins/fullcalendar/lib/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/fullcalendar/fullcalendar.min.js') }}"></script>
@stop

@section('script')
<script type="text/javascript">
$('#calendar').fullCalendar({
    defaultDate: '<?php echo $date; ?>',
    defaultView: 'month',
    defaultView: 'month',
    eventLimit: 5,
    header: {
        left: 'title',
        center: '',
        right: 'prev, next, today',
    },
    eventSources: [{
        events: <?php echo json_encode($events); ?>,
        className: 'p-1',
    }]
});
</script>
@stop