@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <form id="" class="pull-right raw-margin-top-24 raw-margin-left-24" method="post" action="/teams/search">
                {!! csrf_field() !!}
                <input class="form-control" name="search" placeholder="Search">
            </form>
            <h1>Teams: Create</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4 col-md-offset-4">
                <form method="post" action="{{ route('teams.store') }}">
                    {!! csrf_field() !!}

                    @form_maker_table("teams", ['name' => 'string'])

                    <div class="raw-margin-top-24">
                        <a class="btn btn-default pull-left" href="{{ URL::previous() }}">Cancel</a>
                        <button class="btn btn-primary pull-right" type="submit">Create</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

@stop