<?php

namespace {{App\}}Http\Controllers;

use Auth;
use Gate;
use Exception;
use Illuminate\Http\Request;
use {{App\}}Services\TeamService;
use {{App\}}Http\Requests\TeamCreateRequest;
use {{App\}}Http\Controllers\Controller;
use {{App\}}Http\Requests\UserInviteRequest;
use {{App\}}Http\Requests\TeamUpdateRequest;

class TeamController extends Controller
{
    public function __construct(TeamService $teamService)
    {
        $this->service = $teamService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teams = $this->service->paginated($request->user()->id);
        return view('team.index')->with('teams', $teams);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $teams = $this->service->search($request->user()->id, $request->search);
        return view('team.index')->with('teams', $teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('team.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\TeamCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamCreateRequest $request)
    {
        try {
            $result = $this->service->create(Auth::id(), $request->except('_token'));

            if ($result) {
                return redirect('teams/'.$result->id.'/edit')->with('message', 'Successfully created');
            }

            return redirect('teams')->with('message', 'Failed to create');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified team.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByName($name)
    {
        $team = $this->service->findByName($name);

        if (Gate::allows('team-member', [$team, Auth::user()])) {
            return view('team.show')->with('team', $team);
        }

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = $this->service->find($id);
        return view('team.edit')->with('team', $team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeamUpdateRequest $request, $id)
    {
        try {
            $result = $this->service->update($id, $request->except('_token'));

            if ($result) {
                return back()->with('message', 'Successfully updated');
            }

            return back()->with('message', 'Failed to update');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = $this->service->destroy(Auth::user(), $id);

            if ($result) {
                return redirect('teams')->with('message', 'Successfully deleted');
            }

            return redirect('teams')->with('message', 'Failed to delete');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Invite a team member
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inviteMember(UserInviteRequest $request, $id)
    {
        try {
            $result = $this->service->invite(Auth::user(), $id, $request->email);

            if ($result) {
                return back()->with('message', 'Successfully invited member');
            }

            return back()->with('message', 'Failed to invite member - they may already be a member');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove a team member
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function removeMember($id, $userId)
    {
        try {
            $result = $this->service->remove(Auth::user(), $id, $userId);

            if ($result) {
                return back()->with('message', 'Successfully removed member');
            }

            return back()->with('message', 'Failed to remove member');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
