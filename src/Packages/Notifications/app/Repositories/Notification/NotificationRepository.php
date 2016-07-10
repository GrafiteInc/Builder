<?php

namespace {{App\}}Repositories\Notification;

use {{App\}}Repositories\Notification\Notification;
use Illuminate\Support\Facades\Schema;

class NotificationRepository
{
    public function __construct(Notification $notification)
    {
        $this->model = $notification;
    }

    /**
     * Returns all notifications
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Returns all paginated
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated($paginate)
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($paginate);
    }

    /**
     * Returns all paginated by user
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function userBasedPaginated($id, $paginate)
    {
        return $this->model->where('user_id', $id)->orderBy('created_at', 'desc')->paginate($paginate);
    }

    /**
     * Returns all paginated by user
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function userBased($id)
    {
        return $this->model->where('user_id', $id)->where('deleted_at', null)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Search Notification
     *
     * @param string $input
     *
     * @return Notification
     */
    public function search($input, $paginate, $id = null)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing('notifications');

        foreach ($columns as $attribute) {
            if (is_null($id)) {
                $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
            } else {
                $query->orWhere($attribute, 'LIKE', '%'.$input.'%')->where('user_id', $id);
            }
        };

        return $query->paginate($paginate);
    }

    /**
     * Stores Notification into database
     *
     * @param array $input
     *
     * @return Notification
     */
    public function create($input)
    {
        return $this->model->create($input);
    }

    /**
     * Find Notification by given id
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Notification
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find Notification by given id
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Notification
     */
    public function findByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    /**
     * Destroy Notification
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Notification
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Updates Notification in the database
     *
     * @param int $id
     * @param array $inputs
     *
     * @return Notification
     */
    public function update($id, $inputs)
    {
        $notification = $this->model->find($id);
        $notification->fill($inputs);
        $notification->save();

        return $notification;
    }
}