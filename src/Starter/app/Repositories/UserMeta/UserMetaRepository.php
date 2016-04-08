<?php

namespace App\Repositories\UserMeta;

use App\Repositories\UserMeta\UserMeta;

class UserMetaRepository
{
    public function __construct(UserMeta $userMeta)
    {
        $this->model = $userMeta;
    }

    /**
     * Update an userMeta
     * @param  int $userId User Id
     * @param  array $inputs
     * @return boolean
     */
    public function update($userId, $inputs)
    {
        $userMeta = $this->findByUserId($userId);

        if (! isset($inputs['marketing'])) {
            $inputs['marketing'] = 0;
        }

        if (! isset($inputs['terms_and_cond'])) {
            $inputs['terms_and_cond'] = 0;
        }

        $inputs['marketing'] = (bool) $inputs['marketing'];
        $inputs['terms_and_cond'] = (bool) $inputs['terms_and_cond'];

        $userMeta->fill($inputs);
        return $userMeta->save();
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
