<li class="nav-item dropdown">
    <a class="nav-item nav-link dropdown-toggle mr-md-2 py-1" href="#" data-toggle="dropdown">
        <img src="{{ has_image(App\UserMeta::get_meta(Auth::User()->id, 'profile_picture')) }}" class="img-icon img-fluid rounded-circle mr-2">
        {{ Auth::User()->firstname }}
    </a>
    <div class="dropdown-menu dropdown-menu-right" >
        <a class="dropdown-item" href="{{ route('frontend.home') }}">
            <i class="fas fa-globe-asia mr-2"></i> {{ trans('backend.view_site') }}</a>
        <a class="dropdown-item" href="{{ route('backend.users.account') }}">
            <i class="fas fa-user-circle mr-2"></i> {{ trans('backend.my_account') }}</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('auth.logout') }}">
            <i class="fas fa-sign-out-alt mr-2"></i> {{ trans('backend.logout') }}</a>
    </div>
</li>