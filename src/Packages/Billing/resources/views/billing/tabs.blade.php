@section('pageTitle') Billing @stop

<ul class="nav nav-pills justify-content-center" role="tablist">
    <li class="nav-item {{ Request::is('user/billing/details') ? 'active' : '' }}">
        <a class="nav-link" role="tab" href="{{ url('user/billing/details') }}">Billing</a>
    </li>
    @can('access-billing', Auth::user())
        <li class="nav-item {{ Request::is('user/billing/change-card') ? 'active' : '' }}">
            <a class="nav-link" role="tab" href="{{ url('user/billing/change-card') }}">Change Card</a>
        </li>
        <li class="nav-item {{ Request::is('user/billing/coupon') ? 'active' : '' }}">
            <a class="nav-link" role="tab" href="{{ url('user/billing/coupon') }}">Coupon</a>
        </li>
        <li class="nav-item {{ Request::is('user/billing/invoices') ? 'active' : '' }}">
            <a class="nav-link" role="tab" href="{{ url('user/billing/invoices') }}">Invoices</a>
        </li>
    @endcan
</ul>