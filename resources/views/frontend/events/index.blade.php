@extends('layouts.frontend-fullwidth')

@section('header')
<div class="bg-white">
    <div class="container">
        <div class="mb-4 px-4 py-4">
            <h2 class="font-weight-bold text-uppercase">{{ trans('backend.events') }}</h2>
            <p class="font-italic text-muted">{{ trans('backend.our_latest_news') }}</p>
        </div>    
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-4 aside-calendar sticky-top d-table" data-class="sticky-top d-table" data-spy="classy" data-offset-top="90" data-top="80px" data-target=".aside-calendar">
        <div class="bg-white pt-2 pb-2 px-2 mb-4 rounded">
            <div id="calendar" class="w-100"></div>

            <div class="mt-3">
    	        <a href="{{ route('frontend.events.index') }}" class="btn btn-outline-primary btn-xs btn-block">{{ trans('backend.all_events') }}</a>        	
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="bg-white pt-4 pb-2 px-4 mb-4 rounded">

        @if( count($rows) )
        
        @if($date==date('Y-m-d'))
        <p>{{ trans('backend.next_event') }} Tuesday, October 9, 2018</p>
        @elseif( $date<date('Y-m-d') )
        {{ trans('backend.previous_events') }}
        @else
        {{ trans('backend.upcoming_events') }}
        @endif

        <hr>
        <div class="mb-4">
    
            @foreach($rows as $row)
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            <div class="row pt-4 px-4">
                <div class="col-md-4">
                    <div class="shadow-3d">
                        <div class="img-container mb-2 box-shadow">
                          <img class="img-fluid" src="{{ has_image( str_replace('large', 'medium', @$postmeta->image) ) }}">
                        </div>              
                    </div>  
                </div>
                <div class="col-md-8">
                    <h4><a href="{{ url($row->post_name) }}">{{ trans_post($row, 'post_title', '_title') }}</a></h4>
                    @if( $sub_title = trans_post($row, 'sub_title', '_sub_title') )
                    <p class="text-muted">{{ $sub_title }} 
                        @if( $time_start = @$postmeta->time_start )
                        at {{ $time_start }}
                        @endif
                    </p>
                    @endif

                    @if( @$postmeta->date_start || @$postmeta->time_start )
                    <div class="mb-2">
                        <i class="far fa-calendar mr-2"></i> {{ date_formatted(@$postmeta->date_start) }} 
                        <i class="far fa-clock mr-2 ml-3"></i> {{ @$postmeta->time_start }}    
                    </div>
                    @endif

                    @if( @$postmeta->location )
                    <p><i class="fas fa-map-marked-alt mr-1"></i> {{ @$postmeta->location }}</p> 
                    @endif

                    <p class="card-text text-justify">
                        <?php $event_content = trans_post($row, 'post_content', '_content'); ?>
                        {!! str_limit(strip_tags($event_content), 300, '...') !!}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="{{ route($view.'.single', $row->post_name) }}" class="btn btn-sm btn-outline-primary">{{ trans('backend.read_more') }}</a>
                            
                        </div>
                        <small class="text-muted">{{ time_ago($row->created_at) }}</small>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-2 dashed">
            @endforeach

        </div>


        	{{ $rows->links() }}
 

        </div>
       @else
        	<h6 class="alert">{{ trans('backend.no_event_found') }} <b>{{ date_formatted($date) }}!</b></h6>
        @endif

    </div>
</div>

@endsection

@section('style')
<style>
.dashed:last-child {
	display: none;
}
[data-date="{{ $date }}"] {
    background-color: #FFEB3B !important;
}
.fc-scroller { 
    overflow: auto !important; 
    height: auto !important; 
}    
#calendar .fc-day:hover {
    background-color: #FFEB3B;
    cursor: pointer;
}
#calendar .fc-left { margin-bottom: 10px; }
</style>
@stop


@section('plugin_script')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/fullcalendar/fullcalendar.min.css') }}">
<script type="text/javascript" src="{{ asset('plugins/fullcalendar/lib/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/fullcalendar/fullcalendar.min.js') }}"></script>
@stop


@section('script')
<script type="text/javascript">
$('#calendar').fullCalendar({
    defaultDate: '{{ $date }}',
    defaultView: 'month',
    header: {
        left: 'title',
        center: '',
        right: 'prev, next, today',
    },
    dayClick: function(date, jsEvent, view) {
    	location.href = "?date_start="+date.format()+'&lang='+$('[name="lang"]').val();
    }
});
</script>
@stop
