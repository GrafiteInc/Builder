<?php

namespace {{App\}}Services;

use {{App\}}Notifications\ActivateUserEmail;
use {{App\}}Services\UserService;
use Illuminate\Support\Str;

class ActivateService
{
    /**
     * UserService
     *
     * @var UserService
     */
    protected $userService;

    /**
     * Construct
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Send the current user a new activation token
     *
     * @return bool
     */
    public function sendActivationToken()
    {
        $token = md5(str_random(40));

        auth()->user()->meta->update([
            'activation_token' => $token
        ]);

        return auth()->user()->notify(new ActivateUserEmail($token));
    }

    /**
     * Activate the user
     *
     * @return bool
     */
    public function activateUser($token)
    {
        $user = $this->userService->findByActivationToken($token);

        if ($user) {
            return $user->meta->update([
                'is_active' => true,
                'activation_token' => null
            ]);
        }

        return false;
    }
}
