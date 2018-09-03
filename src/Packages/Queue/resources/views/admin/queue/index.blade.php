@extends('admin.dashboard')

@section('pageTitle') Queue: Index @stop

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
                    <th class="text-right">Attempts</th>
                    <th class="text-right">Created On</th>
                    <th class="text-right">Actions</th>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td>{{ $job->queue }}</td>
                            <td><i>{{ $job->name }}</i></td>
                            <td class="text-right">{{ $job->attempts }}</td>
                            <td class="text-right">{{ \Carbon\Carbon::parse($job->created_at)->format('M d, Y') }}</td>
                            <td class="text-right">
                                <div class="btn-toolbar justify-content-between pull-right">
                                    <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this job?')" href="{{ url('admin/queue/jobs/'.$job->id.'/cancel/active') }}">
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

    <div class="col-md-12 text-center raw-margin-top-24">
        {!! $jobs !!}
    </div>
@stop
