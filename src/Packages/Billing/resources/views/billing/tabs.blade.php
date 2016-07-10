<div class="row">
    <div class="col-md-12">
        <h1>Billing</h1>
    </div>
</div>

<ul class="nav nav-tabs raw-margin-top-24" role="tablist">
    <li role="presentation" class="tabs-title {{ Request::is('user/billing/details') ? 'active' : '' }}">
        <a href="{{ url('user/billing/details') }}">Billing</a>
    </li>
    @can('access-billing', Auth::user())
        <li role="presentation" class="tabs-title {{ Request::is('user/billing/change-card') ? 'active' : '' }}">
            <a href="{{ url('user/billing/change-card') }}">Change Card</a>
        </li>
        <li role="presentation" class="tabs-title {{ Request::is('user/billing/coupon') ? 'active' : '' }}">
            <a href="{{ url('user/billing/coupon') }}">Coupon</a>
        </li>
        <li role="presentation" class="tabs-title {{ Request::is('user/billing/invoices') ? 'active' : '' }}">
            <a href="{{ url('user/billing/invoices') }}">Invoices</a>
        </li>
    @endcan
</ul>