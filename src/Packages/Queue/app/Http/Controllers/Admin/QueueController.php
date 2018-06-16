<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FailedJob;
use App\Services\QueueService;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function __construct(QueueService $queueService)
    {
        $this->service = $queueService;
    }

    /**
     * Display a listing of the active jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = FailedJob::find($id);

        return view('admin.queue.show')
            ->with('job', $job);
    }

    /**
     * Display a listing of the active jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jobs = $this->service->activeJobs();

        return view('admin.queue.index')
            ->with('jobs', $jobs);
    }

    /**
     * Display a listing of the upcoming jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming(Request $request)
    {
        $jobs = $this->service->upcomingJobs();

        return view('admin.queue.index')
            ->with('jobs', $jobs);
    }

    /**
     * Display a listing of the failed jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function failed(Request $request)
    {
        $jobs = $this->service->failedJobs();

        return view('admin.queue.failed')
            ->with('jobs', $jobs);
    }

    /**
     * Retry the job
     *
     * @param  int $id
     *
     * @return Response
     */
    public function retry($id)
    {
        if ($this->service->retry($id)) {
            return back()->with('message', 'The job was successfully added back into the queue.');
        }

        return back()->withErrors('Failed to retry the job');
    }

    /**
     * Cancel the job
     *
     * @param  int $id
     * @param  string $table
     *
     * @return Response
     */
    public function cancel($id, $table)
    {
        if ($this->service->cancel($id, $table)) {
            return back()->with('message', 'The job was successfully cancelled');
        }

        return back()->withErrors('Failed to cancel the job');
    }
}
