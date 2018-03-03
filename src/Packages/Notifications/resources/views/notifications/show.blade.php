@extends('dashboard')

@section('pageTitle') Notifications @stop

@section('content')
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="row raw-margin-top-24">
                <div class="col-md-12 alert-{{ $notification->flag }}">
                    <h2 class="raw-padding-top-12 text-center"> {{ $notification->title }} <small>{{ $notification->created_at->format('Y-m-d') }}</small></h2>
                </div>
            </div>
            <div class="row raw-margin-top-24">
                <div class="col-md-12">
                    <p>{{ $notification->details }}</p>
                </div>
            </div>
        </div>
    </div>
@stop
