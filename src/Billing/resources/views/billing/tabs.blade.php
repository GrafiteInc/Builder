<div class="row">
    <div class="col-md-12">
        @include('partials.errors')
        @include('partials.message')
    </div>
</div>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="tabs-title {{ Request::is('account/billing/details') ? 'active' : '' }}">
        <a href="{{ url('account/billing/details') }}">Billing</a>
    </li>
    @can('access-billing', Auth::user())
        <li role="presentation" class="tabs-title {{ Request::is('account/billing/change-card') ? 'active' : '' }}">
            <a href="{{ url('account/billing/change-card') }}">Change Card</a>
        </li>
        <li role="presentation" class="tabs-title {{ Request::is('account/billing/invoices') ? 'active' : '' }}">
            <a href="{{ url('account/billing/invoices') }}">Invoices</a>
        </li>
    @endcan
</ul>