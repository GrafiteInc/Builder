<?php

namespace {{App\}}Services;

use Crypto;
use {{App\}}Services\UserService;
use {{App\}}Models\Notification;
use Illuminate\Support\Facades\Schema;

class NotificationService
{
    public function __construct(
        Notification $model,
        UserService $userService
    ) {
        $this->model = $model;
        $this->userService = $userService;
    }

    /**
     * All notifications
     *
     * @return  Collection
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Paginated notifications
     *
     * @return  PaginatedCollection
     */
    public function paginated()
    {
        return $this->model->orderBy('created_at', 'desc')->paginate(env('paginate', 25));
    }

    /**
     * User based paginated notifications
     *
     * @param  integer $id
     * @return  PaginatedCollection
     */
    public function userBasedPaginated($id)
    {
        return $this->model->where('user_id', $id)->orderBy('created_at', 'desc')->paginate(env('paginate', 25));
    }

    /**
     * User based notifications
     *
     * @param  integer $id
     * @return  Collection
     */
    public function userBased($id)
    {
        return $this->model->where('user_id', $id)->where('deleted_at', null)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Search notifications
     *
     * @param  string $input
     * @param  integer $id
     * @return  Collection
     */
    public function search($input, $id)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        $query->where('id', 'LIKE', '%'.$input.'%');

        $columns = Schema::getColumnListing('notifications');

        foreach ($columns as $attribute) {
            if (is_null($id)) {
                $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
            } else {
                $query->orWhere($attribute, 'LIKE', '%'.$input.'%')->where('user_id', $id);
            }
        };

        return $query->paginate(env('paginate', 25));
    }

    /**
     * Create a notificaton
     *
     * @param  integer $userId
     * @param  string $flag
     * @param  string $title
     * @param  string $details
     * @return  void
     */
    public function notify($userId, $flag, $title, $details)
    {
        $input = [
            'user_id' => $userId,
            'flag' => $flag,
            'title' => $title,
            'details' => $details,
        ];

        $this->create($input);
    }

    /**
     * Create a notification
     *
     * @param  array $input
     * @return  boolean|exception
     */
    public function create($input)
    {
        try {
            if ($input['user_id'] == 0) {
                $users = $this->userService->all();

                foreach ($users as $user) {
                    $input['uuid'] = Crypto::uuid();
                    $input['user_id'] = $user->id;
                    $this->repo->create($input);
                }

                return true;
            }

            $input['uuid'] = Crypto::uuid();
            return $this->model->create($input);
        } catch (Exception $e) {
            throw new Exception("Could not send notifications please try agian.", 1);
        }
    }

    /**
     * Get a user
     *
     * @param  integer $id
     * @return  User
     */
    public function getUser($id)
    {
        return $this->userService->find($id);
    }

    /**
     * Find a notification
     *
     * @param  integer $id
     * @return  Notification
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a notification by UUID
     *
     * @param  string $uuid
     * @return  Notification
     */
    public function findByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    /**
     * Update a notification
     *
     * @param  integer $id
     * @param  array $input
     * @return  Notification
     */
    public function update($id, $input)
    {
        return $this->model->update($id, $input);
    }

    /**
     * Mark notification as read
     *
     * @param  integer $id
     * @return  boolean
     */
    public function markAsRead($id)
    {
        $input['is_read'] = true;
        return $this->model->update($id, $input);
    }

    /**
     * Destroy a Notification
     *
     * @param  integer $id
     * @return  boolean
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Users as Select options array
     *
     * @return  Array
     */
    public function usersAsOptions()
    {
        $users = ['All' => 0];

        foreach ($this->userService->all() as $user) {
            $users[$user->name] = $user->id;
        }

        return $users;
    }

}