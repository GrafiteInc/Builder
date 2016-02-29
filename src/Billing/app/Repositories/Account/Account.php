<?php

namespace App\Repositories\Account;

use Eloquent;
use Laravel\Cashier\Billable;
use App\Repositories\User\User;

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

    /**
     * User
     *
     * @return Relationship
     */
    public function user()
    {
        return User::where('id', $this->user_id)->first();
    }

}
