@extends('dashboard')

@section('content')

<div class="ui fluid container">
    <div class="ui centered grid">
        <div class="column">

            <h1>Password</h1>

            <form class="ui form" method="POST" action="/user/password">
                {!! csrf_field() !!}

                <div class="field">
                    @input_maker_label('Old Password')
                    @input_maker_create('old_password', ['type' => 'password', 'placeholder' => 'Old Password'])
                </div>

                <div class="field">
                    @input_maker_label('New Password')
                    @input_maker_create('new_password', ['type' => 'password', 'placeholder' => 'New Password'])
                </div>

                <div class="field">
                    @input_maker_label('Confirm Password')
                    @input_maker_create('new_password_confirmation', ['type' => 'password', 'placeholder' => 'Confirm Password'])
                </div>

                <div class="field">
                    <a class="ui button violet left" href="{{ URL::previous() }}">Cancel</a>
                    <button class="ui button primary right floated" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
