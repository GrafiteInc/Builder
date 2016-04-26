@extends('layouts.master')

@section('app-content')

<div class="ui fluid container">
    <div class="ui three column centered grid">
        <div class="column">
            <h1 class="text-center">Login</h1>
            <form class="ui form" method="POST" action="/login">
                {!! csrf_field() !!}

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
                </div>

                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password" id="password">
                </div>

                <div class="field">
                    <label>
                        Remember Me <input type="checkbox" name="remember">
                    </label>
                </div>

                <div class="field">
                    <a class="ui violet button left" href="/password/email">Forgot Password</a>
                    <button class="ui primary button right floated" type="submit">Login</button>
                </div>
            </form>

            <div class="floated left raw-margin-top-24">
                <a class="ui button green fluid" href="/register">Register</a>
            </div>
        </div>
    </div>
</div>

@stop

