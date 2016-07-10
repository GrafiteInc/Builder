@extends('dashboard', ['pageTitle' => 'Billing &raquo; Details'])

@section('content')

@include('billing.tabs')

    <div id="cancelSubscription" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Whoa, are you sure?</h4>
                </div>
                <div class="modal-body">
                    <p class="lead">You'll miss us when we're gone.</p>
                    <p>If you're positive you want to cancel your subscription please click below.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a class="btn btn-danger float-right" href="{{ url('user/billing/cancellation') }}">Cancel My Subscription</a>
                </div>
            </div>
        </div>
    </div>

    <div class="tabs-content">
        <div role="tabpanel" class="tab-pane tab-active">

            <div class="col-md-6 col-md-offset-3 raw-margin-top-24">
                <div class="form-group">
                    <p class="lead">Upcoming Invoice</p>
                    <h2 class="subheading">&dollar;{{ $invoice->total/100 }} <small>on {{ $invoiceDate->format('F j, Y') }}</small></h2>
                </div>
                <div class="form-group">
                    <label for="number">Credit Card</label>
                    <input class="form-control" disabled type="text" name="number" value="**** **** **** {{ $user->meta->card_last_four }}">
                </div>
                <div class="form-group">
                    <a class="btn btn-danger pull-right" data-toggle="modal" data-target="#cancelSubscription">Cancel Subscription</a>
                </div>
            </div>

        </div>
    </div>

@stop
