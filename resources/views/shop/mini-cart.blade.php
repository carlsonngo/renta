<?php $cart = session('cart'); ?>
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  <i class="fa fa-shopping-cart"></i>
</a>
<div class="dropdown-menu p-0" aria-labelledby="navbarDropdownMenuLink">

    <div class="alert bg-light text-center m-0">
        You have <b class="mc-q-t">{{ number_format($cart['quantity']) }}</b> item in your cart!
    </div>
    <div class="v-scroll">
        @if( $cart ) 
        <table class="summary-order table table-striped table-hover m-0">
            @foreach($cart['orders'] as $cart)
            <?php $varation_data = json_decode(@$cart['variation_data'], true) ?? []; ?>
            <tbody>
            <tr class="cart-item cart-{{ @$cart['id'] }}" data-qty="{{ $cart['quantity'] }}">
                <td width="50" class="pr-0">
                    <a href="{{ route('shop.single', @$cart['slug']) }}">
                        <img src="{{ has_image(str_replace('-large', '-medium', @$cart['image'])) }}" width="60" class="rounded mb-2">                       
                    </a>
                </td>
                <td>
                    <div class="mb-1">
                        <a href="{{ route('shop.remove-item', @$cart['id']) }}" class="text-danger remove-to-cart float-right" data-id="{{ @$cart['id'] }}"><i class="fa fa-times"></i></a>  
                        <p>
                            <a href="{{ route('shop.single', @$cart['slug']) }}?{{ http_build_query($varation_data) }}">{{ ucfirst(@$cart['name']) }}</a>
                            @if( @$cart['sku'] )
                            <br><span class="text-muted">sku:</span> {{ @$cart['sku'] }}
                            @endif
                        </p>                     
                    </div>

                    <div class="text-right">
                        {{ $cart['quantity'] }} x {{ amount_formatted(@$cart['item_price']) }}                              
                    </div>
                    <div class="text-right">
                        {{ amount_formatted(@$cart['total_price']) }}                                   
                    </div>  
                </td>
            </tr>                
            </tbody>
            @endforeach
        </table>  
        @endif                      

    </div>
    <hr class="m-0">
    <div class="row text-center text-uppercase">
        <div class="col border-right pr-0">        
            <a href="{{ route('shop.cart') }}" class="btn btn-block py-2">Cart</a>   
        </div>
        <div class="col pl-0">
            <a href="{{ route('shop.checkout') }}" class="btn btn-block py-2">Checkout</a>           
        </div>
    </div>
</div>
</li>


