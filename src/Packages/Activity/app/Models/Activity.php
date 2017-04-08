<?php

namespace {{App\}}Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public $table = "activities";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'user_id',
        'description',
        'request',
    ];

    public static $rules = [
        'request' => 'required',
    ];
}
