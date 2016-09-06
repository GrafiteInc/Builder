@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <form id="" class="pull-right raw-margin-top-24 raw-margin-left-24" method="post" action="/teams/search">
                {!! csrf_field() !!}
                <input class="form-control" name="search" placeholder="Search">
            </form>
            <a class="btn btn-primary pull-right raw-margin-top-24" href="{{ url('teams/create') }}">Create Team</a>
            <h1>Teams: Edit</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 raw-margin-bottom-24">
            <div>
                <form method="post" action="{{ url('teams/'.$team->id) }}">
                    {!! csrf_field() !!}
                    {!! method_field('PATCH') !!}

                    @form_maker_object($team, ['name' => 'string'])

                    <div class="raw-margin-top-24">
                        <a class="btn btn-default pull-left" href="{{ URL::previous() }}">Cancel</a>
                        <button class="btn btn-primary pull-right" type="submit">Save</button>
                    </div>

                </form>
            </div>
        </div>
        <div class="col-md-6 raw-margin-bottom-24">
            @if (Auth::user()->isTeamAdmin($team->id))
                <form method="post" action="{{ url('teams/'.$team->id.'/invite') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label>Invite a new member</label>
                        <input class="form-control" type="email" name="email" placeholder="Email">
                    </div>
                    <button class="btn btn-primary pull-right" type="submit">Invite</button>
                </form>
            @endif
        </div>
        @if (Auth::user()->isTeamAdmin($team->id))
            <h2 class="text-center">Members</h2>
            <div class="col-md-12">
                @if ($team->members->isEmpty())
                    <div class="col-md-12 raw-margin-bottom-24">
                        <div class="well text-center">No members found.</div>
                    </div>
                @else
                    <table class="table table-striped">
                        <thead>
                            <th>Name</th>
                            <th class="text-right">Action</th>
                        </thead>
                        <tbody>
                            @foreach($team->members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>
                                        @if (! $member->isTeamAdmin($team->id))
                                            <a class="btn btn-danger pull-right btn-xs" href="{{ url('teams/'.$team->id.'/remove/'.$member->id) }}" onclick="return confirm('Are you sure you want to remove this member?')">Remove</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif
    </div>

@stop

