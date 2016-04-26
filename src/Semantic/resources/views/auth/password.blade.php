@extends('layouts.master')

@section('app-content')

<div class="ui fluid container">
    <div class="ui three column centered grid">
        <div class="column">

            <h1 class="text-center">Forgot Password</h1>

            <form class="ui form" method="POST" action="/password/email">
                {!! csrf_field() !!}
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
                </div>
                <div class="field raw-margin-top-24">
                    <a class="ui button violet left" href="/login">Wait I remember!</a>
                    <button class="ui primary button right floated" type="submit" class="button">Send Password Reset Link</button>
                </div>
            </form>

        </div>
    </div>
</div>

@stop
