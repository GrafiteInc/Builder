<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\AccountService;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function __construct(AccountService $accountService)
    {
        $this->service = $accountService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = $this->service->allAccounts();
        return view('admin.index')->with('accounts', $accounts);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = $this->service->getAccount($id);
        return view('admin.edit')->with('account', $account);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->service->update($id, $request->except(['_token', '_method']));

        if ($result) {
            return back()->with('message', 'Successfully updated');
        }

        return back()->with('message', 'Failed to update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            return redirect('admin/accounts')->with('message', 'Successfully deleted');
        }

        return redirect('admin/accounts')->with('message', 'Failed to delete');
    }
}
