<h1>Admin Dashboard!</h1>

<a href="/user/settings">Settings</a><br>
<a href="/teams">Teams</a><br>

<h1>Admin</h1>
<a href="/admin/dashboard">Dashboard</a><br>
<a href="/admin/users">Users</a><br>
<a href="/admin/roles">Roles</a><br>

@if (Session::get('original_user'))
<a class="btn btn-default pull-right btn-xs" href="/users/switch-back">Return to your Login</a>
@endif
<a href="/logout">Logout</a>