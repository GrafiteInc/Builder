<?php

namespace {{App\}}Repositories\UserMeta;

use {{App\}}Repositories\UserMeta\UserMeta;

class UserMetaRepository
{
    public function __construct(UserMeta $userMeta)
    {
        $this->model = $userMeta;
    }

    /**
     * Update an userMeta
     * @param  int $userId User Id
     * @param  array $payload
     * @return boolean
     */
    public function update($userId, $payload)
    {
        $userMeta = $this->findByUserId($userId);

        if (! isset($payload['marketing'])) {
            $payload['marketing'] = 0;
        }

        if (! isset($payload['terms_and_cond'])) {
            $payload['terms_and_cond'] = 0;
        }

        $payload['marketing'] = (bool) $payload['marketing'];
        $payload['terms_and_cond'] = (bool) $payload['terms_and_cond'];

        $userMeta->fill($payload);
        $userMeta->save();

        return $userMeta;
    }

    /**
     * Find by user Id
     * @param  int $userId
     * @return UserMeta
     */
    public function findByUserId($userId)
    {
        return $this->model->firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Delete an userMeta
     *
     * @param  int $id
     * @return boolean
     */
    public function destroy($id)
    {
        return $this->model->where('user_id', $id)->delete();
    }

}
