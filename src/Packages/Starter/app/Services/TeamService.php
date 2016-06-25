<?php

namespace {{App\}}Services;

use Illuminate\Support\Str;
use {{App\}}Services\UserService;
use {{App\}}Repositories\User\User;
use {{App\}}Repositories\Team\TeamRepository;

class TeamService
{
    public function __construct(
        TeamRepository $teamRepository,
        UserService $userService
    ) {
        $this->repo = $teamRepository;
        $this->userService = $userService;
    }

    public function all($userId)
    {
        return $this->repo->all($userId);
    }

    public function paginated($userId)
    {
        return $this->repo->paginated($userId, env('paginate', 25));
    }

    public function search($userId, $input)
    {
        return $this->repo->search($input, $userId, env('paginate', 25));
    }

    public function create($userId, $input)
    {
        try {
            $input['user_id'] = $userId;
            $team = $this->repo->create($input);
            $this->userService->joinTeam($team->id, $userId);
            return $team;
        } catch (Exception $e) {
            throw new Exception("Failed to create team", 1);
        }
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function findByName($name)
    {
        return $this->repo->findByName($name);
    }

    public function update($id, $input)
    {
        return $this->repo->update($id, $input);
    }

    public function destroy($user, $id)
    {
        if ($user->isTeamAdmin($id)) {
            $team = $this->repo->find($id);
            foreach ($team->members as $member) {
                $this->userService->leaveTeam($id, $member->id);
            }
            return $this->repo->destroy($id);
        }

        return false;
    }

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
