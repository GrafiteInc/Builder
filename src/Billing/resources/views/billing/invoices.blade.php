@extends('dashboard', ['pageTitle' => 'Billing &raquo; Invoices'])

@section('content')

@include('billing.tabs')

    <div class="tabs-content">
        <div role="tabpanel" class="tab-pane tab-active">
            <div class="raw-margin-top-24 raw-margin-left-24 raw-margin-right-24">

                <table class="table table-striped">

                    <thead>
                        <th>Date</th>
                        <th>Identifier</th>
                        <th>Dollars</th>
                    </thead>

                    <tbody>

                        @foreach($invoices as $invoice)
                        <tr>
                            <td><a href="{{ url('user/billing/invoice/'.$invoice->id) }}">{{ date('Y-m-d', $invoices[0]->date) }}</a></td>
                            <td>{{ $invoice->id }}</td>
                            <td>${{ ($invoice->total / 100) }}</td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>
        </div>
    </div>

@stop
