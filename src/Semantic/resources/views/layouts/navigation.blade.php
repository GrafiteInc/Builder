<div class="ui fixed inverted menu">
    <div class="header item">
        <span class="fa fa-cogs"></span> Brand
    </div>
    <div class="header item">
        @if (Auth::user())
            <p>Signed in as {{ Auth::user()->name }}</p>
        @endif
    </div>
    <div class="ui simple dropdown item right">
        App Menu <i class="dropdown icon"></i>
        <div class="menu">
            <a class="item" href="{{ url('dashboard') }}">Dashboard</a>
        </div>
    </div>
    @if (Auth::user())
        <a class="item" href="/logout">Logout</a>
    @endif
</div>
