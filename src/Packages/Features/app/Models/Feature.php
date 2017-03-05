<?php

namespace {{App\}}Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    public $table = "features";

    public $primaryKey = "id";

    public $timestamps = false;

    public $fillable = [
        'key',
        'is_active',
    ];

    public static $rules = [
        'key' => 'required',
    ];
}
