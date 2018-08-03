@extends('layouts.master')

@section('app-content')

        <div class="form-small">

        <h2 class="text-center">Register</h2>

        <form method="POST" action="/register">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Name">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <label>Confirm Password</label>
                    <input class="form-control" type="password" name="password_confirmation" placeholder="Password Confirmation">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <div class="btn-toolbar justify-content-between">
                        <button class="btn btn-primary" type="submit">Register</button>
                        <a class="btn btn-link" href="/login">Login</a>
                    </div>
                </div>
            </div>
        </form>

    </div>

@stop