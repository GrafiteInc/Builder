<div class="container">

    @include('partials.message')

    <div class="row">
        <div class="pull-right">
            {!! Form::open(['url' => 'teams/search']) !!}
            <input class="form-control form-inline pull-right" name="search" placeholder="Search">
            {!! Form::close() !!}
        </div>
        <h1 class="pull-left">teams</h1>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('teams.create') !!}">Add New</a>
    </div>

    <div class="row">
        @if($teams->isEmpty())
            <div class="well text-center">No teams found.</div>
        @else
            <table class="table">
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
                                <form method="post" action="{!! url('teams/'$team->id]) !!}">
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