<div class="modal fade" id="cart-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">My Cart</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center py-0  text-uppercase">
        <div class="row py-3 bg-light">
            <div class="col">
                <span class="text-muted">QTY:</span> 
                <h5 class="text-dark cm-qty">{{ $cart['quantity'] ?? 0 }}</h5>
            </div>
            <div class="col">
                <span class="text-muted">Total:</span> 
                <h5 class="text-dark cm-total">{{ amount_formatted($cart['total'] ?? 0) }}</h5>
            </div>
        </div>

        <div class="row border-top border-bottom">
            <div class="col p-0 border-right">
                <a href="{{ route('shop.cart') }}" class="btn btn-block text-center p-3">View Cart</a>
            </div>
            <div class="col p-0">
                <a href="{{ route('shop.checkout') }}" class="btn btn-block text-center p-3">Checkout</a>
            </div>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-block text-center p-3">Continue Shopping</a>
      </div>
    </div>
  </div>
</div>

