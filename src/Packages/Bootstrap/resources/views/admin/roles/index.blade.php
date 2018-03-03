@extends('dashboard')

@section('pageTitle') Roles @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-between">
                <a class="btn btn-primary" href="{{ url('admin/roles/create') }}">Create Role</a>
                <form id="" class="raw-margin-left-24" method="post" action="/admin/roles/search">
                    {!! csrf_field() !!}
                    <input class="form-control" name="search"  value="{{ request('search') }}" placeholder="Search">
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 raw-margin-top-24">
            @if ($roles->isEmpty())
                <div class="card card-default text-center">
                    <div class="card-body">
                        <span>No roles found.</span>
                    </div>
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Name</th>
                        <th>Label</th>
                        <th class="text-right" width="165px">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->label }}</td>
                                <td>
                                    <div class="btn-toolbar justify-content-between">
                                        <a class="btn btn-outline-primary btn-sm raw-margin-right-8" href="{{ url('admin/roles/'.$role->id.'/edit') }}"><span class="fa fa-edit"></span> Edit</a>
                                        <form method="post" action="{!! url('admin/roles/'.$role->id) !!}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this role?')"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@stop
