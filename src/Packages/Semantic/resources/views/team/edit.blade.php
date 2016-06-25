@extends('dashboard')

@section('content')

<div class="ui fluid container">
    <div class="row">
        <div class="column">
            <h1>Team Editor</h1>
        </div>
    </div>
    <div class="row">
        <div class="ui two column grid">
            <div class="column">
                <div>
                    {!! Form::model($team, ['route' => ['teams.update', $team->id], 'method' => 'patch', 'class' => 'ui form']) !!}
                        <div class="field">
                            @input_maker_label('Name')
                            @input_maker_create('name', ['type' => 'string'], $team)
                        </div>
                        {!! Form::submit('Update', ['class' => 'ui button primary right floated']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="column">
                @if (Auth::user()->isTeamAdmin($team->id))
                    {!! Form::model($team, ['url' => 'teams/'.$team->id.'/invite', 'method' => 'post', 'class' => 'ui form']) !!}
                        <div class="field">
                            <label>Invite a new member</label>
                            <input type="email" name="email" placeholder="Email">
                        </div>
                        {!! Form::submit('Invite', ['class' => 'ui button teal right floated']) !!}
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        @if (Auth::user()->isTeamAdmin($team->id))
            <h2 class="text-center">Members</h2>
            @if ($team->members->isEmpty())
            <div class="row">
                <div class="column raw-margin-bottom-24">
                    <div class="well text-center">No members found.</div>
                </div>
            </div>
            @else
                <table class="ui striped table">
                    <thead>
                        <th>Name</th>
                        <th class="text-right">Action</th>
                    </thead>
                    <tbody>
                        @foreach($team->members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td class="text-right">
                                    @if (! $member->isTeamAdmin($team->id))
                                        <a class="ui button red mini right floated" href="{{ url('teams/'.$team->id.'/remove/'.$member->id) }}" onclick="return confirm('Are you sure you want to remove this member?')">Remove</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
</div>

@stop

