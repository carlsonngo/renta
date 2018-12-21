<header class="navbar navbar-expand-md bg-dark navbar-dark sticky-top mb-3 d-none d-md-flex">
        <div class="text-white small">
            {{ date('l - F d, Y') }}<br>
            {{ date('h:i:s A') }}            
        </div>
    <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
        @include('backend.partials.header-right')
    </ul>
</header>