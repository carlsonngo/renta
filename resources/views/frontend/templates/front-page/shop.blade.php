<?php 
/* 
    Template Name: Shop
*/
?>
@extends('layouts.frontend-with-sidebar')

@section('header')
<!-- BEGIN SLIDER -->
@include('frontend/partials/slider')
<!-- END SLIDER -->
@stop

@section('content')


<?php 
    parse_str( query_vars(), $search );

    $queries = array('price', 'featured_product');
    $search['featured_product'] = 1;
    $key = 'id'; 
    $val = 'DESC';

    if( Input::get('sort') ) {
        list($key, $val) = explode('-', Input::get('sort'));
    }

    $products = App\Post::site()
                        ->search($search, ['price'], $queries)
                        ->where('post_type', 'product')
                        ->where('post_status', 'actived')
                        ->orderBy($key, $val)
                        ->paginate(9);
?>

<div class="bg-white rounded py-4 pb-2 px-4 mb-4">

    <form method="GET">
    <div class="row align-items-center mb-4">
        <div class="col-auto">{{ trans('backend.sort_by') }} :</div>
        <div class="col-auto">
            {{ Form::select('sort', shop_sort(), Input::get('sort'), ['class' => 'form-control on-select']) }}
        </div>
    </div>      
    </form>
    

    <div class="row products">
    @foreach($products as $product)
    <?php $productmeta = get_meta( $product->postMetas()->get() ); ?>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="product-item">
            <a href="{{ route('shop.single', $product->post_name)}}">
                @if( has_discount($productmeta) )
                <div class="discount">{{ discount_percentage($productmeta->sale_price, $productmeta->regular_price) }}</div>
                @endif

            <div class="img-container box-shadow">
               <img src="{{ has_image($productmeta->image) }}">
            </div>       
            <div class="px-3 pb-3">
                <div class="product-name py-2">{!! str_limit(strip_tags($product->post_title), 50, '...') !!}</div>
                <div class="row mt-4">

                    @if( @$productmeta->regular_price )
                    <div class="col"><h6 class="mb-0">
                        @if( has_discount($productmeta) )
                            {{ amount_formatted($productmeta->sale_price) }}
                        @else
                            {{ amount_formatted($productmeta->regular_price) }}
                        @endif
                    </h6></div>
                    @endif

                    @if( @$productmeta->sale_price )
                    <div class="col text-right"><small>
                        <s class="text-orange">{{ amount_formatted($productmeta->regular_price) }}</s></small> 
                    </div>
                    @endif 
                    
                </div>                    
            </div>     
            </a>  
        </div>
    </div>
    @endforeach
    </div>

    @if( ! count($products) )
        <div class="alert alert-warning">{{ trans('backend.no_products_found') }}!</div>
    @else
        <?php parse_str(query_vars(), $q); ?>
        {{ $products->appends($q)->links() }}
    @endif

</div>


@endsection

@section('style')
<style>
.container>.row {    
    margin-top: -70px;
}    
</style>
@stop

@section('plugin_script')
@stop

@section('script')
@stop
