@extends('dashboard', ['pageTitle' => 'Billing &raquo; Change Card'])

@section('content')

@include('billing.tabs')

    <div class="tabs-content">
        <div role="tabpanel" class="tab-pane tab-active">

            <div class=" raw-margin-top-24">
                <div class='card-wrapper'></div>
            </div>

            <div class="col-md-6 col-md-offset-3 raw-margin-top-24 well">
                <form method="POST" action="/user/billing/change-card">

                    @include('billing.card-form')

                    <div class="clearfix">
                        <button class="btn btn-primary pull-right" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('pre-javascript')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script> Stripe.setPublishableKey('{{ Config::get("services.stripe.key") }}'); </script>
@stop