<?php

namespace App\Repositories\Account;

class AccountRepository
{
    public function __construct(Account $account)
    {
        $this->model = $account;
    }

    /**
     * Update an account
     * @param  int $userId User Id
     * @param  array $inputs
     * @return boolean
     */
    public function update($userId, $inputs)
    {
        $account = $this->findByUserId($userId);

        if (! isset($inputs['marketing'])) {
            $inputs['marketing'] = 0;
        }

        $account->fill($inputs);
        return $account->save();
    }

    /**
     * Find by user Id
     * @param  int $userId
     * @return Account
     */
    public function findByUserId($userId)
    {
        return $this->model->firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Delete an account
     *
     * @param  int $id
     * @return boolean
     */
    public function destroy($id)
    {
        return $this->model->where('user_id', $id)->delete();
    }

}
