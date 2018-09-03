@extends('admin.dashboard')

@section('pageTitle') Queue: Job #{{ $job->id }} @stop

@section('content')
    <div class="col-md-12 raw-margin-top-24">
        <div class="row">
            <div class="col-md-6">
                <h4>Name: {{ $job->name }}</h4>
            </div>
            <div class="col-md-6 text-right">
                <h4>Failed At: {{ \Carbon\Carbon::parse($job->failed_at)->format('M d, Y') }}</h4>
            </div>
            <div class="col-md-12 raw-margin-top-24">
                <pre>{{ $job->exception }}</pre>
            </div>
        </div>
    </div>
@stop
