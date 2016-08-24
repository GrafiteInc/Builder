<?php

namespace {{App\}}Repositories\Team;

use {{App\}}Repositories\Team\Team;
use Illuminate\Support\Facades\Schema;

class TeamRepository
{
    public function __construct(Team $team)
    {
        $this->model = $team;
    }

    /**
     * Returns all teams
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($id)
    {
        return $this->model->where('user_id', $id)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Returns all paginated teams
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated($id, $paginate)
    {
        return $this->model->where('user_id', $id)->orderBy('created_at', 'desc')->paginate($paginate);
    }

    /**
     * Search Team
     *
     * @param string $payload
     *
     * @return Team
     */
    public function search($payload, $id, $paginate)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        $query->where('id', 'LIKE', '%'.$payload.'%');

        $columns = Schema::getColumnListing('teams');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$payload.'%')->where('user_id', $id);
        };

        return $query->paginate($paginate);
    }

    /**
     * Stores Team into database
     *
     * @param array $payload
     *
     * @return Team
     */
    public function create($payload)
    {
        return $this->model->create($payload);
    }

    /**
     * Find Team by given id
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find Team by name
     *
     * @param string $name
     *
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function findByName($name)
    {
        return $this->model->where('name', $name)->firstOrFail();
    }

    /**
     * Destroy Team
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Team
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Updates Team in the database
     *
     * @param int $id
     * @param array $payload
     *
     * @return Team
     */
    public function update($id, $payload)
    {
        $team = $this->model->find($id);
        $team->fill($payload);
        $team->save();

        return $team;
    }
}
