@extends('dashboard', ['pageTitle' => 'Features &raquo; Index'])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right raw-margin-top-24">
                <form method="post" action="{!! url('admin/features/search') !!}">
                    {!! csrf_field() !!}
                    <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                </form>
            </div>
            <h1 class="pull-left raw-margin-top-24">Features</h1>
            <a class="btn btn-primary pull-right raw-margin-top-24 raw-margin-right-16" href="{!! url('admin/features/create') !!}">Add New</a>
        </div>
    </div>

    <div class="row raw-margin-top-24">
        <div class="col-md-12">
            @if ($features->isEmpty())
                <div class="well text-center">No features found.</div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Key</th>
                        <th>Is Active</th>
                        <th width="150px">Action</th>
                    </thead>
                    <tbody>
                    @foreach($features as $feature)
                        <tr>
                            <td><a href="{!! route('admin.features.edit', [$feature->id]) !!}">{{ $feature->key }}</a></td>
                            <td>@if ($feature->is_active) <span class="fa fa-check"></span> @endif</td>
                            <td class="text-right">
                                <form method="post" action="{!! url('admin/features/'.$feature->id) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="btn btn-danger btn-xs pull-right" type="submit" onclick="return confirm('Are you sure you want to delete this feature?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                                <a class="btn btn-default btn-xs pull-right raw-margin-right-16" href="{!! route('admin.features.edit', [$feature->id]) !!}"><i class="fa fa-pencil"></i> Edit</a>
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