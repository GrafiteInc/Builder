@extends('admin.dashboard')

@section('pageTitle') Notifications @stop

@section('content')

    <div class="row">
        <div class="col-md-12">

            <form method="post" action="{!! url('admin/notifications/'.$notification->id) !!}">
                {!! csrf_field() !!}
                {!! method_field('PATCH') !!}

                <div class="form-group">
                    @input_maker_label('Flag')
                    @input_maker_create('flag', ['type' => 'select', 'options' => [
                        'Info' => 'info',
                        'Success' => 'success',
                        'Warning' => 'warning',
                        'Danger' => 'danger',
                    ]], $notification)
                </div>
                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('User')
                    @input_maker_create('user_id', ['type' => 'select', 'options' => Notifications::usersAsOptions(), 'custom' => 'disabled' ], $notification)
                </div>
                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Title')
                    @input_maker_create('title', ['type' => 'string'], $notification)
                </div>
                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Details')
                    @input_maker_create('details', ['type' => 'textarea'], $notification)
                </div>

                <div class="raw-margin-top-24 form-group">
                    <div class="btn-toolbar justify-content-between">
                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                        <a href="/admin/notifications" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>

            </form>

        </div>
    </div>

@stop
