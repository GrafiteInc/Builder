@extends('dashboard')

@section('pageTitle') Roles: Create @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/admin/roles">
                {!! csrf_field() !!}

                <div>
                    @input_maker_label('Name')
                    @input_maker_create('name', ['type' => 'string'])
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('Label')
                    @input_maker_create('label', ['type' => 'string'])
                </div>

                <div class="raw-margin-top-24">
                    <h3>Permissions</h3>
                    @foreach(Config::get('permissions', []) as $permission => $name)
                        <div class="checkbox">
                            <label for="{{ $name }}">
                                <input type="checkbox" name="permissions[{{ $permission }}]" id="{{ $name }}">
                                {{ $name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="raw-margin-top-24">
                    <div class="btn-toolbar justify-content-between">
                        <button class="btn btn-primary" type="submit">Create</button>
                        <a class="btn btn-secondary" href="/admin/roles">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop