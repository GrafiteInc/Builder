@extends('dashboard', ['pageTitle' => 'Notifications &raquo; Show'])

@section('content')

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    {!! Form::open(['url' => 'user/notifications/search', 'method' => 'get']) !!}
                    <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                    {!! Form::close() !!}
                </div>
                <h1 class="pull-left" style="margin: 0;">Notifications</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 alert-{{ $notification->flag }}">
                <h1 style="padding-bottom: 12px;"> {{ $notification->title }} <small>{{ $notification->created_at->format('Y-m-d') }}</small></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>{{ $notification->details }}</p>
            </div>
        </div>
    </div>

@stop