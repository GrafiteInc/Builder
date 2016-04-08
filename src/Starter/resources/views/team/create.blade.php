@include('partials.message')

<div class="container">

    {!! Form::open(['route' => 'teams.store']) !!}

    @form_maker_table("teams", ['name' => 'string'])

    {!! Form::submit('Save') !!}

    {!! Form::close() !!}

</div>