<ul class="nav nav-sidebar">
    <li @if(Request::is('dashboard', 'dashboard/*')) class="active" @endif>
        <a href="{!! url('dashboard') !!}"><span class="fa fa-dashboard"></span> Dashboard</a>
    </li>
    <li @if(Request::is('user/settings', 'user/password')) class="active" @endif>
        <a href="{!! url('user/settings') !!}"><span class="fa fa-user"></span> Settings</a>
    </li>
    <li @if(Request::is('teams', 'teams/*')) class="active" @endif>
        <a href="{!! url('teams') !!}"><span class="fa fa-users"></span> Teams</a>
    </li>
    @if (Gate::allows('admin'))
        <li class="sidebar-header"><span>Admin</span></li>
        <li @if(Request::is('admin/users', 'admin/users/*')) class="active" @endif>
            <a href="{!! url('admin/users') !!}"><span class="fa fa-users"></span> Users</a>
        </li>
        <li @if(Request::is('admin/roles', 'admin/roles/*')) class="active" @endif>
            <a href="{!! url('admin/roles') !!}"><span class="fa fa-lock"></span> Roles</a>
        </li>
    @endif
</ul>