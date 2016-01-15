@include('partials.message')

<div class="container">

    {!! Form::open(['route' => 'teams.store']) !!}

    {!! FormMaker::fromTable("teams", ['name' => 'string']) !!}

    {!! Form::submit('Save') !!}

    {!! Form::close() !!}

</div>