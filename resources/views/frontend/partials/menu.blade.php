@if( @$menu->children )
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      {{ $menu->label }}
    </a>

    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
    @each('frontend.partials.menu-child',  $menu->children, 'menu')
    </div>

</li>
@else
<li class="nav-item">
    <a class="nav-link" href="{{ $menu->value }}">{{ $menu->label }} <span class="sr-only">(current)</span></a>
</li>
@endif
