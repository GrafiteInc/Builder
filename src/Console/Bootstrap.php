<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Traits\FileMakerTrait;

class Bootstrap extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:bootstrap';

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
    public function handle()
    {
        if (!file_exists(base_path('app/Services/UserService.php'))) {
            $this->line("\n\nPlease perform the starter command:\n");
            $this->info("\n\nphp artisan laracogs:starter\n");
            $this->line("\n\nThen one you're able to run the unit tests successfully re-run this command, to bootstrap your app :)\n");
        } else {
            $fileSystem = new Filesystem();

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Bootstrap');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Bootstrap/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Bootstrap/', base_path());

                $this->line("\nMake sure you set the PagesController@dashboard to use the following view:\n");
                $this->comment("'dashboard.main'\n");
                $this->info("Run the following:\n");
                $this->comment("npm install\n");
                $this->comment("npm run dev <- local development\n");
                $this->comment("npm run watch <- watch for changes\n");
                $this->comment("npm run production <- run for production\n");
                $this->info("Finished bootstrapping your app\n");
            } else {
                $this->info('You cancelled the laracogs bootstrap');
            }
        }
    }
}
