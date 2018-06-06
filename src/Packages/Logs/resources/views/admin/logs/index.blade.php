@extends('admin.dashboard')

@section('pageTitle') App Logs @stop

@section('content')
    <div class="col-md-12 text-right raw-margin-top-16">
        @if (count($dates) > 0)
            <form class="form-inline pull-left">
                <select name="date" class="form-control">
                    @foreach($dates as $date)
                        <option value="{{ $date }}">{{ $date }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary ml-2">Check Date</button>
            </form>
        @endif

        <a class="btn btn-info ml-2" href="{{ url('admin/logs?level=info') }}@if(request('date'))&date={{ request('date') }}@endif">Info</a>
        <a class="btn btn-danger ml-2" href="{{ url('admin/logs?level=error') }}@if(request('date'))&date={{ request('date') }}@endif">Error</a>
        <a class="btn btn-warning ml-2" href="{{ url('admin/logs?level=warning') }}@if(request('date'))&date={{ request('date') }}@endif">Warning</a>
        <a class="btn btn-default ml-2" href="{{ url('admin/logs?level=debug') }}@if(request('date'))&date={{ request('date') }}@endif">Debug</a>
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
