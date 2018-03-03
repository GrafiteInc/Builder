@extends('dashboard')

@section('pageTitle') Users: Invite @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/admin/users/invite">
                {!! csrf_field() !!}

                <div>
                    @input_maker_label('Email')
                    @input_maker_create('email', ['type' => 'string'])
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('Name')
                    @input_maker_create('name', ['type' => 'string'])
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('Role')
                    @input_maker_create('roles', ['type' => 'relationship', 'model' => 'App\Models\Role', 'label' => 'label', 'value' => 'name'])
                </div>

                <div class="raw-margin-top-24">
                    <div class="btn-toolbar justify-content-between">
                        <button class="btn btn-primary" type="submit">Invite</button>
                        <a class="btn btn-secondary" href="/admin/users">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop