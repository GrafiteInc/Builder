<?php

namespace App\Http\Controllers\User;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Http\Requests\NotificationRequest;

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
        $notifications = $this->service->userBasedPaginated(Auth::id());
        return view('notifications.index')->with('notifications', $notifications);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $notifications = $this->service->search($request->search, Auth::id());
        return view('notifications.index')->with('notifications', $notifications);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function read($uuid)
    {
        $notification = $this->service->findByUuid($uuid);
        $this->service->markAsRead($notification->id);

        return view('notifications.show')->with('notification', $notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            return redirect('user/notifications')->with('message', 'Successfully deleted');
        }

        return redirect('user/notifications')->with('message', 'Failed to delete');
    }
}
