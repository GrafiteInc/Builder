<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    public function getNameAttribute()
    {
        return json_decode($this->payload)->displayName;
    }
}
