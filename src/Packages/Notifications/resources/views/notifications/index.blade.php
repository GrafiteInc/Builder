@extends('dashboard')

@section('pageTitle') Notifications @stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-between">
                <form method="post" action="{!! url('user/notifications/search') !!}">
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
                        <th>Date</th>
                        <th>Flag</th>
                        <th>Headline</th>
                        <th>Read</th>
                        <th width="150px" class="text-right">Action</th>
                    </thead>
                    <tbody>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td><a href="{!! url('user/notifications/'.$notification->uuid.'/read') !!}">{{ $notification->created_at->format('Y-m-d') }}</a></td>
                            <td>{{ ucfirst($notification->flag) }}</td>
                            <td>{{ $notification->title }}</td>
                            <td><span class="fas fa-{{ ($notification->is_read == 1) ? 'check' : 'times' }}"></span></td>
                            <td class="text-right">
                                <form method="post" action="{!! url('user/notifications/'.$notification->id.'/delete') !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this notification?')"><i class="fa fa-trash"></i> Delete</button>
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