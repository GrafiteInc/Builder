<?php

namespace {{App\}}Services;

use {{App\}}Models\Activity;

class ActivityService
{
    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    /**
     * All activities
     *
     * @return  Collection
     */
    public function getByUser($userId, $paginate = null)
    {
        $query = $this->model->where('user_id', $userId);

        if (!is_null($paginate)) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }

    /**
     * Create an activity record
     *
     * @param  string $description
     * @return  Activity
     */
    public function log($description = '')
    {
        $payload = [
            'user_id' => auth()->id(),
            'description' => $description,
            'request' => json_encode([
                'url' => request()->url(),
                'method' => request()->method(),
                'query' => request()->fullUrl(),
                'secure' => request()->secure(),
                'client_ip' => request()->ip(),
                'payload' => request()->all(),
            ]),
        ];

        return $this->model->create($payload);
    }
}
