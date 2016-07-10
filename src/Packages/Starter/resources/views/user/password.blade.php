@include('partials.errors')

<form method="POST" action="/user/password">
    {!! csrf_field() !!}

    <div>
        @input_maker_label('Old Password')
        @input_maker_create('old_password', ['type' => 'password', 'placeholder' => 'Old Password'])
    </div>

    <div>
        @input_maker_label('New Password')
        @input_maker_create('new_password', ['type' => 'password', 'placeholder' => 'New Password'])
    </div>

    <div>
        @input_maker_label('Confirm Password')
        @input_maker_create('new_password_confirmation', ['type' => 'password', 'placeholder' => 'Confirm Password'])
    </div>

    <div>
        <button type="submit">Save</button>
    </div>
</form>

<a href="/user/settings">Settings</a><br>
<a href="/dashboard">Dashboard</a>
