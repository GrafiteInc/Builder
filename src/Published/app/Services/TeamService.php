<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Repositories\User\User;
use App\Services\AccountService;
use App\Repositories\Team\TeamRepository;
use App\Repositories\User\UserRepository;

class TeamService
{
    public function __construct(TeamRepository $teamRepository,
        UserRepository $userRepository,
        AccountService $accountService
    )
    {
        $this->repo = $teamRepository;
        $this->userRepo = $userRepository;
        $this->accountService = $accountService;
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
            $this->userRepo->joinTeam($team->id, $userId);
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

    public function destroy($id)
    {
        $team = $this->repo->find($id);
        foreach ($team->members as $member) {
            $this->userRepo->leaveTeam($id, $member->id);
        }
        return $this->repo->destroy($id);
    }

    public function invite($admin, $id, $email)
    {
        try {
            if ($admin->isTeamAdmin($id)) {
                $user = $this->userRepo->findByEmail($email);

                if (! $user) {
                    $password = Str::random(10);

                    $user = User::create([
                        'name' => $email,
                        'email' => $email,
                        'password' => bcrypt($password),
                    ]);

                    $this->accountService->create($user, $password);
                }

                if ($user->isTeamMember($id)) {
                    return false;
                }

                $this->userRepo->joinTeam($id, $user->id);

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
                $user = $this->userRepo->find($userId);

                if ($admin->isTeamAdmin($id)) {
                    $this->userRepo->leaveTeam($id, $user->id);
                    return true;
                }
            }

            return false;
        } catch (Exception $e) {
            throw new Exception("Failed to remove member", 1);
        }
    }

}