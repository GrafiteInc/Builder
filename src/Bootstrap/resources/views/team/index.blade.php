@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>Team Manager</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('partials.errors')
            @include('partials.message')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 raw-margin-bottom-24 raw-margin-top-24">
            <div class="pull-right">
                {!! Form::open(['url' => 'teams/search']) !!}
                <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                {!! Form::close() !!}
            </div>
            <a class="btn btn-primary pull-left" href="{!! route('teams.create') !!}">Create New</a>
        </div>

        <div class="col-md-12">
            @if ($teams->isEmpty())
                <div class="col-md-12 raw-margin-bottom-24">
                    <div class="well text-center">No teams found.</div>
                </div>
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

                <div class="row">
                    {!! $teams; !!}
                </div>
            @endif
        </div>
    </div>

@stop