<?php

namespace {{App\}}Http\Controllers\Admin;

use Illuminate\Http\Request;
use {{App\}}Http\Controllers\Controller;
use {{App\}}Services\LogService;

class LogsController extends Controller
{
    public function __construct(LogService $logService)
    {
        $this->service = $logService;
    }

    /**
     * Display a listing of the logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $logs = $this->service->get($request->all());
        $levels = $this->service->levels;
        $dates = $this->service->getLogDates();

        return view('admin.logs.index')
            ->with('dates', $dates)
            ->with('logs', $logs)
            ->with('levels', $levels);
    }
}
