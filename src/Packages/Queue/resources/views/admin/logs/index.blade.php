@extends('admin.dashboard')

@section('pageTitle') App Logs @stop

@section('content')
    <div class="col-md-12 text-right raw-margin-top-16">
        <a class="btn btn-info raw-margin-left-8" href="{{ url('admin/logs?level=info') }}">Info</a>
        <a class="btn btn-danger raw-margin-left-8" href="{{ url('admin/logs?level=error') }}">Error</a>
        <a class="btn btn-warning raw-margin-left-8" href="{{ url('admin/logs?level=warning') }}">Warning</a>
        <a class="btn btn-default raw-margin-left-8" href="{{ url('admin/logs?level=debug') }}">Debug</a>
    </div>

    <div class="raw-margin-top-24">
        <div class="col-md-12">
            @if ($logs->isEmpty())
                <div class="well text-center">No logs found.</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th width="200px">Date</th>
                        <th width="100px">Level</th>
                        <th>Log</th>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log['date'] }}</td>
                                <td>{{ ucfirst($log['level']) }}</td>
                                <td><code>{{ $log['log'] }}</code></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="col-md-12 text-center">
        {!! $logs !!}
    </div>
@stop
