<?php

namespace {{App\}}Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use {{App\}}Http\Controllers\Controller;
use {{App\}}Services\ForgeService;

class ForgeController extends Controller
{
    public function __construct(ForgeService $forgeService)
    {
        $this->service = $forgeService;
    }

    public function index(Request $request)
    {
        $settings = $this->service->getSettings();
        $firewalls = $this->service->getFirewalls();
        $sites = $this->service->getSites();
        $jobCount = count($this->service->getJobs());
        $workerCount = count($this->service->getWorkers());

        return view('admin.forge.index')->with(compact('settings', 'firewalls', 'sites', 'jobCount', 'workerCount'));
    }

    public function scheduler()
    {
        $jobs = $this->service->getJobs();
        $site = $this->service->getSite();

        return view('admin.forge.jobs')
            ->with('site', $site)
            ->with('jobs', $jobs);
    }

    public function workers()
    {
        $workers = $this->service->getWorkers();

        return view('admin.forge.workers')
            ->with('workers', $workers);
    }

    public function createJob(Request $request)
    {
        $validatedData = $request->validate([
            'command' => 'required',
            'user' => 'required',
            'minute' => 'required',
            'hour' => 'required',
            'day' => 'required',
            'month' => 'required',
            'weekday' => 'required',
        ]);

        if ($this->service->createJob($validatedData)) {
            return back()->with('message', 'New Job Created');
        }

        return back()->withErrors(['Unable to create Job']);
    }

    public function deleteJob(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'job_id' => 'required',
            ]);

            $this->service->deleteJob($validatedData['job_id']);

            return back()->with('message', 'Job was deleted');
        } catch (Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function createWorker(Request $request)
    {
        $validatedData = $request->validate([
            'connection' => 'required',
            'queue' => 'required',
            'timeout' => 'required',
            'sleep' => 'required',
            'tries' => 'required',
            'processes' => 'required',
        ]);

        if ($this->service->createWorker($validatedData)) {
            return back()->with('message', 'New Worker Created');
        }

        return back()->withErrors(['Unable to create Worker']);
    }

    public function deleteWorker(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'worker_id' => 'required',
            ]);

            $this->service->deleteWorker($validatedData['worker_id']);

            return back()->with('message', 'Worker was deleted');
        } catch (Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }
}
