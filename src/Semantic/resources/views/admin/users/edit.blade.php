@extends('dashboard')

@section('content')

<div class="ui fluid container">
    <div class="row">
        <div class="column">
            <h1>User Admin: Edit</h1>
        </div>
    </div>
    <div class="row raw-margin-top-24">
        <div class="column">
            <form class="ui form" method="POST" action="/admin/users/{{ $user->id }}">
                <input name="_method" type="hidden" value="PATCH">
                {!! csrf_field() !!}

                <div class="field raw-margin-top-24">
                    @input_maker_label('Email')
                    @input_maker_create('email', ['type' => 'string'], $user)
                </div>

                <div class="field raw-margin-top-24">
                    @input_maker_label('Name')
                    @input_maker_create('name', ['type' => 'string'], $user)
                </div>

                @include('user.meta')

                <div class="field raw-margin-top-24">
                    @input_maker_label('Role')
                    @input_maker_create('roles', ['type' => 'relationship', 'model' => 'App\Repositories\Role\Role', 'label' => 'label', 'value' => 'name', 'class' => 'ui fluid dropdown'], $user)
                </div>

                <div class="field raw-margin-top-24">
                    <a class="ui button violet left" href="{{ URL::previous() }}">Cancel</a>
                    <button class="ui button primary right floated" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

@stop
