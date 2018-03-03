@extends('admin.dashboard')

@section('pageTitle') Notifications @stop

@section('content')
    <div class="row raw-margin-top-24">
        <div class="col-md-12">

            <form class="ui form" method="POST" action="{{ url('admin/notifications') }}">
                {!! csrf_field() !!}

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

                <div class="raw-margin-top-24 form-group">
                    <div class="btn-toolbar justify-content-between">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="/admin/notifications" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>

            </form>

        </div>
    </div>

@stop