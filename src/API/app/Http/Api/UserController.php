<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * Get the user
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return $user;
    }

    /**
     * Get user profile
     * @return JSON
     */
    public function getProfile()
    {
        $user = $this->getAuthenticatedUser();
        return response()->json(compact('user'));
    }

    /**
     * Update the user
     *
     * @param  UpdateAccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postProfile(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($this->service->update($user->id, $request->all())) {
            return response()->json(compact('user'));
        }

        return response()->json(['error' => 'Could not update user']);
    }
}
