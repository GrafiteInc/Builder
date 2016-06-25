@include('partials.message')

<div class="container">

    {!! Form::model($team, ['route' => ['teams.update', $team->id], 'method' => 'patch']) !!}

    @form_maker_object($team, ['name' => 'string'])

    {!! Form::submit('Update') !!}

    {!! Form::close() !!}
</div>

@if (Auth::user()->isTeamAdmin($team->id))

<div class="">
    <p>Invite Member</p>
    {!! Form::model($team, ['url' => 'teams/'.$team->id.'/invite', 'method' => 'post']) !!}

    <input type="email" name="email" placeholder="Email">

    {!! Form::submit('Invite') !!}

    {!! Form::close() !!}
</div>

<div class="">
    <h2>Members</h2>
    <table>
        <thead>
            <th>Name</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach($team->members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>
                        @if (! $member->isTeamAdmin($team->id))
                            <a href="{{ url('teams/'.$team->id.'/remove/'.$member->id) }}" onclick="return confirm('Are you sure you want to remove this member?')">Remove</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif

<a href="/dashboard">Dashboard</a>