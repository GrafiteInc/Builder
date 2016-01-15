<?php

namespace App\Repositories\Team;

use Eloquent;
use App\Repositories\User\User;

class Team extends Eloquent
{
    public $table = "teams";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'user_id',
        'name',
    ];

    public static $rules = [
        'name' => 'required|unique:teams'
    ];

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

}
