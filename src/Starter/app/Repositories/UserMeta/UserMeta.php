<?php

namespace App\Repositories\UserMeta;

use App\Repositories\User\User;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'phone',
        'marketing',
        'terms_and_cond',
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
