@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>Account Settings</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('partials.errors')
            @include('partials.message')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/account/settings">
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

                @if ($account->roles->first()->name === 'admin' || $account->id == 1)
                    <div class="col-md-12 raw-margin-top-24">
                       <label> Role</label>
                        <select class="form-control" name="role">
                            @foreach(App\Repositories\Role\Role::all() as $role)
                                <option @if($account->roles->first()->id === $role->id) selected @endif value="{{ $role->name }}">{{ $role->label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-12 raw-margin-top-24">
                    <a class="btn btn-default pull-left" href="{{ URL::previous() }}">Cancel</a>
                    <button class="btn btn-primary pull-right" type="submit">Save</button>
                    <a class="btn btn-info pull-right raw-margin-right-16" href="/account/password">Change Password</a><br>
                </div>
            </form>

        </div>
    </div>

@stop
