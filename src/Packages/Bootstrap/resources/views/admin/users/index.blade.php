@extends('dashboard')

@section('pageTitle') Users @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-between">
                <a class="btn btn-primary" href="{{ url('admin/users/invite') }}">Invite New User</a>
                <form method="post" action="/admin/users/search">
                    {!! csrf_field() !!}
                    <input class="form-control" name="search"  value="{{ request('search') }}" placeholder="Search">
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 raw-margin-top-24">
            @if ($users->isEmpty())
                <div class="card card-default text-center">
                    <div class="card-body">
                        <span>No users found.</span>
                    </div>
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Email</th>
                        <th class="text-right" width="165px">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @if ($user->id !== Auth::id())
                                <tr>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="btn-toolbar justify-content-between">
                                            <a class="btn btn-outline-primary btn-sm raw-margin-right-8" href="{{ url('admin/users/'.$user->id.'/edit') }}"><span class="fa fa-edit"></span> Edit</a>
                                            <form method="post" action="{!! url('admin/users/'.$user->id) !!}">
                                                {!! csrf_field() !!}
                                                {!! method_field('DELETE') !!}
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@stop
