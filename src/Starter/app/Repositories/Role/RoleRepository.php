<?php

namespace {{App\}}Repositories\Role;

use Schema;

class RoleRepository
{
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    /**
     * Returns all roles
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->get();
    }

    /**
     * Returns all paginated roles
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated($paginate)
    {
        return $this->model->paginate($paginate);
    }

    /**
     * Search Role
     *
     * @param string $input
     *
     * @return Role
     */
    public function search($input, $paginate)
    {
        $query = $this->model->orderBy('name', 'desc');

        $columns = Schema::getColumnListing('roles');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
        };

        return $query->paginate($paginate);
    }

    /**
     * Stores Role into database
     *
     * @param array $input
     *
     * @return Role
     */
    public function create($input)
    {
        return $this->model->create($input);
    }

    /**
     * Find Role by given id
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Role
     */
    public function find($id)
    {
        return $this->model->find($id);
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

    /**
     * Destroy Role
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Role
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Updates Role in the database
     *
     * @param int $id
     * @param array $inputs
     *
     * @return Role
     */
    public function update($id, $inputs)
    {
        $team = $this->model->find($id);
        $team->fill($inputs);
        $team->save();

        return $team;
    }
}
