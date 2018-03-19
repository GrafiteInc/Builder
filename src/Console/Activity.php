<?php

namespace Grafite\Builder\Console;

use Grafite\Builder\Console\GrafiteCommand;
use Grafite\Builder\Traits\FileMakerTrait;
use Illuminate\Filesystem\Filesystem;

class Activity extends GrafiteCommand
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'grafite:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grafite Builder will add an activity tracker for your users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->starterIsInstalled()) {
            $fileSystem = new Filesystem();

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Activity');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Activity/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Activity', base_path());
                $this->info("\n\n Please review the setup details for activity tracking.");
                $this->line("\n You need to add the activity service provider to your app config:");
                $this->comment("\n App\Providers\ActivityServiceProvider::class,");
                $this->line("\n You will also need to the activity middleware to the Kernel in the \$routeMiddleware array:");
                $this->comment("\n 'activity' => \App\Http\Middleware\Activity::class,");
                $this->line("\n Now to track all you need to do is add the middleware `activity` to your routes");
                $this->line("\n In order to get a user's activities use this:");
                $this->comment("\n app(App\Services\ActivityService::class)->getByUser(\$userId);");
                $this->line("\n In order to use the helper you will have to add to your composer autoload:");
                $this->comment("\n \"files\": [");
                $this->comment("\n \"app/Helpers/activity_helper.php\"");
                $this->comment("\n ]");
                $this->info("\n Finished setting up activity");
            } else {
                $this->info("\n You cancelled the grafite:activity");
            }
        }
    }
}
