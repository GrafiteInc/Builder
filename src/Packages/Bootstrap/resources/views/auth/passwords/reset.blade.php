@extends('layouts.master')

@section('app-content')

    <div class="row">
        <div class="col-md-12 form-small">

            <h1 class="text-center">Password Reset</h1>

            <form method="POST" action="/password/reset">
                {!! csrf_field() !!}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="row">
                    <div class="col-md-12 raw-margin-top-24">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 raw-margin-top-24">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 raw-margin-top-24">
                        <label>Confirm Password</label>
                        <input class="form-control" type="password" name="password_confirmation">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 raw-margin-top-24">
                        <button class="btn btn-primary" type="submit">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop