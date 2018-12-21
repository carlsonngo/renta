<div class="bg-white nav flex-column nav-pills rounded customer-menu">
  <a href="{{ route('shop.customer.index') }}" class="nav-link {{ actived(Request::segment(2), '') }}">Dashboard</a>
  <a href="{{ route('shop.customer.orders') }}" class="nav-link {{ actived(Request::segment(2), 'orders') }}">Orders</a>
  <a href="{{ route('shop.customer.addresses') }}" class="nav-link {{ actived(Request::segment(2), 'addresses') }}">Addresses</a>
  <a href="{{ route('shop.customer.account') }}" class="nav-link {{ actived(Request::segment(2), 'account') }}">Account Details</a>
  <a href="{{ route('auth.logout') }}" class="nav-link">Logout</a>
</div>        

