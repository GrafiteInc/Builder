<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand mr-0" href="/"><span class="fa fa-cogs"></span> Brand</a>
    <ul class="navbar-nav mr-auto">
        <span class="navbar-text raw-margin-left-24 page-title">
            <a class="sidebar-toggle text-light mr-3"><i class="fa fa-bars"></i></a>
            @yield('pageTitle')
        </span>
    </ul>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            @if (Auth::user())
                <a class="nav-link" href="/logout">Sign out</a>
            @endif
        </li>
    </ul>
</nav>
