<?php

namespace {{App\}}Services;

use DB;
use Auth;
use Mail;
use Config;
use Session;
use Exception;
use {{App\}}Models\User;
use {{App\}}Models\UserMeta;
use {{App\}}Models\Team;
use {{App\}}Models\Role;
use {{App\}}Services\Traits\HasTeams;
use {{App\}}Services\Traits\HasRoles;
use {{App\}}Events\UserRegisteredEmail;
use {{App\}}Notifications\ActivateUserEmail;
use Illuminate\Support\Facades\Schema;

class UserService
{
    use HasRoles,
        HasTeams;

    /**
     * User model
     * @var User
     */
    public $model;

    /**
     * User Meta model
     * @var UserMeta
     */
    protected $userMeta;

    /**
     * Team Service
     * @var TeamService
     */
    protected $team;

    /**
     * Role Service
     * @var RoleService
     */
    protected $role;

    public function __construct(
        User $model,
        UserMeta $userMeta,
        Team $team,
        Role $role
    ) {
        $this->model = $model;
        $this->userMeta = $userMeta;
        $this->team = $team;
        $this->role = $role;
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find a user
     * @param  integer $id
     * @return User
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Search the users
     *
     * @param  string $input
     * @return mixed
     */
    public function search($input)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing('users');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
        };

        return $query->paginate(env('PAGINATE', 25));
    }

    /**
     * Find a user by email
     *
     * @param  string $email
     * @return User
     */
    public function findByEmail($email)
    {
        return $this->model->findByEmail($email);
    }

    /**
     * Find by Role ID
     * @param  integer $id
     * @return Collection
     */
    public function findByRoleID($id)
    {
        $usersWithRepo = [];
        $users = $this->model->all();

        foreach ($users as $user) {
            if ($user->roles->first()->id == $id) {
                $usersWithRepo[] = $user;
            }
        }

        return $usersWithRepo;
    }

    /**
     * Find by the user meta activation token
     *
     * @param  string $token
     * @return boolean
     */
    public function findByActivationToken($token)
    {
        $userMeta = $this->userMeta->where('activation_token', $token)->first();

        if ($userMeta) {
            return $userMeta->user()->first();
        }

        return false;
    }

    /**
     * Create a user's profile
     *
     * @param  User $user User
     * @param  string $password the user password
     * @param  string $role the role of this user
     * @param  boolean $sendEmail Whether to send the email or not
     * @return User
     */
    public function create($user, $password, $role = 'member', $sendEmail = false)
    {
        try {
            DB::transaction(function () use ($user, $password, $role, $sendEmail) {
                $this->userMeta->firstOrCreate([
                    'user_id' => $user->id
                ]);

                $this->assignRole($role, $user->id);

                if ($sendEmail) {
                    event(new UserRegisteredEmail($user, $password));
                }
            });

            $this->setAndSendUserActivationToken($user);

            return $user;
        } catch (Exception $e) {
            throw new Exception("We were unable to generate your profile, please try again later.", 1);
        }
    }

    /**
     * Update a user's profile
     *
     * @param  int $userId User Id
     * @param  array $inputs UserMeta info
     * @return User
     */
    public function update($userId, $payload)
    {
        if (isset($payload['meta']) && ! isset($payload['meta']['terms_and_cond'])) {
            throw new Exception("You must agree to the terms and conditions.", 1);
        }

        try {
            return DB::transaction(function () use ($userId, $payload) {
                $user = $this->model->find($userId);

                if (isset($payload['meta']['marketing']) && ($payload['meta']['marketing'] == 1 || $payload['meta']['marketing'] == 'on')) {
                    $payload['meta']['marketing'] = 1;
                } else {
                    $payload['meta']['marketing'] = 0;
                }

                if (isset($payload['meta']['terms_and_cond']) && ($payload['meta']['terms_and_cond'] == 1 || $payload['meta']['terms_and_cond'] == 'on')) {
                    $payload['meta']['terms_and_cond'] = 1;
                } else {
                    $payload['meta']['terms_and_cond'] = 0;
                }

                $userMetaResult = (isset($payload['meta'])) ? $user->meta->update($payload['meta']) : true;

                $user->update($payload);

                if (isset($payload['roles'])) {
                    $this->unassignAllRoles($userId);
                    $this->assignRole($payload['roles'], $userId);
                }

                return $user;
            });
        } catch (Exception $e) {
            throw new Exception("We were unable to update your profile", 1);
        }
    }

    /**
     * Invite a new member
     * @param  array $info
     * @return void
     */
    public function invite($info)
    {
        $password = substr(md5(rand(1111, 9999)), 0, 10);

        return DB::transaction(function () use ($password, $info) {
            $user = $this->model->create([
                'email' => $info['email'],
                'name' => $info['name'],
                'password' => bcrypt($password)
            ]);

            return $this->create($user, $password, $info['roles'], true);
        });
    }

    /**
     * Destroy the profile
     *
     * @param  int $id
     * @return bool
     */
    public function destroy($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $this->unassignAllRoles($id);
                $this->leaveAllTeams($id);

                $userMetaResult = $this->userMeta->where('user_id', $id)->delete();
                $userResult = $this->model->find($id)->delete();

                return ($userMetaResult && $userResult);
            });
        } catch (Exception $e) {
            throw new Exception("We were unable to delete this profile", 1);
        }
    }

    /**
     * Switch user login
     *
     * @param  integer $id
     * @return boolean
     */
    public function switchToUser($id)
    {
        try {
            $user = $this->model->find($id);
            Session::put('original_user', Auth::id());
            Auth::login($user);
            return true;
        } catch (Exception $e) {
            throw new Exception("Error logging in as user", 1);
        }
    }

    /**
     * Switch back
     *
     * @param  integer $id
     * @return boolean
     */
    public function switchUserBack()
    {
        try {
            $original = Session::pull('original_user');
            $user = $this->model->find($original);
            Auth::login($user);
            return true;
        } catch (Exception $e) {
            throw new Exception("Error returning to your user", 1);
        }
    }

    /**
     * Set and send the user activation token via email
     *
     * @param void
     */
    public function setAndSendUserActivationToken($user)
    {
        $token = md5(str_random(40));

        $user->meta()->update([
            'activation_token' => $token
        ]);

        $user->notify(new ActivateUserEmail($token));
    }
}
