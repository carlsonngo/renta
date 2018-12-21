@if(@$slider->post_content && @$slider->post_status == 'actived')
<div id="carouselExampleIndicators" class="fullslide carousel slide mb-5" data-ride="carousel">
    <ol class="carousel-indicators mb-5">
        @foreach(json_decode($slider->post_content) as $s_k => $s_v)
        <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $s_k; ?>" class="<?php echo $s_k==0 ? 'active' : ''; ?>"></li>
        @endforeach
    </ol>
    <div class="carousel-inner ">
        @foreach(json_decode($slider->post_content) as $s_k => $s_v)
        <div class="carousel-item <?php echo $s_k==0 ? 'active' : ''; ?>" style="background-image: url({{ asset($s_v) }});"></div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">{{ trans('backend.previous') }}</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">{{ trans('backend.next') }}</span>
    </a>
</div>
@else
<br><br><br>
@endif