@include('partials.errors')

<form method="POST" action="/user/password">
    {!! csrf_field() !!}

    <div>
        Old Password
        <input type="password" name="old_password" placeholder="Old Password">
    </div>

    <div>
        New Password
        <input type="password" name="new_password" placeholder="New Password">
    </div>

    <div>
        Confirm Password
        <input type="password" name="new_password_confirmation" placeholder="Confirm Password">
    </div>

    <div>
        <button type="submit">Save</button>
    </div>
</form>

<a href="/user/settings">Settings</a><br>
<a href="/dashboard">Dashboard</a>