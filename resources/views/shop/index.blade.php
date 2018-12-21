@extends('layouts.frontend-fullwidth')
    
@section('content')
    <div class="filter">
        <div class="container">
            <form method="GET">
            <div class="row">
                <div class="form-group col-sm">
                    {{ Form::select('category', ['' => 'All Categories'] + $categories, Input::get('category'), ['class' => 'form-control on-select']) }}
                </div>
                <div class="form-group col-sm">
                    <input type="text" class="form-control" placeholder="Rental Period">
                </div>
                <div class="form-group col-sm">
                    <select class="form-control">
                        <option>Filter By</option>
                    </select>
                </div>
                <div class="form-group col-sm">
                    {{ Form::select('sort', shop_sort(), Input::get('sort'), ['class' => 'form-control on-select']) }}
                </div>
            </div>            
            </form>
        </div>
    </div>

    <div class="products">
        <div class="container">
            <div class="row text-center">

                @foreach($rows as $row)
                <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>

                <div class="col-lg-3 col-md-4 col-sm-6">

                    <a href="{{ route('shop.single', $row->post_name)}}">
                    <div class="product-image {{ get_membership() == 'premium' || !@$postmeta->premium ? '' : 'restricted' }}">
                        <div class="mask"></div>
                        <img src="{{ has_image( str_replace('-large', '-medium', $postmeta->image) ) }}" alt="" class="img-fluid">                        
                    </div>
                    <div class="product-info">
                        <h5>{{ trans_post($row, 'post_title', '_title') }}</h5>
                        <p>Sequin Snake Laced Gown</p>
                        @if( @$postmeta->regular_price )
                        <div class="col"><h6>
                            @if( has_discount($postmeta) )
                                {{ amount_formatted($postmeta->sale_price) }}
                            @else
                                {{ amount_formatted($postmeta->regular_price) }}
                            @endif
                        </h6></div>
                        @endif

                        @if( has_discount($postmeta) )
                        <div class="col text-right"><small>
                            <s class="text-orange">{{ amount_formatted($postmeta->regular_price) }}</s></small> 
                        </div>
                        @endif 
                        </a>
                    </div>
                </div>
                @endforeach

            </div>


			@if( ! count($rows) )
			    <div class="alert alert-warning">No products found!</div>
			@endif

        </div>
    </div>
</div>



@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
@stop
