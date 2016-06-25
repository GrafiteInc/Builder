@include('partials.errors')

@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<h1>Role: Edit</h1>

<form method="POST" action="/admin/roles/{{ $role->id }}">
    <input name="_method" type="hidden" value="PATCH">
    {!! csrf_field() !!}

    <div>
        @input_maker_label('Name')
        @input_maker_create('name', ['type' => 'string'], $role)
    </div>

    <div>
        @input_maker_label('Label')
        @input_maker_create('label', ['type' => 'string'], $role)
    </div>

    <div>
        <a href="{{ URL::previous() }}">Cancel</a>
        <button type="submit">Save</button>
    </div>
</form>

<a href="/admin/roles">Role Admin</a>