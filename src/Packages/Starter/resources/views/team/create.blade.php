@include('partials.message')

<div class="container">

    <form method="post" action="{{ route('teams.store') }}">
        {!! csrf_field() !!}

        @form_maker_table("teams", ['name' => 'string'])

        <a href="{{ URL::previous() }}">Cancel</a>
        <button type="submit">Create</button>

    </form>

</div>