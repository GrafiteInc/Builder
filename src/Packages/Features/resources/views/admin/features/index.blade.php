@extends('admin.dashboard')

@section('pageTitle') Features @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-between">
                <a class="btn btn-primary" href="{!! url('admin/features/create') !!}">Create Feature</a>
                <form method="post" action="{!! url('admin/features/search') !!}">
                    {!! csrf_field() !!}
                    <input class="form-control form-inline pull-right" value="{{ request('search') }}" name="search" placeholder="Search">
                </form>
            </div>
        </div>
    </div>

    <div class="row raw-margin-top-24">
        <div class="col-md-12">
            @if ($features->isEmpty())
                <div class="card text-center">
                    <div class="card-body">
                        No features found.
                    </div>
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Key</th>
                        <th>Is Active</th>
                        <th width="165px">Action</th>
                    </thead>
                    <tbody>
                    @foreach ($features as $feature)
                        <tr>
                            <td><a href="{!! route('admin.features.edit', [$feature->id]) !!}">{{ $feature->key }}</a></td>
                            <td>@if ($feature->is_active) <span class="fa fa-check"></span> @endif</td>
                            <td class="text-right">
                                <div class="btn-toolbar justify-content-between">
                                    <a class="btn btn-outline-primary btn-sm raw-margin-right-8" href="{!! route('admin.features.edit', [$feature->id]) !!}"><i class="fa fa-edit"></i> Edit</a>
                                    <form method="post" action="{!! url('admin/features/'.$feature->id) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this feature?')"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-center">
            {!! $features; !!}
        </div>
    </div>

@stop