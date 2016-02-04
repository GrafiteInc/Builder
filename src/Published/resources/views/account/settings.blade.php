@include('partials.errors')

@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<form method="POST" action="/account/settings">
    {!! csrf_field() !!}

    <div>
        Email
        <input type="email" name="email" value="{{ $account->email }}">
    </div>

    <div>
        Name
        <input type="name" name="name" value="{{ $account->name }}">
    </div>

    @include('account.account')

    @if ($account->roles->first()->name === 'admin' || $account->id == 1)
        <div>
            Role
            <select name="role">
                @foreach(App\Repositories\Role\Role::all() as $role)
                    <option @if($account->roles->first()->id === $role->id) selected @endif value="{{ $role->name }}">{{ $role->label }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div>
        <button type="submit">Save</button>
    </div>
</form>

<a href="/account/password">Change Password</a><br>
<a href="/dashboard">Dashboard</a>