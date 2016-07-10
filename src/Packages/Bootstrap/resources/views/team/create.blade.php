@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>Team Creator</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('partials.errors')
            @include('partials.message')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4 col-md-offset-4">
                {!! Form::open(['route' => 'teams.store']) !!}

                @form_maker_table("teams", ['name' => 'string'])

                {!! Form::submit('Save', ['class' => 'btn btn-primary pull-right']) !!}

                {!! Form::close() !!}
            </div>

        </div>
    </div>

@stop