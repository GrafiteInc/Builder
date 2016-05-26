<h1>Dashboard!</h1>

<a href="/user/settings">Settings</a><br>
<a href="/teams">Teams</a><br>
@if(Auth::user()->can('admin'))
<h1>Admin</h1>
<a href="/admin/users">Users</a><br>
<a href="/admin/roles">Roles</a><br>
@endif

@if (Session::get('original_user'))
<a class="btn btn-default pull-right btn-xs" href="/users/switch-back">Return to your Login</a>
@endif
<a href="/logout">Logout</a>