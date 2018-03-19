<?php

namespace Grafite\Builder\Console;

use Grafite\Builder\Console\GrafiteCommand;
use Grafite\Builder\Traits\FileMakerTrait;
use Illuminate\Filesystem\Filesystem;

class Bootstrap extends GrafiteCommand
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'grafite:bootstrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grafite Builder will bootstrap your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->starterIsInstalled()) {
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
                $this->info('You cancelled the grafite:bootstrap');
            }
        }
    }
}
