@extends('layouts.master')

@section('app-content')

    <div class="form-small">

        <h2 class="text-center">Please sign in</h2>

        <form method="POST" action="/login">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password" placeholder="Password" id="password">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <label>
                        Remember Me <input type="checkbox" name="remember">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <div class="btn-toolbar justify-content-between">
                        <button class="btn btn-primary" type="submit">Login</button>
                        <a class="btn btn-link" href="/password/reset">Forgot Password</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <a class="btn raw100 btn-info" href="/register">Register</a>
                </div>
            </div>
        </form>

    </div>

@stop

