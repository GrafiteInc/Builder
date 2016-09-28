<?php

namespace {{App\}}Models;

use {{App\}}Models\Role;
use {{App\}}Models\Team;
use {{App\}}Models\UserMeta;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * User UserMeta
     *
     * @return Relationship
     */
    public function meta()
    {
        return $this->hasOne(UserMeta::class);
    }

    /**
     * User Roles
     *
     * @return Relationship
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has role
     *
     * @param  string  $role
     * @return boolean
     */
    public function hasRole($role)
    {
        $roles = [];
        foreach ($this->roles->toArray() as $userRole) {
            array_push($roles, $userRole['name']);
        }
        return in_array($role, $roles);
    }



    /**
     * Teams
     *
     * @return Relationship
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Team member
     *
     * @return boolean
     */
    public function isTeamMember($id)
    {
       return array_search($id, 
                           array_column($this->teams->toArray(), 'id')
                          ) > -1;
    }

    /**
     * Team admin
     *
     * @return boolean
     */
    public function isTeamAdmin($id)
    {
        $team = $this->teams->find($id);
        return (int) $team->user_id === (int) $this->id;
    }

    /**
     * Find by Email
     *
     * @param  string $email
     * @return User
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}
