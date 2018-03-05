<?php

namespace {{App\}}Http\Controllers\Admin;

use Illuminate\Http\Request;
use {{App\}}Http\Controllers\Controller;
use {{App\}}Services\NotificationService;
use {{App\}}Http\Requests\NotificationCreateRequest;
use {{App\}}Http\Requests\NotificationUpdateRequest;

class NotificationController extends Controller
{
    public function __construct(NotificationService $notificationService)
    {
        $this->service = $notificationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notifications = $this->service->paginated();
        return view('admin.notifications.index')->with('notifications', $notifications);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $notifications = $this->service->search($request->search, null);
        return view('admin.notifications.index')->with('notifications', $notifications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\NotificationCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotificationCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result && is_object($result)) {
            return redirect('admin/notifications/'.$result->id.'/edit')->with('message', 'Successfully created');
        } elseif ($result) {
            return redirect('admin/notifications')->with('message', 'Successfully created');
        }

        return redirect('admin/notifications')->with('errors', ['Failed to create']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = $this->service->find($id);
        return view('admin.notifications.edit')->with('notification', $notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\NotificationUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NotificationUpdateRequest $request, $id)
    {
        $result = $this->service->update($id, $request->except('_token'));

        if ($result) {
            return back()->with('message', 'Successfully updated');
        }

        return back()->with('errors', ['Failed to update']);
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
            return redirect('admin/notifications')->with('message', 'Successfully deleted');
        }

        return redirect('admin/notifications')->with('errors', ['Failed to delete']);
    }
}
