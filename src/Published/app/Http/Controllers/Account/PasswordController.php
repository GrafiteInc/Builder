<?php

namespace App\Http\Controllers\Account;

use Hash;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\AccountService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectPath = '/account/password';

    public function __construct(AccountService $accountService)
    {
        $this->service = $accountService;
    }

    /**
     * User wants to change their password
     *
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {
        $account = $request->user();

        if ($account) {
            return view('account.password')
            ->with('account', $account);
        }

        return back()->withErrors(['Could not find user']);
    }

    /**
     * Change the user's password and return
     *
     * @param  UpdatePasswordRequest $request
     * @return Response
     */
    public function update(UpdatePasswordRequest $request)
    {
        $password = $request->new_password;

        if (Hash::check($request->old_password, Auth::user()->password)) {
            $this->resetPassword(Auth::user(), $password);
            return redirect('account/settings')
                ->with('message', 'Password updated successfully');
        }

        return back()->withErrors(['Password could not be updated']);
    }
}
