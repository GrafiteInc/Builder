<?php

namespace App\Repositories\Team;

use App\Repositories\User\User;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
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
