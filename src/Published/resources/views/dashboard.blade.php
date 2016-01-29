<h1>Dashboard!</h1>

<a href="/account/settings">Settings</a><br>
<a href="/teams">Teams</a><br>
@if(Auth::user()->can('admin'))
<a href="/admin/accounts">Admin</a><br>
@endif
<a href="/logout">Logout</a>