@extends('layouts.master')

@section('app-content')

    <div class="form-small">

        <h2 class="text-center">Forgot Password</h2>

        <form method="POST" action="/password/email">
            {!! csrf_field() !!}
            @include('partials.errors')
            @include('partials.status')

            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <button class="btn btn-primary btn-block" type="submit" class="button">Send Password Reset Link</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 raw-margin-top-24">
                    <a class="btn btn-link" href="/login">Wait I remember!</a>
                </div>
            </div>
        </form>

    </div>

@stop
