@extends('dashboard')

@section('content')

    <div class="row">
        <h1>Account Password</h1>

        <div class="col-md-12">
            @include('partials.errors')
            @include('partials.message')

            <form method="POST" action="/account/password">
                {!! csrf_field() !!}

                <div class="col-md-12 raw-margin-top-24">
                    <label>Old Password</label>
                    <input class="form-control" type="password" name="old_password" placeholder="Old Password">
                </div>

                <div class="col-md-12 raw-margin-top-24">
                    <label>New Password</label>
                    <input class="form-control" type="password" name="new_password" placeholder="New Password">
                </div>

                <div class="col-md-12 raw-margin-top-24">
                    <label>Confirm Password</label>
                    <input class="form-control" type="password" name="new_password_confirmation" placeholder="Confirm Password">
                </div>

                <div class="col-md-12 raw-margin-top-24">
                    <a class="btn btn-default pull-left" href="{{ URL::previous() }}">Cancel</a>
                    <button class="btn btn-primary pull-right" type="submit">Save</button>
                </div>
            </form>

        </div>
    </div>

@stop
