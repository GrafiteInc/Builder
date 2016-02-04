@extends('dashboard')

@section('content')

    <div class="row">
        <h1>Admin Edit: Account</h1>

        <div class="col-md-12">
            @include('partials.errors')
            @include('partials.message')

            <form method="POST" action="/admin/accounts/{{ $account->id }}">
                <input name="_method" type="hidden" value="PATCH">
                {!! csrf_field() !!}

                <div class="col-md-12 raw-margin-top-24">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" value="{{ $account->email }}">
                </div>

                <div class="col-md-12 raw-margin-top-24">
                   <label> Name</label>
                    <input class="form-control" type="name" name="name" value="{{ $account->name }}">
                </div>

                @include('account.account')

                <div class="col-md-12 raw-margin-top-24">
                   <label> Role</label>
                    <select class="form-control" name="role">
                        @foreach(App\Repositories\Role\Role::all() as $role)
                            <option @if($account->roles->first()->id === $role->id) selected @endif value="{{ $role->name }}">{{ $role->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 raw-margin-top-24">
                    <a class="btn btn-default pull-left" href="{{ URL::previous() }}">Cancel</a>
                    <button class="btn btn-primary pull-right" type="submit">Save</button>
                </div>
            </form>

        </div>
    </div>

@stop