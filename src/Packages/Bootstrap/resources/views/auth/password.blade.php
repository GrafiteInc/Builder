@extends('layouts.master')

@section('app-content')

    <div class="row">
        <div class="col-md-4 col-md-offset-4">

            <h1 class="text-center">Forgot Password</h1>

            <form method="POST" action="/password/email">
                {!! csrf_field() !!}
                @include('partials.errors')
                @include('partials.status')
                <div class="col-md-12 pull-left">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
                </div>
                <div class="col-md-12 pull-left raw-margin-top-24">
                    <a class="btn btn-default pull-left" href="/login">Wait I remember!</a>
                    <button class="btn btn-primary pull-right" type="submit" class="button">Send Password Reset Link</button>
                </div>
            </form>

        </div>
    </div>

@stop
