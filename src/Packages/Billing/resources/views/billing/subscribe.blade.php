@extends('dashboard', ['pageTitle' => 'Billing &raquo; Subscribe'])

@section('pre-javascript')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script> Stripe.setPublishableKey('{{ Config::get("services.stripe.key") }}'); </script>
@stop

@section('content')

@include('billing.tabs')

    <div class="tabs-content">
        <div role="tabpanel" class="tab-pane tab-active">

            <div class="raw-margin-top-24">
                <div class='card-wrapper'></div>
            </div>

            <div class="col-md-6 col-md-offset-3 raw-margin-top-24 well">
                <form method="POST" action="/user/billing/subscribe">

                    @include('billing.card-form')

                    <div class="row text-right">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@stop
