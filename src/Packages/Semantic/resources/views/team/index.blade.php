@extends('dashboard')

@section('content')

<div class="ui fluid container">
    <div class="ui centered grid">
        <div class="column">

            <h1>Team Manager</h1>

            <div class="fluid raw-margin-bottom-24 raw-margin-top-24">
                <div class="ui grid">
                    <div class="four column row">
                        <div class="left floated column">
                            <a class="ui button primary left floated" href="{!! route('teams.create') !!}">Create New</a>
                        </div>
                        <div class="right floated column">
                            {!! Form::open(['url' => 'teams/search', 'class' => 'ui form right floated']) !!}
                                <div class="field">
                                    <input name="search" class="fluid" placeholder="Search">
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="fluid">
                @if ($teams->isEmpty())
                    <div class="fluid raw-margin-bottom-24">
                        <div class="well text-center">No teams found.</div>
                    </div>
                @else
                    <table class="ui striped table">
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
                                        <button type="submit" class="ui button red mini right floated" onclick="return confirm('Are you sure you want to delete this team?')"><span class="fa fa-edit"></span> Delete</button>
                                    </form>
                                    <a class="ui button primary right floated mini raw-margin-right-16" href="{!! route('teams.edit', [$team->id]) !!}"><i class="fa fa-pencil"></i> Edit</a>
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
    </div>
</div>

@stop