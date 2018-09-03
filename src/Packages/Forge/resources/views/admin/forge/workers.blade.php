@extends('admin.dashboard')

@section('pageTitle') Forge @stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        Create Worker
                    </div>
                    <div class="card-body">
                        <form class="form" method="post" action="{{ url('admin/forge/workers') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="">Connection</label>
                                        <input class="form-control" type="text" name="connection" placeholder="Connection" value="database">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="">Queue</label>
                                        <input class="form-control" type="text" name="queue" placeholder="Queue" value="default">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="">Timeout</label>
                                        <input class="form-control" type="text" name="timeout" placeholder="Timeout" value="0">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="">Sleep</label>
                                        <input class="form-control" type="text" name="sleep" placeholder="Sleep" value="10">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="">Tries</label>
                                        <input class="form-control" type="text" name="tries" placeholder="Tries" value="3">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label" for="">Processes (optional)</label>
                                        <input class="form-control" type="text" name="processes" placeholder="Processes" value="1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-outline-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt-4">
                <h4>Queue Workers</h4>

                @if (count($workers) == 0)
                    <div class="card mt-4">
                        <div class="card-body">
                            There are no workers for this site
                        </div>
                    </div>
                @else
                    <table class="table table-striped mt-4">
                         <tr>
                            <th>Connection</th>
                            <th>Queue</th>
                            <th>Timeout</th>
                            <th>Sleep</th>
                            <th>Tries</th>
                            <th>Processes</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                        @foreach ($workers as $worker)
                            <tr>
                                <td>{{ $worker->connection }}</td>
                                <td>{{ $worker->queue }}</td>
                                <td>{{ $worker->timeout }}</td>
                                <td>{{ $worker->sleep }}</td>
                                <td>{{ $worker->tries }}</td>
                                <td>{{ $worker->processes }}</td>
                                <td>{{ $worker->status }}</td>
                                <td>
                                    <form method="post" action="{!! url('admin/forge/workers') !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <input type="hidden" name="worker_id" value="{{ $worker->id }}">
                                        <button class="btn btn-danger btn-sm pull-right" type="submit" onclick="return confirm('Are you sure you want to delete this worker?')"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>
@stop
