@extends('dashboard', ['pageTitle' => 'Features &raquo; Edit'])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right raw-margin-top-24">
                <form method="post" action="{!! url('admin/features/search') !!}">
                    {!! csrf_field() !!}
                    <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                </form>
            </div>
            <h1 class="pull-left raw-margin-top-24">Features: Edit</h1>
            <a class="btn btn-primary pull-right raw-margin-top-24 raw-margin-right-16" href="{!! url('admin/features/create') !!}">Add New</a>
        </div>
    </div>

    <div class="row raw-margin-top-24">
        <div class="col-md-12">

            <form method="post" action="{!! url('admin/features/'.$feature->id) !!}">
                {!! csrf_field() !!}
                {!! method_field('PATCH') !!}

                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Key')
                    @input_maker_create('key', ['type' => 'string'], $feature)
                </div>

                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Active')
                    @input_maker_create('is_active', ['type' => 'checkbox'], $feature)
                </div>

                <div class="raw-margin-top-24 form-group">
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                </div>

            </form>

        </div>
    </div>

@stop
