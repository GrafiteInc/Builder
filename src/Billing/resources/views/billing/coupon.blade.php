@extends('dashboard', ['pageTitle' => 'Billing &raquo; Change Card'])

@section('content')

@include('billing.tabs')

    <div class="tabs-content">
        <div role="tabpanel" class="tab-pane tab-active">

            <div class="col-md-6 col-md-offset-3 raw-margin-top-24">
                <form method="POST" action="/user/billing/coupon">
                    {!! csrf_field() !!}

                    <div class="form-group name">
                        <label for="name">Coupon</label>
                        <input class="form-control" type="text" name="coupon" id="coupon" required placeholder="Coupon Code">
                    </div>

                    <div class="row text-right">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">Add Coupon</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop