@extends('dashboard')

@section('content')

<div class="ui fluid container">
    <div class="ui three column grid">
        <div class="column centered">
            <h1>Team Creator</h1>
            <form class="ui form" method="post" action="{{ route('teams.store') }}">
                {!! csrf_field() !!}

                @form_maker_table("teams", ['name' => 'string'])

                <div class="raw-margin-top-24">
                    <a class="ui button default left floated" href="{{ URL::previous() }}">Cancel</a>
                    <button class="ui button primary right floated" type="submit">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>

@stop