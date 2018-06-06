<?php

namespace {{App\}}Services;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class LogService
{
    protected $date;

    protected $page;

    protected $path;

    protected $level;

    public $levels = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
    ];

    public function get($payload)
    {
        $this->page = request('page', 0);
        $this->date = request('date', null);
        $this->level = request('level', 'all');
        $this->path = $this->getLogPath();

        $perPage = request('perPage', 25);

        return $this->paginate($perPage);
    }

    public function getLogPath()
    {
        return storage_path('logs');
    }

    public function getDate()
    {
        return $this->date;
    }

    private function getLogFileList()
    {
        $path = $this->getLogPath();

        if (is_dir($path)) {
            // Matches files in the log directory with the name of 'laravel.log'
            $logPath = sprintf('%s%slaravel.log', $path, DIRECTORY_SEPARATOR);

            if ($date = $this->getDate()) {
                // Matches files in the log directory with the file name of
                // 'laravel-YYYY-MM-DD.log' if a date is supplied
                $logPath = sprintf('%s%slaravel-%s.log', $path, DIRECTORY_SEPARATOR, $date);
            }

            return glob($logPath);
        }

        return false;
    }

    public function getLogDates()
    {
        $dates = [];
        $path = $this->getLogPath();

        if (is_dir($path)) {
            $files = glob($path.'/*');
        }

        if (count($files) === 1 && str_contains($files[0], 'laravel.log')) {
            return [];
        }

        foreach ($files as $file) {
            $date = str_replace($path, '', $file);
            $date = str_replace('/laravel-', '', $date);
            $dates[] = str_replace('.log', '', $date);
        }

        return $dates;
    }

    private function getLogFiles()
    {
        $data = [];

        $files = $this->getLogFileList();

        if (is_array($files)) {
            $count = 0;

            foreach ($files as $file) {
                $data[$count]['contents'] = file_get_contents($file);
                $data[$count]['path'] = $file;
                $count++;
            }

            return $data;
        }

        return false;
    }

    private function parseLog($content, $allowedLevel = 'all')
    {
        $entries = [];

        $pattern = "/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/";

        preg_match_all($pattern, $content, $headings);

        $data = preg_split($pattern, $content);

        if ($data[0] < 1) {
            $trash = array_shift($data);

            unset($trash);
        }

        if (is_array($headings)) {
            foreach ($headings as $heading) {
                for ($i = 0, $j = count($heading); $i < $j; $i++) {
                    foreach ($this->levels as $level) {
                        preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}([\+-]\d{4})?)\](?:.*?(\w+)\.|.*?)' . $level . ': (.*?)( in .*?:[0-9]+)?$/i', $heading[$i], $current);

                        if (!isset($current[4])) continue;

                        if ($level == $allowedLevel || $allowedLevel == 'all') {
                            if (strpos(strtolower($heading[$i]), strtolower('.'.$level))) {
                                $entries[] = [
                                    'date' => $current[1],
                                    'level' => $level,
                                    'log' => $current[4],
                                    'stack' => $data[$i],
                                    'filePath' => $this->getLogPath(),
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $entries;
    }

    public function getAllLogs()
    {
        $entries = [];

        $files = $this->getLogFiles();

        if (is_array($files)) {
            foreach ($files as $log) {
                $this->path = $log['path'];

                $parsedLog = $this->parseLog($log['contents'], $this->level);

                foreach ($parsedLog as $entry) {
                    $entries[] = $entry;
                }
            }

            return collect($entries);
        }

        throw new Exception('Unable to retrieve files');
    }

    public function paginate($perPage = 25)
    {
        $currentPage = $this->page;

        $offset = (($currentPage - 1) * $perPage);

        $entries = $this->getAllLogs()->reverse();

        $total = $entries->count();

        $entries = $entries->slice($offset, $perPage, true)->all();

        $paginator = new LengthAwarePaginator($entries, $total, $perPage, $currentPage);

        $paginator->setPath(request()->url());

        return $paginator;
    }
}
