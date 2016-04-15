@extends('dashboard', ['pageTitle' => 'Notifications &raquo; Create'])

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    {!! Form::open(['url' => 'admin/notifications/search']) !!}
                    <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                    {!! Form::close() !!}
                </div>
                <h1 class="pull-left" style="margin: 0;">Notifications: Create</h1>
            </div>
        </div>
        <div class="row raw-margin-top-24">
            <div class="col-md-12">

                {!! Form::open(['route' => 'admin.notifications.store']) !!}

                <div class="form-group">
                    @input_maker_label('Flag')
                    @input_maker_create('flag', ['type' => 'select', 'options' => [
                        'Info' => 'info',
                        'Success' => 'success',
                        'Warning' => 'warning',
                        'Danger' => 'danger',
                    ]])
                </div>

                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('User')
                    @input_maker_create('user_id', ['type' => 'select', 'options' => Notifications::usersAsOptions() ])
                </div>

                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Title')
                    @input_maker_create('title', ['type' => 'string'])
                </div>

                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Details')
                    @input_maker_create('details', ['type' => 'textarea'], null, 'form-control', false, false)
                </div>

                {!! Form::submit('Save', ['class' => 'btn btn-primary pull-right raw-margin-top-24']) !!}

                {!! Form::close() !!}

            </div>
        </div>
    </div>

@stop