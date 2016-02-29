<?php

namespace App\Repositories\Account;

use Eloquent;
use Laravel\Cashier\Billable;

class Account extends Eloquent
{
    use Billable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'phone',
        'marketing',
        'stripe_id',
        'card_brand',
        'card_last_four',
    ];

}
