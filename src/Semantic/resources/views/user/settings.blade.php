@extends('dashboard')

@section('content')

<div class="ui fluid container">
    <div class="ui centered grid">
        <div class="column">

            <h1>Settings</h1>

            <form class="ui form" method="POST" action="/user/settings">
                {!! csrf_field() !!}

                <div class="field">
                    @input_maker_label('Email')
                    @input_maker_create('email', ['type' => 'string'], $user)
                </div>

                <div class="field">
                    @input_maker_label('Name')
                    @input_maker_create('name', ['type' => 'string'], $user)
                </div>

                @include('user.meta')

                @if ($user->roles->first()->name === 'admin' || $user->id == 1)
                    <div class="field">
                        @input_maker_label('Role')
                        @input_maker_create('roles', ['type' => 'relationship', 'model' => 'App\Repositories\Role\Role', 'label' => 'label', 'value' => 'name', 'class' => 'ui fluid dropdown'], $user)
                    </div>
                @endif

                <div class="field">
                    <a class="ui button violet left" href="{{ URL::previous() }}">Cancel</a>
                    <button class="ui button primary right floated" type="submit">Save</button>
                    <a class="ui button teal right floated raw-margin-right-16" href="/user/password">Change Password</a><br>
                </div>
            </form>
        </div>
    </div>

@stop
