<?php

namespace App\Http\Controllers\Account;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\AccountService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAccountRequest;

class SettingsController extends Controller
{
    public function __construct(AccountService $accountService)
    {
        $this->service = $accountService;
    }

    /**
     * View current user's settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request)
    {
        $account = $request->user();

        if ($account) {
            return view('account.settings')
            ->with('account', $account);
        }

        return back()->withErrors(['Could not find user']);
    }

    /**
     * Update the account
     *
     * @param  UpdateAccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountRequest $request)
    {
        if ($this->service->update(Auth::id(), $request->all())) {
            return back()->with('message', 'Settings updated successfully');
        }

        return back()->withErrors(['Could not update user']);
    }
}
