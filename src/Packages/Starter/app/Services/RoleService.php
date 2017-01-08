<?php

namespace {{App\}}Services;

use DB;
use Auth;
use Config;
use Exception;
use {{App\}}Services\UserService;
use {{App\}}Models\Role;
use Illuminate\Support\Facades\Schema;

class RoleService
{
    public function __construct(
        Role $model,
        UserService $userService
    ) {
        $this->model = $model;
        $this->userService = $userService;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */


    /**
     * All roles
     * @return \Illuminate\Support\Collection|null|static|Role
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Paginated roles
     * @return \Illuminate\Support\Collection|null|static|Role
     */
    public function paginated()
    {
        return $this->model->paginate(env('PAGINATE', 25));
    }

    /**
     * Find a role
     * @param  integer $id
     * @return \Illuminate\Support\Collection|null|static|Role
     */
    public function find($id)
    {
        return $this->model->find($id);
    }


    /**
     * Search the roles
     * @param  string $input
     * @return \Illuminate\Support\Collection|null|static|Role
     */
    public function search($input)
    {
        $query = $this->model->orderBy('name', 'desc');

        $columns = Schema::getColumnListing('roles');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
        };

        return $query->paginate(env('PAGINATE', 25));
    }

    /**
     * Find Role by name
     *
     * @param string $name
     *
     * @return \Illuminate\Support\Collection|null|static|Role
     */
    public function findByName($name)
    {
        return $this->model->where('name', $name)->firstOrFail();
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
            if (isset($input['permissions'])) {
                $input['permissions'] = implode(',', array_keys($input['permissions']));
            } else {
                $input['permissions'] = null;
            }
            return $this->model->create($input);
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
        if (isset($input['permissions'])) {
            $input['permissions'] = implode(',', array_keys($input['permissions']));
        } else {
            $input['permissions'] = null;
        }

        $role = $this->model->find($id);
        $role->update($input);

        return $role;
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
            $result = DB::transaction(function () use ($id) {
                $result = false;
                $userCount = count($this->userService->findByRoleID($id));

                if ($userCount == 0) {
                    $result = $this->model->find($id)->delete();
                }

                return $result;
            });
        } catch (Exception $e) {
            throw new Exception("We were unable to delete this role", 1);
        }

        return $result;
    }
}
