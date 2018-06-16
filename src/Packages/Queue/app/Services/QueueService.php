<?php

namespace App\Services;

use App\Models\FailedJob;
use App\Models\Job;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class QueueService
{
    public $pagination_count;

    public function __construct()
    {
        $this->pagination_count = 25;
    }

    public function activeJobs()
    {
        return Job::where('reserved_at', '!=', null)->paginate($this->pagination_count);
    }

    public function upcomingJobs()
    {
        return Job::paginate($this->pagination_count);
    }

    public function failedJobs()
    {
        return FailedJob::paginate($this->pagination_count);
    }

    public function restart()
    {
        try {
            Artisan::call('queue:restart');

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    public function retryAll()
    {
        try {
            Artisan::call('queue:retry', [
                'id' => 'all',
            ]);

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    public function cancel($id, $table)
    {
        if ($table === 'failed') {
            return FailedJob::find($id)->delete();
        }

        return Job::find($id)->delete();
    }

    public function retry($id)
    {
        try {
            Artisan::call('queue:retry', [
                'id' => $id,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
