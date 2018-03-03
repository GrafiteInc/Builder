@extends('dashboard')

@section('pageTitle') Password @stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/user/password">
                {!! csrf_field() !!}

                <div>
                    @input_maker_label('Old Password')
                    @input_maker_create('old_password', ['type' => 'password', 'placeholder' => 'Old Password'])
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('New Password')
                    @input_maker_create('new_password', ['type' => 'password', 'placeholder' => 'New Password'])
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('Confirm Password')
                    @input_maker_create('new_password_confirmation', ['type' => 'password', 'placeholder' => 'Confirm Password'])
                </div>

                <div class="raw-margin-top-24">
                    <div class="btn-toolbar justify-content-between">
                        <button class="btn btn-primary pull-right" type="submit">Save</button>
                        <a class="btn btn-secondary pull-left" href="{{ URL::previous() }}">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop
