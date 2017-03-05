@extends('dashboard', ['pageTitle' => 'Features &raquo; Create'])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right raw-margin-top-24">
                <form method="post" action="{!! url('admin/features/search') !!}">
                    {!! csrf_field() !!}
                    <input class="form-control form-inline pull-right" name="search" placeholder="Search">
                </form>
            </div>
            <h1 class="pull-left raw-margin-top-24">Features: Create</h1>
        </div>
    </div>
    <div class="row raw-margin-top-24">
        <div class="col-md-12">

            <form class="ui form" method="POST" action="{{ url('admin/features') }}">
                {!! csrf_field() !!}

                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Key')
                    @input_maker_create('key', ['type' => 'string'])
                </div>

                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Active')
                    @input_maker_create('is_active', ['type' => 'checkbox'])
                </div>

                <div class="raw-margin-top-24 form-group">
                    <button type="submit" class="btn btn-primary pull-right">Save</button>
                </div>

            </form>

        </div>
    </div>

@stop