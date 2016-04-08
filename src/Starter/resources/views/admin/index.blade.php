@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<table>

<thead>
    <th>Email</th>
    <th>Actions</th>
</thead>
<tbody>
    @foreach($users as $user)

        <tr>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ url('admin/users/'.$user->id.'/edit') }}"><span class="fa fa-edit"> Edit</span></a>
                <a href="{{ url('admin/users/'.$user->id.'/delete') }}" onclick="return confirm('Are you sure you want to delete this user?')"><span class="fa fa-edit"> Delete</span></a>
            </td>
        </tr>

    @endforeach

</tbody>

</table>

<a href="/dashboard">Dashboard</a>