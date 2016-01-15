<?php

namespace App\Repositories\Role;

use Eloquent;
use App\Repositories\User\User;

class Role extends Eloquent
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * A Roles users
     *
     * @return Relationship
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Find a role by name
     *
     * @param  string $name
     * @return Role
     */
    public static function findByName($name)
    {
        return Role::where('name', $name)->first();
    }

}
