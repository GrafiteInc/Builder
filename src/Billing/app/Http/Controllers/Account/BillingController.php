<?php

namespace App\Http\Controllers\Account;

use Auth;
use Activity;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
    /**
     * Billing selection
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscribe(Request $request)
    {
        $user = $request->user();
        $invoice = $user->account->upcomingInvoice();

        if ($user->account->subscribed('main') && ! is_null($invoice)) {
            return view('billing.details')
                ->with('invoice', $invoice)
                ->with('invoiceDate', Carbon::createFromTimestamp($invoice->date))
                ->with('account', $user);
        }

        return view('billing.subscribe')
            ->with('account', $user);
    }

    /**
     * Create a subscription
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function postSubscribe(Request $request)
    {
        $inputs = $request->all();
        $creditCardToken = $inputs['stripeToken'];

        try {
            Auth::user()->account->newSubscription('main', env('SUBSCRIPTION'))->create($creditCardToken);
            return redirect('account/billing/details')->with('message', 'You\'re now subscribed!');
        } catch (Exception $e) {
            throw new Exception("Could not process the billing please try again.", 1);
        }

        return back()->withErrors(['Could not complete billing, please try again.']);
    }

    /**
     * change a credit card
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getChangeCard(Request $request)
    {
        $user = $request->user();

        return view('billing.change-card')
            ->with('account', $user);
    }

    /**
     * Save new credit card
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function postChangeCard(Request $request)
    {
        $inputs = $request->all();
        $creditCardToken = $inputs['stripeToken'];

        try {
            Auth::user()->account->updateCard($creditCardToken);
            return redirect('account/billing/details')->with('message', 'Your subscription has been updated!');
        } catch (Exception $e) {
            throw new Exception("Could not process the billing please try again.", 1);
        }

        return back()->withErrors(['Could not complete billing, please try again.']);
    }

    /**
     * Add a coupon
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getCoupon(Request $request)
    {
        $user = $request->user();

        return view('billing.coupon')
            ->with('account', $user);
    }

    /**
     * Use a coupon
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function postCoupon(Request $request)
    {
        $inputs = $request->all();

        try {
            Auth::user()->account->coupon($inputs['coupon']);
            return redirect('account/billing/details')->with('message', 'Your coupon was used!');
        } catch (Exception $e) {
            throw new Exception("Could not process the coupon please try again.", 1);
        }

        return back()->withErrors(['Could not add your coupon, please try again.']);
    }

    /**
     * Get invoices
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getInvoices(Request $request)
    {
        $user = $request->user();
        $invoices = $user->account->invoices('main');

        return view('billing.invoices')
            ->with('invoices', $invoices)
            ->with('account', $user);
    }

    /**
     * Get one invoice
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getInvoiceById($id, Request $request)
    {
        $user = $request->user();

        try {
            $response = $user->account->downloadInvoice($id, [
                'vendor'    => config("invoice.company"),
                'street'    => config("invoice.street"),
                'location'  => config("invoice.location"),
                'phone'     => config("invoice.phone"),
                'url'       => config("invoice.url"),
                'product'   => config("invoice.product"),
                'description'   => 'Subscription',
            ]);
        } catch (Exception $e) {
            $response = back()->withErrors(['Could not find this invoice, please try again.']);
        }

        return $response;
    }

    /**
     * Cancel Subscription
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscription(Request $request)
    {
        $user = $request->user();
        $invoice = $user->account->upcomingInvoice();
        $date = Carbon::createFromTimestamp($invoice->date);

        try {
            $user->account->subscription('main')->cancel();
            return redirect('account/billing/details')->with('message', 'Your subscription has been cancelled! It will be availale until '.$date);
        } catch (Exception $e) {
            throw new Exception("Could not process the cancellation please try again.", 1);
        }

        return back()->withErrors(['Could not cancel billing, please try again.']);
    }
}
