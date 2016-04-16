<?php

namespace {{App\}}Http\Controllers\User;

use Auth;
use {{App\}}Http\Requests;
use Illuminate\Http\Request;
use {{App\}}Services\UserService;
use {{App\}}Http\Controllers\Controller;
use {{App\}}Http\Requests\UpdateUserRequest;

class SettingsController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * View current user's settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return view('user.settings')
            ->with('user', $user);
        }

        return back()->withErrors(['Could not find user']);
    }

    /**
     * Update the user
     *
     * @param  UpdateAccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        if ($this->service->update(Auth::id(), $request->all())) {
            return back()->with('message', 'Settings updated successfully');
        }

        return back()->withErrors(['Could not update user']);
    }
}
