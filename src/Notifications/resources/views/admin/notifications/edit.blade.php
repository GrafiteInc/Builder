@extends('dashboard', ['pageTitle' => 'Notifications &raquo; Edit'])

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    {!! Form::open(['url' => 'admin.notifications/search']) !!}
                    <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                    {!! Form::close() !!}
                </div>
                <h1 class="pull-left" style="margin: 0;">Notifications: Edit</h1>
                <a class="btn btn-primary pull-right" style="margin-right: 8px" href="{!! route('admin.notifications.create') !!}">Add New</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                {!! Form::model($notification, ['route' => ['admin.notifications.update', $notification->id], 'method' => 'patch']) !!}

                @input_maker_label('Flag')
                @input_maker_create('flag', ['type' => 'select', 'options' => [
                    'Info' => 'info',
                    'Success' => 'success',
                    'Warning' => 'warning',
                    'Danger' => 'danger',
                ]], $notification)

                @input_maker_label('User')
                @input_maker_create('user_id', ['type' => 'select', 'options' => Notifications::usersAsOptions() ], $notification)

                @input_maker_label('Title')
                @input_maker_create('title', ['type' => 'string'], $notification)

                @input_maker_label('Details')
                @input_maker_create('details', ['type' => 'textarea'], $notification)

                {!! Form::submit('Update', ['class' => 'btn btn-primary pull-right']) !!}

                {!! Form::close() !!}

            </div>
        </div>
    </div>

@stop
