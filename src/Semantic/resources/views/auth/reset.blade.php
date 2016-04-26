@extends('layouts.master')

@section('app-content')

<div class="ui fluid container">
    <div class="ui three column centered grid">
        <div class="column">

            <h1 class="text-center">Password Reset</h1>

            <form class="ui form" method="POST" action="/password/reset">
                {!! csrf_field() !!}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password">
                </div>
                <div class="field">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation">
                </div>
                <div class="field">
                    <button class="ui button primary right floated" type="submit">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop