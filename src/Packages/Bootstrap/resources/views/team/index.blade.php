@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <form id="" class="pull-right raw-margin-top-24 raw-margin-left-24" method="post" action="/teams/search">
                {!! csrf_field() !!}
                <input class="form-control" name="search" placeholder="Search">
            </form>
            <a class="btn btn-primary pull-right raw-margin-top-24" href="{{ url('teams/create') }}">Create Team</a>
            <h1>Teams</h1>
        </div>
    </div>
    <div class="row raw-margin-top-24">
        <div class="col-md-12">
            @if ($teams->isEmpty())
                <div class="well text-center">No teams found.</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Name</th>
                        <th width="200px" class="text-right">Action</th>
                    </thead>
                    <tbody>
                        @foreach($teams as $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td>
                                    <form method="post" action="{!! url('teams/'.$team->id) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button class="btn btn-danger btn-xs pull-right" type="submit" onclick="return confirm('Are you sure you want to delete this team?')"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                    <a class="btn btn-warning pull-right btn-xs raw-margin-right-16" href="{!! route('teams.edit', [$team->id]) !!}"><i class="fa fa-pencil"></i> Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row text-center">
                    {!! $teams; !!}
                </div>
            @endif
        </div>
    </div>

@stop