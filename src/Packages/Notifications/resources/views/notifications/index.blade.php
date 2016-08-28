@extends('dashboard', ['pageTitle' => 'Notifications &raquo; Index'])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right raw-margin-top-24">
                <form method="post" action="{!! url('user/notifications/search') !!}">
                    {!! csrf_field() !!}
                    <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                </form>
            </div>
            <h1 class="pull-left raw-margin-top-24">Notifications</h1>
        </div>
    </div>

    <div class="row raw-margin-top-24">
        <div class="col-md-12">
            @if ($notifications->isEmpty())
                <div class="well text-center">No notifications found.</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Date</th>
                        <th>Flag</th>
                        <th>Headline</th>
                        <th>Read</th>
                        <th width="150px">Action</th>
                    </thead>
                    <tbody>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td><a href="{!! url('user/notifications/'.$notification->uuid.'/read') !!}">{{ $notification->created_at->format('Y-m-d') }}</a></td>
                            <td>{{ ucfirst($notification->flag) }}</td>
                            <td>{{ $notification->title }}</td>
                            <td><span class="fa fa-{{ ($notification->is_read == 1) ? 'check' : 'close' }}"></span></td>
                            <td class="text-right">
                                <form method="post" action="{!! url('user/notifications/'.$notification->id.'/delete') !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="btn btn-danger btn-xs pull-right" type="submit" onclick="return confirm('Are you sure you want to delete this notification?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
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