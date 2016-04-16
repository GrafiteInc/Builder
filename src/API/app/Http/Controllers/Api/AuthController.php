<?php

namespace {{App\}}Http\Controllers\Api;

use DB;
use Validator;
use Illuminate\Http\Request;
use {{App\}}Services\UserService;
use {{App\}}Repositories\User\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use {{App\}}Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * Login a user
     *
     * @param  Request $request
     * @return JSON
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    /**
     * Refresh the token
     *
     * @return JSON
     */
    public function refresh()
    {
        $newToken = JWTAuth::parseToken()->refresh();
        return response()->json(compact('newToken'));
    }

    /**
     * Register a User
     *
     * @param  Request $request
     * @return JSON
     */
    public function register(Request $request)
    {
        $data = $request->only('email', 'password');

        return DB::transaction(function() use ($data) {
            $user = User::create([
                'name' => $data['email'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $user = $this->service->create($user, $data['password']);
            $token = JWTAuth::fromUser($user);

            return response()->json(compact('token'));
        });
    }
}
