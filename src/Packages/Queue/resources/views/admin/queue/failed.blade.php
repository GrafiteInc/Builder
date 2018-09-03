@extends('admin.dashboard')

@section('pageTitle') Queue: Failed @stop

@section('content')
    <div class="col-md-12 text-right raw-margin-top-16">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link @if (Request::is('admin/queue')) active @endif" href="{{ url('admin/queue') }}">Active Jobs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if (Request::is('admin/queue/upcoming')) active @endif" href="{{ url('admin/queue/upcoming') }}">Upcoming Jobs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if (Request::is('admin/queue/failed')) active @endif" href="{{ url('admin/queue/failed') }}">Failed Jobs</a>
            </li>
        </ul>
    </div>

    <div class="col-md-12 raw-margin-top-24">
        @if ($jobs->isEmpty())
            <div class="card text-center">
                <div class="card-body">No jobs found.</div>
            </div>
        @else
            <table class="table table-striped">
                <thead>
                    <th>Queue</th>
                    <th width="300px">Payload Name</th>
                    <th width="300px">Reason</th>
                    <th class="text-right">Failed On</th>
                    <th class="text-right" width="200px">Actions</th>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td>{{ $job->queue }}</td>
                            <td><i><a href="{{ url('admin/queue/jobs/'.$job->id) }}">{{ $job->name }}</a></i></td>
                            <td><i>{{ $job->reason }}</i></td>
                            <td class="text-right">{{ \Carbon\Carbon::parse($job->failed_at)->format('M d, Y') }}</td>
                            <td class="text-right">
                                <div class="btn-toolbar justify-content-between pull-right">
                                    <a class="btn btn-outline-primary btn-sm mr-2" href="{{ url('admin/queue/jobs/'.$job->id.'/retry') }}">
                                        <span class="fa fa-refresh"></span> Retry</a>
                                    <a class="btn btn-danger btn-sm" href="{{ url('admin/queue/jobs/'.$job->id.'/cancel/failed') }}" onclick="return confirm('Are you sure you want to cancel this job?')">
                                        <span class="fa fa-trash"></span> Cancel
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="col-md-12 text-center">
        {!! $jobs !!}
    </div>
@stop
