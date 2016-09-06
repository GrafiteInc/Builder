@include('partials.errors')

@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<form method="POST" action="/user/settings">
    {!! csrf_field() !!}

    <div>
        @input_maker_label('Email')
        @input_maker_create('email', ['type' => 'string'], $user)
    </div>

    <div>
        @input_maker_label('Name')
        @input_maker_create('name', ['type' => 'string'], $user)
    </div>

    @include('user.meta')

    @if ($user->roles->first()->name === 'admin' || $user->id == 1)
        <div>
            @input_maker_label('Role')
            @input_maker_create('roles', ['type' => 'relationship', 'model' => 'App\Models\Role', 'label' => 'label', 'value' => 'name'], $user)
        </div>
    @endif

    <div>
        <button type="submit">Save</button>
    </div>
</form>

<a href="/user/password">Change Password</a><br>
<a href="/dashboard">Dashboard</a>
