<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Generators\FileMakerTrait;

class Semantic extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:semantic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laracogs will add semantic-ui to your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!file_exists(base_path('resources/views/team/create.blade.php'))) {
            $this->line("\n\nPlease perform the starter command:\n");
            $this->info("\n\nphp artisan laracogs:starter\n");
            $this->line("\n\nThen one you're able to run the unit tests successfully re-run this command, to semantic ui your app :)\n");
        } else {
            $fileSystem = new Filesystem();

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Semantic');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Semantic/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Semantic/', base_path());

                $this->info("\nYou will need to install semantic-ui:");
                $this->comment('npm install semantic-ui');

                $this->info("\nWhen prompted, select automatic detection.");
                $this->info("\nWhen prompted, select your project location, default should be fine.");
                $this->info("\nWhen prompted, set the directory to:");
                $this->comment('semantic');

                $this->info("\nThen run:");
                $this->comment('cd semantic && gulp build');

                $this->info("\nThen run:");
                $this->comment('cd ../ && gulp');

                $this->info("\nMake sure you set the PagesController@dashboard to use the following view:");
                $this->comment("'dashboard.main'");
                $this->info("\nFinished setting up semantic-ui in your app\n\n");
            } else {
                $this->info('You cancelled the laracogs semantic');
            }
        }
    }
}
