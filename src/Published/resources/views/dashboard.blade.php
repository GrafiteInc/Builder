<h1>Dashboard!</h1>

<a href="/user/settings">Settings</a><br>
<a href="/teams">Teams</a><br>
@if(Auth::user()->can('admin'))
<a href="/admin/users">Admin</a><br>
@endif
<a href="/logout">Logout</a>