@extends('dashboard')

@section('pageTitle') Teams @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-between">
                <a class="btn btn-primary raw-margin-right-8" href="{{ url('teams/create') }}">Create Team</a>
                <form class="form-inline" method="post" action="/teams/search">
                    {!! csrf_field() !!}
                    <input class="form-control mr-sm-2" name="search" type="search" value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 raw-margin-top-24">
            @if ($teams->isEmpty())
                <div class="card card-default text-center">
                    <div class="card-body">
                        <span>No teams found.</span>
                    </div>
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Name</th>
                        <th width="165px" class="text-right">Action</th>
                    </thead>
                    <tbody>
                        @foreach($teams as $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td>
                                    <div class="btn-toolbar pull-right">
                                        <a class="btn btn-outline-primary btn-sm raw-margin-right-8" href="{!! route('teams.edit', [$team->id]) !!}"><i class="fa fa-edit"></i> Edit</a>
                                        <form class="form" method="post" action="{!! url('teams/'.$team->id) !!}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this team?')"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
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