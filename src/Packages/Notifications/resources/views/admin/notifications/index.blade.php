@extends('dashboard', ['pageTitle' => 'Notifications &raquo; Index'])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right raw-margin-top-24">
                <form method="post" action="{!! url('admin/notifications/search') !!}">
                    {!! csrf_field() !!}
                    <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                </form>
            </div>
            <h1 class="pull-left raw-margin-top-24">Notifications</h1>
            <a class="btn btn-primary pull-right raw-margin-top-24 raw-margin-right-16" href="{!! url('admin/notifications/create') !!}">Add New</a>
        </div>
    </div>

    <div class="row raw-margin-top-24">
        <div class="col-md-12">
            @if ($notifications->isEmpty())
                <div class="well text-center">No notifications found.</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Title</th>
                        <th>User</th>
                        <th>Flag</th>
                        <th>Read</th>
                        <th width="150px">Action</th>
                    </thead>
                    <tbody>
                    @foreach($notifications as $notification)
                        <tr>
                            <td><a href="{!! route('admin.notifications.edit', [$notification->id]) !!}">{{ $notification->title }}</a></td>
                            <td>{{ Notifications::getUser($notification->user_id)->name }}</td>
                            <td><span class="text-{{ $notification->flag }}">{{ ucfirst($notification->flag) }}</span></td>
                            <td>@if($notification->is_read) Yes @else No @endif</td>
                            <td class="text-right">
                                <form method="post" action="{!! url('admin/notifications/'.$notification->id) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="btn btn-danger btn-xs pull-right" type="submit" onclick="return confirm('Are you sure you want to delete this notification?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                                <a class="btn btn-default btn-xs pull-right raw-margin-right-16" href="{!! route('admin.notifications.edit', [$notification->id]) !!}"><i class="fa fa-pencil"></i> Edit</a>
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