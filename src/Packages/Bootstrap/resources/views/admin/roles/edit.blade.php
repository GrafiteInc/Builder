@extends('dashboard')

@section('pageTitle') Roles: Edit @stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/admin/roles/{{ $role->id }}">
                <input name="_method" type="hidden" value="PATCH">
                {!! csrf_field() !!}

                <div>
                    @input_maker_label('Name')
                    @input_maker_create('name', ['type' => 'string'], $role)
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('Label')
                    @input_maker_create('label', ['type' => 'string'], $role)
                </div>

                <div class="raw-margin-top-24">
                    <h3>Permissions</h3>
                    @foreach(Config::get('permissions', []) as $permission => $name)
                        <div class="checkbox">
                            <label for="{{ $name }}">
                                @if (stristr($role->permissions, $permission))
                                    <input type="checkbox" name="permissions[{{ $permission }}]" id="{{ $name }}" checked>
                                @else
                                    <input type="checkbox" name="permissions[{{ $permission }}]" id="{{ $name }}">
                                @endif
                                {{ $name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="raw-margin-top-24">
                    <div class="btn-toolbar justify-content-between">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <a class="btn btn-secondary" href="/admin/roles">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop