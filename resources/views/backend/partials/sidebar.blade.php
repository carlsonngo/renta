<!-- BEGIN NAV -->
<nav class="sidebar navbar navbar-expand-md bg-dark navbar-dark sticky-top no-scroll">

    @if($site_logo = App\Setting::get_setting('site_logo'))
    <a class="mx-md-3 mt-md-2 mb-md-3" href="#">
        <img src="{{ asset($site_logo) }}" height="40">
    </a>
    @else
    <a class="navbar-brand mx-3 m-md-3" href="#">
        {{ App\Setting::get_setting('site_title') }}
    </a>
    @endif

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse flex-column w-100" id="navbarNavDropdown">
        <ul class="navbar-nav d-md-block w-100">

            @include('backend.partials.menu-sidebar')   

            <div class="d-md-none">
            <li class="nav-item">
                <hr class="m-2">
            </li>

            @include('backend.partials.header-right')                
            
            </div>
        </ul>
    </div>
</nav>
<!-- END NAV -->


