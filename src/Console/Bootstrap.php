<?php

namespace Yab\Laracogs\Console;

use Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Schema;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;

class Bootstrap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laracogs:bootstrap';

    protected $files;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laracogs will bootstrap your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if (! file_exists(base_path('resources/views/team/create.blade.php'))) {
            $this->line("\n\nPlease perform the starter command:\n");
            $this->info("\n\nphp artisan laracogs:starter\n");
            $this->line("\n\nThen one you're able to run the unit tests successfully re-run this command, to bootstrap your app :)\n");
        } else {
            $fileSystem = new Filesystem;

            $files = $fileSystem->allFiles(__DIR__.'/../Bootstrap');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Bootstrap/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm("Are you sure you want to overwrite any files of the same name?");

            if ($result) {
                foreach ($files as $file) {
                    $newFileName = str_replace(__DIR__.'/../Bootstrap/', '', $file);
                    $this->line("Copying ".$newFileName."...");
                    if (is_dir($file)) {
                        $fileSystem->copyDirectory($file, base_path($newFileName));
                    } else {
                        @mkdir(base_path(str_replace(basename($newFileName), '', $newFileName)), 0755, true);
                        $fileSystem->copy($file, base_path($newFileName));
                    }
                }
            }

            $this->info("\n\nMake sure you set the PagesController@dashboard to use the following view: 'dashboard.main'\n\n");
            $this->info("Finished bootsrapping your app");
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
