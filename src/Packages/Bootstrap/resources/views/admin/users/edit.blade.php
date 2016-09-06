@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @if (! Session::get('original_user'))
                <a class="btn btn-default pull-right" href="/admin/users/switch/{{ $user->id }}">Login as this User</a>
            @endif
            <h1>User Admin: Edit</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/admin/users/{{ $user->id }}">
                <input name="_method" type="hidden" value="PATCH">
                {!! csrf_field() !!}

                <div class="raw-margin-top-24">
                    @input_maker_label('Email')
                    @input_maker_create('email', ['type' => 'string'], $user)
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('Name')
                    @input_maker_create('name', ['type' => 'string'], $user)
                </div>

                @include('user.meta')

                <div class="raw-margin-top-24">
                    @input_maker_label('Role')
                    @input_maker_create('roles', ['type' => 'relationship', 'model' => 'App\Models\Role', 'label' => 'label', 'value' => 'name'], $user)
                </div>

                <div class="raw-margin-top-24">
                    <a class="btn btn-default pull-left" href="{{ URL::previous() }}">Cancel</a>
                    <button class="btn btn-primary pull-right" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

@stop