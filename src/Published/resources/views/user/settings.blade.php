@include('partials.errors')

@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<form method="POST" action="/user/settings">
    {!! csrf_field() !!}

    <div>
        Email
        <input type="email" name="email" value="{{ $user->email }}">
    </div>

    <div>
        Name
        <input type="name" name="name" value="{{ $user->name }}">
    </div>

    @include('user.meta')

    @if ($user->roles->first()->name === 'admin' || $user->id == 1)
        <div>
            Role
            <select name="role">
                @foreach(App\Repositories\Role\Role::all() as $role)
                    <option @if($user->roles->first()->id === $role->id) selected @endif value="{{ $role->name }}">{{ $role->label }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div>
        <button type="submit">Save</button>
    </div>
</form>

<a href="/user/password">Change Password</a><br>
<a href="/dashboard">Dashboard</a>