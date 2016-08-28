<div class="">

    @include('partials.message')

    <div class="">
        <div>
            <form id="" method="post" action="/teams/search">
                {!! csrf_field() !!}
                <input class="form-control" name="search" placeholder="Search">
            </form>
        </div>
        <a href="{!! route('teams.create') !!}">Add New</a>
        <h1>Teams</h1>
    </div>

    <div class="">
        @if($teams->isEmpty())
            <div>No teams found.</div>
        @else
            <table>
                <thead>
                    <th>Name</th>
                    <th width="50px">Action</th>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{ $team->name }}</td>
                            <td>
                                <a href="{!! route('teams.edit', [$team->id]) !!}"><i class="fa fa-pencil"></i> Edit</a>
                                <form method="post" action="{!! url('teams/'.$team->id) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this team?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row">
                {!! $teams; !!}
            </div>
        @endif
    </div>
</div>

<a href="/dashboard">Dashboard</a>