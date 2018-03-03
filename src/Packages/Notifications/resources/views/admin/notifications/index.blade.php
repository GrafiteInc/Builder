@extends('admin.dashboard')

@section('pageTitle') Notifications @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-between">
                <a class="btn btn-primary raw-margin-right-8" href="{!! url('admin/notifications/create') !!}">Create Notification</a>
                <form method="post" action="{!! url('admin/notifications/search') !!}">
                    {!! csrf_field() !!}
                    <input class="form-control form-inline pull-right" name="search" value="{{ request('search') }}" placeholder="Search">
                </form>
            </div>
        </div>
    </div>

    <div class="row raw-margin-top-24">
        <div class="col-md-12">
            @if ($notifications->isEmpty())
                <div class="card text-center">
                    <div class="card-body">
                        No notifications found.
                    </div>
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Title</th>
                        <th>User</th>
                        <th>Flag</th>
                        <th>Read</th>
                        <th class="text-right" width="165px">Action</th>
                    </thead>
                    <tbody>
                    @foreach($notifications as $notification)
                        <tr>
                            <td><a href="{!! route('admin.notifications.edit', [$notification->id]) !!}">{{ $notification->title }}</a></td>
                            <td>{{ Notifications::getUser($notification->user_id)->name }}</td>
                            <td><span class="text-{{ $notification->flag }}">{{ ucfirst($notification->flag) }}</span></td>
                            <td>@if($notification->is_read) Yes @else No @endif</td>
                            <td class="text-right">
                                <div class="btn-toolbar justify-content-between">
                                    <a class="btn btn-outline-primary btn-sm raw-margin-right-8" href="{!! route('admin.notifications.edit', [$notification->id]) !!}"><i class="fa fa-edit"></i> Edit</a>
                                    <form method="post" action="{!! url('admin/notifications/'.$notification->id) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this notification?')"><i class="fa fa-trash"></i> Delete</button>
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

    <div class="row">
        <div class="col-md-12 text-center">
            {!! $notifications; !!}
        </div>
    </div>

@stop