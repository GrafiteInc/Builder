@extends('dashboard')

@section('content')

<div class="ui fluid container">
    <div class="ui three column grid">
        <div class="column centered">
            <h1>Team Creator</h1>

            {!! Form::open(['route' => 'teams.store', 'class' => 'ui form']) !!}

            <div class="field">
                @input_maker_label('Name')
                @input_maker_create('name', ['type' => 'string'])
            </div>

            {!! Form::submit('Save', ['class' => 'ui button primary right floated raw-margin-top-24']) !!}

            {!! Form::close() !!}
        </div>
    </div>
</div>

@stop