<?php

namespace App\Services;

use DB;
use Auth;
use Mail;
use Config;
use Exception;
use App\Repositories\User\UserRepository;
use App\Repositories\Account\AccountRepository;

class AccountService
{
    public function __construct(AccountRepository $accountRepo, UserRepository $userRepo)
    {
        $this->accountRepo = $accountRepo;
        $this->userRepo = $userRepo;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function allAccounts()
    {
        return $this->userRepo->all(Auth::id());
    }

    public function getAccount($id)
    {
        return $this->userRepo->find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    /**
     * Create a user's account
     *
     * @param  User $user User
     * @return User
     */
    public function create($user, $password)
    {
        try {
            DB::beginTransaction();
                // create the user meta
                $this->accountRepo->findByUserId($user->id);
                // Set the user's role
                $this->userRepo->assignRole('member', $user->id);
                // Email the user about their account
                Mail::send('emails.new-account', ['user' => $user, 'password' => $password], function ($m) use ($user) {
                    $m->from('info@app.com', 'App');
                    $m->to($user->email, $user->name)->subject('You have a new account!');
                });
            DB::commit();

            return $user;
        } catch (Exception $e) {
            throw new Exception("We were unable to generate your account, please try again later.", 1);
        }
    }

    /**
     * Update a user's account
     *
     * @param  int $userId User Id
     * @param  array $inputs Account info
     * @return boolean
     */
    public function update($userId, $inputs)
    {
        try {
            DB::beginTransaction();
                $accountResult = $this->accountRepo->update($userId, $inputs['account']);
                $userResult = $this->userRepo->update($userId, [
                    'email' => $inputs['email'],
                    'name' => $inputs['name'],
                ]);
                $this->userRepo->unassignAllRoles($userId);
                $this->userRepo->assignRole($inputs['role'], $userId);
            DB::commit();
        } catch (Exception $e) {
            throw new Exception("We were unable to update your account", 1);
        }

        return ($accountResult && $userResult);
    }

    /**
     * Destroy the account
     *
     * @param  int $id
     * @return bool
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
                $this->userRepo->unassignAllRoles($id);
                $accountResult = $this->accountRepo->destroy($id);
                $userResult = $this->userRepo->destroy($id);
            DB::commit();
        } catch (Exception $e) {
            throw new Exception("We were unable to delete this account", 1);
        }

        return ($accountResult && $userResult);
    }

}
