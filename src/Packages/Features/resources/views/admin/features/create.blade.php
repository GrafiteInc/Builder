@extends('admin.dashboard')

@section('pageTitle') Features @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <form class="form" method="POST" action="{{ url('admin/features') }}">
                {!! csrf_field() !!}

                <div class="form-group">
                    @input_maker_label('Key')
                    @input_maker_create('key', ['type' => 'string'])
                </div>

                <div class="raw-margin-top-24 form-group">
                    @input_maker_label('Active')
                    @input_maker_create('is_active', ['type' => 'checkbox', 'class' => 'form-check-inline'])
                </div>

                <div class="raw-margin-top-24 form-group">
                    <div class="btn-toolbar justify-content-between">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="/admin/features" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>

            </form>

        </div>
    </div>

@stop