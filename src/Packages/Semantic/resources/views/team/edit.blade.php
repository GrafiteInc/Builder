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
                    <form class="ui form" method="patch" action="{{ url('teams/'.$team->id) }}">
                        {!! csrf_field() !!}
                        {!! method_field('PATCH') !!}

                        @form_maker_object($team, ['name' => 'string'])

                        <div class="raw-margin-top-24">
                            <button class="ui button primary right floated" type="submit">Create</button>
                        </div>

                    </form>
                </div>
            </div>
            <div class="column">
                @if (Auth::user()->isTeamAdmin($team->id))
                    <form class="ui form" method="post" action="{{ url('teams/'.$team->id.'/invite') }}">
                        {!! csrf_field() !!}
                        <div class="field">
                            <label>Invite a new member</label>
                            <input type="email" name="email" placeholder="Email">
                        </div>
                        <button class="ui button teal right floated" type="submit">Create</button>
                    </form>

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

