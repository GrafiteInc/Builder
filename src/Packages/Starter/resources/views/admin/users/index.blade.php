@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<h1>User Admin</h1>
<a href="/admin/users/invite">Invite New User</a>

<form id="" method="post" action="/admin/users/search">
    {!! csrf_field() !!}
    <input name="search" placeholder="Search">
</form>

@if ($users->count() > 0)
    <table>
        <thead>
            <th>Email</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @foreach($users as $user)
                @if ($user->id !== Auth::id())
                    <tr>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ url('admin/users/'.$user->id.'/edit') }}"><span class="fa fa-edit"> Edit</span></a>
                            <form method="post" action="{{ url('admin/users/'.$user->id) }}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@else
    <p>Sorry no users</p>
@endif

<a href="/dashboard">Dashboard</a>
