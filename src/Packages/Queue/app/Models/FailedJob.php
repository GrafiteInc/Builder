<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'failed_jobs';

    public function getNameAttribute()
    {
        return json_decode($this->payload)->displayName;
    }

    public function getReasonAttribute()
    {
        return str_limit($this->exception, 40);
    }
}
