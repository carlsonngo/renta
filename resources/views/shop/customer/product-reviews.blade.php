@extends('layouts.frontend-basic')

@section('content')


<table class="table border bg-white">
    @foreach($reviews as $review)
    <tr>
        <td>
            <div class="rates-o">
                <span>{{ stars_review(5) }}</span>
                <span class="text-warning">{{ stars_review($review->post_title) }}</span>
            </div> <span>by</span> <span>{{ $review->user->firstname }}</span>
          
            <div class="mb-1 mt-2">{{ $review->post_content }}</div>
            <div class="text-muted small">{{ time_ago($review->created_at) }}</div>
        </td>
    </tr>
    @endforeach
</table>

{{ $reviews->appends(['reviews' => 1])->links() }}


<div class="o-loader animated">
    <div class="row align-items-center h-100">
        <div class="col-12 m-auto w-25 text-center animated bounceIn">
            <img src="{{ asset('assets/img/loaders/1.gif' ) }}?<?php date('ymdhis'); ?>" class="img-thumbnail rounded-circle animated bounce delay-1s" width="70">          
        </div>
    </div>
</div>
@endsection


@section('style')
<style type="text/css">
body { background-color: transparent; }    
</style>
@stop

@section('script')
<script>  
$(document).on('click', '.page-link', function(){
    $('.o-loader').show();
});
</script>
@stop