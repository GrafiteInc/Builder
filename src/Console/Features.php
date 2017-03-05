<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Traits\FileMakerTrait;

class Features extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:features';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a feature management UI to your app';

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

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Features');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Features/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Features', base_path());
                $this->appendTheFactory();

                $this->info("\n\n Please review the setup details for features.");
                $this->info("\n\n You will want to add things like:");
                $this->line("\n These links: ");
                $this->comment("\n <li><a href='{!! url('admin/features') !!}'><span class='fa fa-cog'></span> Features</a></li>");
                $this->line("\n Now mofify the RouteServiceProvider by switching to a closure in the `group` method (app/Providers/RouteServiceProvider.php):");
                $this->line("\n It will look like: ->group(base_path('routes/web.php')); So you need to change it to resemble this:");
                $this->comment("\n ->group(function () {");
                $this->comment("\n require base_path('routes/web.php');");
                $this->comment("\n require base_path('routes/features.php');");
                $this->comment("\n }");
                $this->line("\n You will the feature service provider to your app config:");
                $this->comment("\n App\Providers\FeatureServiceProvider::class,");
                $this->info("\n Finished setting up features");
            } else {
                $this->info("\n You cancelled the laracogs features");
            }
        }
    }

    public function appendTheFactory()
    {
        $factoryPrepared = '
/*
|--------------------------------------------------------------------------
| Feature Factory
|--------------------------------------------------------------------------
*/

$factory->define('.$this->getAppNamespace()."Models\Feature::class, function (Faker\Generator \$faker) {
    return [
        'id' => 1,
        'key' => 'user-signup',
        'is_active' => false,
    ];
});
";

        $factoryMaster = base_path('database/factories/ModelFactory.php');

        return file_put_contents($factoryMaster, $factoryPrepared, FILE_APPEND);
    }
}
