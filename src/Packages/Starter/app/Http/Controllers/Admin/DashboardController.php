<?php

namespace {{App\}}Http\Controllers\Admin;

use {{App\}}Http\Requests;
use Illuminate\Http\Request;
use {{App\}}Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
