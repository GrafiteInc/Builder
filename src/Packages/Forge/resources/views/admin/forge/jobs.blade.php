@extends('admin.dashboard')

@section('pageTitle') Forge @stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        Create Job
                    </div>
                    <div class="card-body">
                        <form class="form" method="post" action="{{ url('admin/forge/scheduler') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="form-label" for="">Command</label>
                                <input class="form-control" type="text" name="command" placeholder="Command" value="php /home/forge/{{ $site->name }}/artisan command">
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group mt-2">
                                        <label class="form-label" for="">User</label>
                                        <input class="form-control" type="text" name="user" placeholder="User" value="forge">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-2">
                                        <label class="form-label" for="">Minute</label>
                                        <input class="form-control" type="text" name="minute" placeholder="Minute" value="*">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-2">
                                        <label class="form-label" for="">Hour</label>
                                        <input class="form-control" type="text" name="hour" placeholder="Hour" value="*">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-2">
                                        <label class="form-label" for="">Day</label>
                                        <input class="form-control" type="text" name="day" placeholder="Day" value="*">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-2">
                                        <label class="form-label" for="">Month</label>
                                        <input class="form-control" type="text" name="month" placeholder="Month" value="*">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-2">
                                        <label class="form-label" for="">Weekday</label>
                                        <input class="form-control" type="text" name="weekday" placeholder="Weekday" value="*">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-outline-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt-4">
                <h4>Scheduled Jobs</h4>

                @if (count($jobs) == 0)
                    <div class="card mt-4">
                        <div class="card-body">
                            There are no jobs for this server
                        </div>
                    </div>
                @else
                    <table class="table table-striped mt-4">
                        @foreach ($jobs as $job)
                        <tr>
                            <td>{{ $job->command }}</td>
                            <td>{{ $job->user }}</td>
                            <td>{{ $job->frequency }}</td>
                            <td>{{ $job->cron }}</td>
                            <td>{{ $job->status }}</td>
                            <td>
                                <form method="post" action="{!! url('admin/forge/scheduler') !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                                    <button class="btn btn-danger btn-sm pull-right" type="submit" onclick="return confirm('Are you sure you want to delete this job?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>
@stop
