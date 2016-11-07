@extends('layouts.master')

@section('app-content')

<div class="ui fluid container">
    <div class="ui three column grid">
        <div class="column centered">

            <h1>Activate</h1>
            <p>Please check your email to activate your account.</p>
            <a class="ui button green fluid" href="{{ url('activate/send-token') }}">Request new Token</a>

        </div>
    </div>
</div>

@stop

