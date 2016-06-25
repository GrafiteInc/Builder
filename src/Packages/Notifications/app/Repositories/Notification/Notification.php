<?php

namespace {{App\}}Repositories\Notification;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table = "notifications";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'user_id',
        'flag',
        'uuid',
        'title',
        'details',
        'is_read',
    ];

    public static $rules = [
        'title' => 'required',
        'details' => 'required',
        'flag' => 'required',
        'user_id' => 'required',
    ];

}
