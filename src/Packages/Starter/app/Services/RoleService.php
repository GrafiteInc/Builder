<?php

namespace {{App\}}Services;

use DB;
use Auth;
use Config;
use Exception;
use {{App\}}Services\UserService;
use {{App\}}Repositories\Role\RoleRepository;

class RoleService
{
    public function __construct(RoleRepository $repo, UserService $userService)
    {
        $this->repo = $repo;
        $this->userService = $userService;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function all()
    {
        return $this->repo->all();
    }

    public function paginated()
    {
        return $this->repo->paginated(env('paginate', 25));
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function search($input)
    {
        return $this->repo->search($input, env('paginate', 25));
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    /**
     * Create a role
     *
     * @param  array $input
     * @return Role
     */
    public function create($input)
    {
        try {
            $role = $this->repo->create($input);
            return $role;
        } catch (Exception $e) {
            throw new Exception("Failed to create role", 1);
        }
    }

    /**
     * Update a role
     *
     * @param  int $id
     * @param  array $input
     * @return boolean
     */
    public function update($id, $input)
    {
        return $this->repo->update($id, $input);
    }

    /**
     * Destroy the role
     *
     * @param  int $id
     * @return bool
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
                $result = false;
                $userCount = count($this->userService->findByRoleID($id));
                if ($userCount == 0) {
                    $result = $this->repo->destroy($id);
                }
            DB::commit();
        } catch (Exception $e) {
            throw new Exception("We were unable to delete this role", 1);
        }

        return $result;
    }

}
