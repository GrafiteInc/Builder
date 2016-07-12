@include('partials.errors')

@if (session('message'))
    <div class="">
        {{ session('message') }}
    </div>
@endif

<h1>Role: Create</h1>

<form method="POST" action="/admin/roles">
    {!! csrf_field() !!}

    <div class="">
        @input_maker_label('Name')
        @input_maker_create('name', ['type' => 'string'])
    </div>

    <div class="">
        @input_maker_label('Label')
        @input_maker_create('label', ['type' => 'string'])
    </div>

    <div>
        <h3>Permissions</h3>
        @foreach(Config::get('permissions', []) as $permission => $name)
            <div>
                <label for="{{ $name }}">
                    <input type="checkbox" name="permissions[{{ $permission }}]" id="{{ $name }}">
                    {{ $name }}
                </label>
            </div>
        @endforeach
    </div>

    <div class="">
        <a href="{{ URL::previous() }}">Cancel</a>
        <button type="submit">Save</button>
    </div>
</form>

<a href="/admin/roles">Role Admin</a>