<?php 
/* 
    Template Name: Default
*/
?>
<!-- BEGIN HEAD -->
@include('frontend/partials/head')
<!-- END HEADER -->

<!-- BEGIN HEAD -->
@include('frontend/partials/header')
<!-- END HEADER -->

<?php $title = trans_post($info, 'post_title', '_title'); ?>

<div class="container mt-4">
 
    @if( $title )
    <div class="my-4">
        <h2 class="text-uppercase">{{ $title }}</h2>
        @if( $sub_title = trans_post($info, 'sub_title', '_sub_title') )
        <p class="font-italic text-muted mb-0">{{ $sub_title }}</p>
        @endif
    </div>
    @endif
    
    @if( @$info->image )
    <div class="img-container rounded mb-4">
        <img src="{{ asset($info->image) }}">
    </div>
    @endif

	<section class="mb-5">
	    <div class="text-justify">
            @if($info->post_type == 'post')
            <p class="text-right">{{ trans('backend.date_posted') }} : {{ time_ago($info->created_at) }}</p>

            {{ label_tags($info->tags) }}

            <hr>

            @endif
            {!! trans_post($info, 'post_content', '_content') !!}

	    </div>
	</section>


</div>

<!-- BEGIN FOOTER -->
@include('frontend/partials/footer')
<!-- END FOOTER -->

