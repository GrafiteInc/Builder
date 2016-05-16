@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<h1>Role Admin</h1>

<a href="/admin/roles/create">Create New Role</a>

<form id="" method="post" action="/admin/roles/search">
    {!! csrf_field() !!}
    <input name="search" placeholder="Search">
</form>

@if ($roles->count() > 0)
    <table>
        <thead>
            <th>Name</th>
            <th>Label</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->label }}</td>
                    <td>
                        <a href="{{ url('admin/roles/'.$role->id.'/edit') }}"><span class="fa fa-edit"> Edit</span></a>
                        <form method="post" action="{{ url('admin/roles/'.$role->id) }}">
                            {!! csrf_field() !!}
                            {!! method_field('DELETE') !!}
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this role?')"><i class="fa fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>Sorry no roles</p>
@endif

<a href="/dashboard">Dashboard</a>