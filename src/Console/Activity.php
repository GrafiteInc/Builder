<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Traits\FileMakerTrait;

class Activity extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add an activity tracker for your users';

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
            $this->line("\n\nThen one you're able to run the unit tests successfully re-run this command, to bootstrap your app :)\n");
        } else {
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
                $this->appendTheFactory();

                $this->info("\n\n Please review the setup details for activity tracking.");
                $this->line("\n You need to add the activity service provider to your app config:");
                $this->comment("\n App\Providers\ActivityServiceProvider::class,");
                $this->line("\n You will also need to the activity middleware to the Kernel in the \$routeMiddleware array:");
                $this->comment("\n 'activity' => \App\Http\Middleware\Activity::class,");
                $this->line("\n Now to track all you need to do is add the middleware `activity` to your routes");
                $this->line("\n In order to get a user's activities use this:");
                $this->comment("\n app(App\Services\ActivityService::class)->getByUser(\$userId);");
                $this->info("\n Finished setting up activity");
            } else {
                $this->info("\n You cancelled the laracogs activity");
            }
        }
    }

    public function appendTheFactory()
    {
        $activityPrepared = '
/*
|--------------------------------------------------------------------------
| Activity Factory
|--------------------------------------------------------------------------
*/

$factory->define('.$this->getAppNamespace()."Models\Activity::class, function (Faker\Generator \$faker) {
    return [
        'id' => 1,
        'user_id' => 1,
        'description' => 'Standard User Activity',
        'request' => [],
    ];
});
";

        $factoryMaster = base_path('database/factories/ModelFactory.php');

        return file_put_contents($factoryMaster, $activityPrepared, FILE_APPEND);
    }
}
