@include('partials.errors')

@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<form method="POST" action="/admin/users/{{ $user->id }}">
    <input name="_method" type="hidden" value="PATCH">
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

    <div>
        Role
        <select name="role">
            @foreach(App\Repositories\Role\Role::all() as $role)
                <option @if($user->roles->first()->id === $role->id) selected @endif value="{{ $role->name }}">{{ $role->label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <button type="submit">Save</button>
    </div>
</form>

<a href="/admin/users">Admin</a>