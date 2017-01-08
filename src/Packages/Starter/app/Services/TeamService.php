<?php

namespace {{App\}}Services;

use DB;
use Illuminate\Support\Str;
use {{App\}}Services\UserService;
use {{App\}}Models\User;
use {{App\}}Models\Team;
use Illuminate\Support\Facades\Schema;

class TeamService
{
    /**
     * Team Model
     * @var Team
     */
    public $model;

    /**
     * UserService
     * @var UserService
     */
    protected $userService;

    public function __construct(
        Team $model,
        UserService $userService
    ) {
        $this->model = $model;
        $this->userService = $userService;
    }

    /**
     * All teams
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function all($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    /**
     * All teams paginated
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function paginated($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(env('PAGINATE', 25));
    }

    /**
     * Search the teams
     * @param integer $userId
     * @param string $input
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function search($userId, $input)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing('teams');
        $query->where('id', 'LIKE', '%'.$input.'%');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input.'%')->where('user_id', $userId);
        };

        return $query->paginate(env('PAGINATE', 25));
    }

    /**
     * Create a team
     * @param integer $userId
     * @param array $input
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function create($userId, $input)
    {
        try {
            $team = DB::transaction(function () use ($userId, $input) {
                $input['user_id'] = $userId;
                $team = $this->model->create($input);
                $this->userService->joinTeam($team->id, $userId);
                return $team;
            });

            return $team;
        } catch (Exception $e) {
            throw new Exception("Failed to create team", 1);
        }
    }

    /**
     * Find a team
     * @param integer $id
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a team by name
     * @param string $name
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function findByName($name)
    {
        return $this->model->where('name', $name)->firstOrFail();
    }

    /**
     * Update a team
     * @param integer $id
     * @param array $input
     * @return Team
     */
    public function update($id, $input)
    {
        $team = $this->model->find($id);
        $team->update($input);

        return $team;
    }

    /**
     * Delete a team
     * @param User $user
     * @param integer $id
     * @return boolean
     */
    public function destroy($user, $id)
    {
        if ($user->isTeamAdmin($id)) {
            $team = $this->model->find($id);
            foreach ($team->members as $member) {
                $this->userService->leaveTeam($id, $member->id);
            }
            return $this->model->find($id)->delete();
        }

        return false;
    }

    /**
     * Invite a team member
     * @param User $admin
     * @param integer $id
     * @param string $email
     * @return boolean
     */
    public function invite($admin, $id, $email)
    {
        try {
            if ($admin->isTeamAdmin($id)) {
                $user = $this->userService->findByEmail($email);

                if (! $user) {
                    $password = Str::random(10);

                    $user = User::create([
                        'name' => $email,
                        'email' => $email,
                        'password' => bcrypt($password),
                    ]);

                    $this->userService->create($user, $password);
                }

                if ($user->isTeamMember($id)) {
                    return false;
                }

                $this->userService->joinTeam($id, $user->id);

                return true;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception("Failed to invite member", 1);
        }
    }

    /**
     * Remove a team member
     * @param User $admin
     * @param integer $id
     * @param integer $userId
     * @return boolean
     */
    public function remove($admin, $id, $userId)
    {
        try {
            if ($admin->isTeamAdmin($id)) {
                $user = $this->userService->find($userId);

                if ($admin->isTeamAdmin($id)) {
                    $this->userService->leaveTeam($id, $user->id);
                    return true;
                }
            }

            return false;
        } catch (Exception $e) {
            throw new Exception("Failed to remove member", 1);
        }
    }
}
