<?php

namespace {{App\}}Services;

use Exception;
use Themsaid\Forge\Forge;

class ForgeService
{
    public function __construct()
    {
        $this->forge = new Forge(config('forge.token'));
    }

    public function getSettings()
    {
        return $this->forge->server(config('forge.server'));
    }

    public function getFirewalls()
    {
        return $this->forge->firewallRules(config('forge.server'));
    }

    public function getSites()
    {
        return $this->forge->sites(config('forge.server'));
    }

    public function getSite()
    {
        return $this->forge->site(config('forge.server'), config('forge.site'));
    }

    public function getJobs()
    {
        return $this->forge->jobs(config('forge.server'));
    }

    public function createJob($validatedRequest)
    {
        $payload = [
            "command" => $validatedRequest['command'],
            "frequency" => "custom",
            "user" => $validatedRequest['user'],
            "minute" => $validatedRequest['minute'],
            "hour" => $validatedRequest['hour'],
            "day" => $validatedRequest['day'],
            "month" => $validatedRequest['month'],
            "weekday" => $validatedRequest['weekday'],
        ];

        return $this->forge->createJob(config('forge.server'), $payload, true);
    }

    public function deleteJob($jobId)
    {
        return $this->forge->deleteJob(config('forge.server'), $jobId);
    }

    public function getWorkers()
    {
        return $this->forge->workers(config('forge.server'), config('forge.site'));
    }

    public function createWorker($validatedRequest)
    {
        $payload = [
            "connection" => $validatedRequest['connection'],
            "queue" => $validatedRequest['queue'],
            "timeout" => $validatedRequest['timeout'],
            "sleep" => $validatedRequest['sleep'],
            "tries" => $validatedRequest['tries'],
            "processes" => $validatedRequest['processes'],
            "daemon" => $validatedRequest['daemon'] ?? false,
        ];

        return $this->forge->createWorker(config('forge.server'), config('forge.site'), $payload, true);
    }

    public function deleteWorker($workerId)
    {
        return $this->forge->deleteWorker(config('forge.server'), config('forge.site'), $workerId);
    }
}
