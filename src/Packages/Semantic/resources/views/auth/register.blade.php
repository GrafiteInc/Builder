@extends('layouts.master')

@section('app-content')

<div class="ui fluid container">
    <div class="ui three column centered grid">
        <div class="column">

            <h1 class="text-center">Register</h1>

            <form class="ui form" method="POST" action="/register">
                {!! csrf_field() !!}

                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Name" value="{{ old('name') }}">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password"  placeholder="Password" name="password">
                </div>
                <div class="field">
                    <label>Confirm Password</label>
                    <input type="password"  placeholder="Confirm Password" name="password_confirmation">
                </div>
                <div class="field">
                    <a class="ui violet button left" href="/login">Login</a>
                    <button class="ui primary button right floated" type="submit">Register</button>
                </div>
            </form>

        </div>
    </div>
</div>

@stop