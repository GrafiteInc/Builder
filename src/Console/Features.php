<?php

namespace Grafite\Builder\Console;

use Grafite\Builder\Console\GrafiteCommand;
use Grafite\Builder\Traits\FileMakerTrait;
use Illuminate\Filesystem\Filesystem;

class Features extends GrafiteCommand
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'grafite:features';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grafite Builder will add a feature management UI to your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->starterIsInstalled()) {
            $fileSystem = new Filesystem();

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Features');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Features/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Features', base_path());
                $this->info("\n\n Please review the setup details for features.");
                $this->info("\n\n You will want to add things like:");
                $this->line("\n These links: ");
                $this->comment("\n <li><a class=\"nav-link\" href='{!! url('admin/features') !!}'><span class='fa fa-cog'></span> Features</a></li>");
                $this->line("\n Now modify the RouteServiceProvider by switching to a closure in the `group` method (app/Providers/RouteServiceProvider.php):");
                $this->line("\n It will look like: ->group(base_path('routes/web.php')); So you need to change it to resemble this:");
                $this->comment("\n ->group(function () {");
                $this->comment("\n require base_path('routes/web.php');");
                $this->comment("\n require base_path('routes/features.php');");
                $this->comment("\n }");
                $this->line("\n You will the feature service provider to your app config:");
                $this->comment("\n App\Providers\FeatureServiceProvider::class,");
                $this->line("\n In order to use the helper you will have to add to your composer autoload:");
                $this->comment("\n \"files\": [");
                $this->comment("\n \"app/Helpers/feature_helper.php\"");
                $this->comment("\n ]");
                $this->info("\n Finished setting up features");
            } else {
                $this->info("\n You cancelled the grafite:features");
            }
        }
    }
}
