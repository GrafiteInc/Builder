@extends('dashboard', ['pageTitle' => 'Notifications &raquo; Show'])

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
        <div class="col-md-12 alert-{{ $notification->flag }}">
            <h1 class="raw-padding-bottom-12 text-center"> {{ $notification->title }} <small>{{ $notification->created_at->format('Y-m-d') }}</small></h1>
        </div>
    </div>
    <div class="row raw-margin-top-24">
        <div class="col-md-12">
            <p>{{ $notification->details }}</p>
        </div>
    </div>

@stop