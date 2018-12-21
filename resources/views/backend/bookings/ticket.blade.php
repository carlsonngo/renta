<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<style type="text/css">
body { font-size: 22px; }    
</style>

<h4>Event Ticket</h4>

<div class="border border-top-0 my-3 bg-light">
    <table class="table mb-0">
        <tr class="text-muted">
            <td class="text-uppercase">
                <small>Ticket #</small>
                <div class="font-weight-bold h4">{{ $info->id }}</div>
            </td>
            <td class="text-uppercase">
                <small>Ticket Code</small>
                <div class="font-weight-bold h4">{{ $info->post_name }}</div>
            </td>
            <td>
                <small>PURCHASER</small>
                <p class="font-weight-bold text-uppercase mb-1 h4">{{ $info->name }}</p>
                {{ $info->post_title }}
            </td>

        </tr>
    </table>
    </div>

    <div class="border p-3">
    <table width="100%">
        <tr>
            <td>
                <div class="text-muted text-uppercase small">
                    Event Title
                </div>
                <div>{{ $event->post_title }}</div>
            </td>
            <td>
                <div class="text-muted text-uppercase small">
                    Event Date
                </div>
                {{ date('F d, Y', strtotime(@$info->date_start)) }} @         
                {{ date('h:i A', strtotime(@$info->time_start)) }}          
            </td>
        </tr>
        <tr>
            <td>
                <div class="text-muted text-uppercase small">
                    Organizer
                </div>
                {{ @$event->organizer }}            
            </td>
            <td>
                <div class="text-muted text-uppercase small">
                    Location
                </div>
                {{ @$event->location }}            
            </td>
        </tr>
    </table>

    <hr>

    <div class="mb-3">
        <p class="text-muted">ATTENDEE :</p>



        <?php $attendee = json_decode(@$info->attendee); ?>
        @foreach($attendee as $a_k => $a_v)                        
        <div>{{ trans('backend.'.$a_k) }} :
            {{ $a_v->count }}
        </div>
        @endforeach
          
        <div class="mt-3">{{ App\Setting::get_setting('site_title') }} / <span class="text-primary">{{ url('/') }}</span></div>

    </div>

</div>
