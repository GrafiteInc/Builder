@extends('dashboard')

@section('content')

<div class="ui fluid container">
    <div class="row">
        <div class="ui four column grid">
            <div class="column">
                <h1>User Admin</h1>
            </div>
            <div class="column right floated">
                <div class="raw-margin-top-16 fluid">
                    <div class="ui two column grid">
                        <div class="column">
                            <form class="ui form raw-margin-left-16 right floated" method="post" action="/admin/users/search">
                                {!! csrf_field() !!}
                                <div class="field fluid">
                                    <input class="form-control" name="search" placeholder="Search">
                                </div>
                            </form>
                        </div>
                        <div class="column">
                            <a class="ui button primary right floated" href="{{ url('admin/users/invite') }}">Invite New User</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="column raw-margin-top-24">
            <table class="ui table striped">

                <thead>
                    <th>Email</th>
                    <th class="right aligned">Actions</th>
                </thead>
                <tbody>
                    @foreach($users as $user)

                        @if ($user->id !== Auth::id())
                            <tr>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form method="post" action="{!! url('admin/users/'.$user->id) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit" class="ui button red mini right floated" onclick="return confirm('Are you sure you want to delete this user?')"><span class="fa fa-edit"></span> Delete</button>
                                    </form>
                                    <a class="ui button primary mini right floated" href="{{ url('admin/users/'.$user->id.'/edit') }}"><span class="fa fa-edit"></span> Edit</a>
                                </td>
                            </tr>
                        @endif

                    @endforeach

                </tbody>

            </table>
        </div>
    </div>
</div>

@stop
