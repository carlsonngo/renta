@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase mr-3">{{ $label }}</span>

/ <span class="badge text-uppercase p-2 mr-3">{{ trans('backend.statistic_report') }}</span>


<div class="float-right">

    @if( has_access($module, ['book_now']) ) 
    <a href="{{ route('backend.posts.index', ['post_type' => 'event']) }}" class="btn btn-sm btn-dark"> {{ trans('backend.book_now') }}</a> 
    @endif

    @if( has_access($module, ['calendar']) ) 
    <a class="btn btn-sm btn-outline-dark mr-2" href="{{ route('backend.bookings.calendar') }}">{{ trans('backend.view_calendar') }}</a>   
    @endif
</div>

@stop

@section('content')


<div class="bg-white py-4 rounded border mt-4">
    <div id="report"></div>    
</div>

@endsection

@section('style')
@stop

@section('plugin_script')
<script src="{{ asset('plugins/highcharts/code/highcharts.js') }}"></script>
<script src="{{ asset('plugins/highcharts/code/modules/exporting.js') }}"></script>
<script src="{{ asset('plugins/highcharts/code/modules/export-data.js') }}"></script>
@stop

@section('script')
<script type="text/javascript">
    Highcharts.chart('report', {
    title: {
        text: '{{ trans('messages.monthly_average_booking') }} {{ date('Y') }}'
    },
    xAxis: {
        categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ]
    },
    labels: {
        items: [{
            html: '{{ trans('backend.total_bookings') }}',
            style: {
                left: '50px',
                top: '18px',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
            }
        }]
    },
    exporting: {
        buttons: {
            contextButton: {
                enabled: false
            },
            exportButton: {
                text: '{{ trans('backend.download') }}',
                menuItems: Highcharts.getOptions().exporting.buttons.contextButton.menuItems.splice(2, 8)
            },

        }
    },
    credits: { enabled: false },
    series: [{
        type: 'column',
        name: '{{ trans('backend.reservations') }}',
        color: "#E91E63",
        data: <?php echo json_encode($reservations); ?>,
    }, {
        type: 'column',
        name: '{{ trans('backend.ticket_orders') }}',
        color: "#3F51B5",
        data: <?php echo json_encode($ticket_orders); ?>,
    }, {
        type: 'spline',
        name: '{{ trans('backend.reserved') }}',
        color: "#8edd33",
        data: <?php echo json_encode($reserved); ?>,
        marker: {
            lineWidth: 4,
            lineColor: '#8edd33',
            fillColor: 'white'
        }
    }, {
        type: 'spline',
        name: '{{ trans('backend.ticket_sold') }}',
        color: "#ff9800",
        data: <?php echo json_encode($ticket_sold); ?>,
        marker: {
            lineWidth: 4,
            lineColor: '#ff9800',
            fillColor: 'white'
        }
    }, {
        type: 'pie',
        name: '{{ trans('backend.total_bookings') }}',
        data: [{
            name: '{{ trans('backend.reservations') }}',
            y: <?php echo array_sum($reservations); ?>,
            color: "#E91E63"
        }, {
            name: '{{ trans('backend.ticket_orders') }}',
            y: <?php echo array_sum($ticket_orders); ?>,
            color: '#3F51B5' 
        }, {
            name: '{{ trans('backend.reserved') }}',
            y: <?php echo array_sum($reserved); ?>,
            color: "#8edd33"
        }, {
            name: '{{ trans('backend.ticket_sold') }}',
            y: <?php echo array_sum($ticket_sold); ?>,
            color: "#ff9800"
        }],
        center: [100, 80],
        size: 100,
        showInLegend: false,
        dataLabels: {
            enabled: false
        }
    }]
});
</script>
@stop
