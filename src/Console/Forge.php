<?php

namespace Grafite\Builder\Console;

use Grafite\Builder\Console\GrafiteCommand;
use Grafite\Builder\Traits\FileMakerTrait;
use Illuminate\Filesystem\Filesystem;

class Forge extends GrafiteCommand
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'grafite:forge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grafite Builder will add a FORGE admin UI to your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->starterIsInstalled()) {
            $fileSystem = new Filesystem();

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Forge');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Forge/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Forge', base_path());
                $this->info("\n\n You will need to run: composer require themsaid/forge-sdk");
                $this->info("\n\n You will want to add things like:");
                $this->line("\n These links: ");
                $this->comment("\n <li class=\"nav-item\"><a class=\"nav-link\" href='{!! url('admin/forge/settings') !!}'><span class=\"fa fa-fw fa-server\"></span> Forge Settings</a></li>");
                $this->comment("\n <li class=\"nav-item\"><a class=\"nav-link\" href='{!! url('admin/forge/scheduler') !!}'><span class=\"fa fa-fw fa-calendar\"></span> Forge Scheduler</a></li>");
                $this->comment("\n <li class=\"nav-item\"><a class=\"nav-link\" href='{!! url('admin/forge/workers') !!}'><span class=\"fa fa-fw fa-cogs\"></span> Forge Workers</a></li>");
                $this->line("\n Now modify the RouteServiceProvider by switching to a closure in the `group` method (app/Providers/RouteServiceProvider.php):");
                $this->line("\n Add these lines to (.env):");
                $this->comment("\n FORGE_TOKEN=");
                $this->comment("\n FORGE_SERVER_ID=");
                $this->comment("\n FORGE_SITE_ID=");
                $this->line("\n It will look like: ->group(base_path('routes/web.php')); So you need to change it to resemble this:");
                $this->comment("\n ->group(function () {");
                $this->comment("\n require base_path('routes/web.php');");
                $this->comment("\n require base_path('routes/forge.php');");
                $this->comment("\n }");
                $this->info("\n Finished setting up forge");
            } else {
                $this->info("\n You cancelled the grafite:forge");
            }
        }
    }
}
