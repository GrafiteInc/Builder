<?php

namespace {{App\}}Services;

use DB;
use Schema;
use Stripe\Stripe;
use Carbon\Carbon;
use Stripe\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * Account methods for billing controls
 */
class AccountService
{
    protected $user;
    protected $config;
    protected $subscription;
    protected $inBillingCycle;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->config = Config::get('plans');
        $this->subscription = $this->user->meta->subscription($this->config['subscription_name']);
        $this->inBillingCycle = false;

        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Sets the persepctive to the current billing cycle
     * @return Account
     */
    public function currentBillingCycle()
    {
        $this->inBillingCycle = true;
        return $this;
    }

    /**
     * Validates a user can access
     * @param  string $area
     * @return bool
     */
    public function canAccess($area)
    {
        $accessCollection = $this->config['plans'][$this->subscription->stripe_plan]['access'];

        if (in_array($area, $accessCollection)) {
            return true;
        }

        return false;
    }

    /**
     * Validates a user cannot access
     * @param  string $area
     * @return bool
     */
    public function cannotAccess($area)
    {
        if ($this->canAccess($area)) {
            return false;
        }

        return true;
    }

    /**
     * Get a clause
     * @param  string $key
     * @return int
     */
    public function getClause($key)
    {
        return $this->config['plans'][$this->subscription->stripe_plan][$key];
    }

    /**
     * Get a model's limit
     * @param  string $model
     * @return int
     */
    public function getLimit($model)
    {
        return $this->config['plans'][$this->subscription->stripe_plan]['limits'][$model];
    }

    /**
     * Check if user is in their limits
     * @param  string $model
     * @return bool
     */
    public function withinLimit($model, $key = 'user_id', $value = null)
    {
        if (is_null($value)) {
            $value = $this->user->id;
        }

        $limit = $this->config['plans'][$this->subscription->stripe_plan]['limits'][$model];

        if (Schema::hasTable($model)) {
            $newModel = DB::table($model);
        } else {
            $newModel = app($model);
        }

        $countQuery = $newModel->where($key, $value);

        if ($this->inBillingCycle) {
            $customer = Customer::retrieve($this->user->meta->stripe_id);
            $stripeSubscription = $customer->subscriptions->retrieve($this->subscription->stripe_id);

            $currentPeriodStart = Carbon::createFromTimestamp($stripeSubscription->current_period_start)->format('Y-m-d h:i:s');
            $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d h:i:s');

            $countQuery
                ->where('created_at', '>=', $currentPeriodStart)
                ->where('created_at', '<=', $currentPeriodEnd);
        }

        $count = $countQuery->get()->count();

        if ($count < $limit) {
            return true;
        }

        return false;
    }

    /**
     * Credits used by user
     * @param  string $model
     * @return int
     */
    public function creditsUsed($model, $key = 'user_id', $value = null)
    {
        if (is_null($value)) {
            $value = $this->user->id;
        }

        $credits = $this->config['plans'][$this->subscription->stripe_plan]['credits'];

        $appModel = app($model);

        $creditQuery = $appModel->where($key, $value);

        if ($this->inBillingCycle) {
            $customer = Customer::retrieve($this->user->meta->stripe_id);
            $stripeSubscription = $customer->subscriptions->retrieve($this->subscription->stripe_id);

            $currentPeriodStart = Carbon::createFromTimestamp($stripeSubscription->current_period_start)->format('Y-m-d h:i:s');
            $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d h:i:s');

            $creditQuery
                ->where('created_at', '>=', $currentPeriodStart)
                ->where('created_at', '<=', $currentPeriodEnd);
        }

        $creditCollection = $creditQuery->get();

        foreach ($creditCollection as $item) {
            $creditCount =+ $item->{$credits['column']};
        }

        return $creditCount;
    }

    /**
     * Checks credits available for user
     * @param  string $model
     * @return int
     */
    public function creditsAvailable($model)
    {
        $credits = $this->config['plans'][$this->subscription->stripe_plan]['credits'];
        return $credits['limit'] - $this->creditsUsed($model);
    }

    /**
     * Subscription clause method
     * @param  string $key
     * @param  Closure $method
     * @param  string $model
     * @return void
     */
    public function clause($key, $method, $model = null)
    {
        $query = null;

        if (! is_null($model)) {
            $appModel = app($model);
            $query = DB::table(app($model)->table);

            if ($this->inBillingCycle) {
                $customer = Customer::retrieve($this->user->meta->stripe_id);
                $stripeSubscription = $customer->subscriptions->retrieve($this->subscription->stripe_id);

                $currentPeriodStart = Carbon::createFromTimestamp($stripeSubscription->current_period_start)->format('Y-m-d h:i:s');
                $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end)->format('Y-m-d h:i:s');

                $query = $appModel->where('created_at', '>=', $currentPeriodStart)->where('created_at', '<=', $currentPeriodEnd);
            }
        }

        $clause = $this->config['plans'][$this->subscription->stripe_plan][$key];

        return $method($this->user, $this->subscription, $clause, $query);
    }

}
