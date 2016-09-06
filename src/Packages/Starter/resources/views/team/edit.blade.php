@include('partials.message')

<div class="">
    <form method="post" action="{{ url('teams/'.$team->id) }}">
        {!! csrf_field() !!}
        {!! method_field('PATCH') !!}

        @form_maker_object($team, ['name' => 'string'])

        <a href="{{ URL::previous() }}">Cancel</a>
        <button type="submit">Create</button>

    </form>
</div>

@if (Auth::user()->isTeamAdmin($team->id))

<div class="">
    <p>Invite Member</p>
    <form method="post" action="{{ url('teams/'.$team->id.'/invite') }}">
        {!! csrf_field() !!}
        <input type="email" name="email" placeholder="Email">
        <button type="submit">Create</button>
    </form>
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