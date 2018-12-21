@extends('layouts.frontend-with-sidebar')

@section('header')
<div class="bg-white">
    <div class="container">
        <div class="mb-4 px-4 py-4">
            <h2 class="font-weight-bold text-uppercase">{{ trans_post($cat, 'plural_name', '_plural_name') }}</h2>
            
            @if( $sub_title = trans_post($cat, 'post_content', '_description') )
            <p class="font-italic text-muted">{{ $sub_title }}</p>
            @endif

        </div>    
    </div>
</div>
@stop

@section('content')
<section class="mb-4 bg-white rounded">

    @foreach($rows as $row)
    <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
    <div class="row pt-4 px-4">
        <div class="col-md-4">
            <div class="shadow-3d">
                <div class="img-container mb-2 box-shadow" style="max-height: 200px;">
                  <img class="img-fluid" src="{{ has_image( str_replace('large', 'medium', @$postmeta->image) ) }}">
                </div>              
            </div>
        </div>
        <div class="col-md-8">
            <h4>{{ trans_post($row, 'post_title', '_title') }}</h4>
            <p class="card-text text-justify">
                <?php $post_content = trans_post($row, 'post_content', '_content'); ?>
                {!! str_limit(strip_tags($post_content), 350, '...') !!}
            </p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <a href="{{ url($category.'/'.$row->post_name) }}" class="btn btn-sm btn-outline-primary">{{ trans('backend.read_more') }}</a>
                    
                </div>
                <small class="text-muted">{{ time_ago($row->created_at) }}</small>
            </div>
        </div>
    </div>
    <hr class="mt-4 mb-2 dashed">
    @endforeach

</section>

{{ $rows->links() }}

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
