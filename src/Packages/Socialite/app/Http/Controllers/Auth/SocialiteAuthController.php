<?php

namespace {{App\}}Http\Controllers\Auth;

use DB;
use URL;
use Auth;
use Config;
use Socialite;
use {{App\}}Services\UserService;
use {{App\}}Repositories\User\User;
use {{App\}}Http\Controllers\Controller;

class SocialiteAuthController extends Controller
{

    protected $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->scopes(Config::get('services.'.$provider.'.scopes'))->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $localUser = $this->service->findByEmail($user->getEmail());

        // If we cannot find user by email locally
        if (! $localUser) {
            $data = [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => md5(rand(222222, 999999))
            ];

            $localUser = $this->createUser($data);
        }

        if (Auth::login($localUser)) {
            return redirect('dashboard');
        }

        return redirect('/login')->with('error', 'Unable to login.');
    }

    /**
     * Create a user
     *
     * @param  array $data
     *
     * @return User
     */
    public function createUser($data)
    {
        return DB::transaction(function() use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            return $this->service->create($user, $data['password']);
        });
    }
}