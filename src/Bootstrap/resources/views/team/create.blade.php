@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>Create A Team</h1>

            <div class="col-md-12">
                @include('partials.errors')
                @include('partials.message')
            </div>

            <div class="col-md-4 col-md-offset-4">
                {!! Form::open(['route' => 'teams.store']) !!}

                {!! FormMaker::fromTable("teams", ['name' => 'string']) !!}

                {!! Form::submit('Save', ['class' => 'btn btn-primary pull-right']) !!}

                {!! Form::close() !!}
            </div>

        </div>
    </div>

@stop