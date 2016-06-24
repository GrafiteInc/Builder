<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Generators\FileMakerTrait;

class Notifications extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laracogs will add notifications to your app';

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

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Notifications');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Notifications/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Notifications', base_path());
                $this->appendTheFactory();

                $this->info("\n\n You will need to run: composer require laravel/cashier");
                $this->info("\n\n Then follow the directions regarding notifications on: https://laravel.com/docs/");
                $this->info("\n\n Please review the setup details for notifications.");
                $this->info("\n\n You will want to add things like:");
                $this->line("\n These links: ");
                $this->comment("\n <li><a href='{!! url('user/notifications') !!}'><span class='fa fa-envelope-o'></span> Notifications</a></li>");
                $this->comment("\n <li><a href='{!! url('admin/notifications') !!}'><span class='fa fa-envelope-o'></span> Notifications</a></li>");
                $this->line("\n Add this line to (app/Providers/RouteServiceProvider.php):");
                $this->comment("\n require app_path('Http/notification-routes.php');");
                $this->line("\n Add this to (app/Providers/AppServiceProvider.php) in the register() method:");
                $this->comment("\n \$loader = \Illuminate\Foundation\AliasLoader::getInstance();");
                $this->comment("\n \$loader->alias('Notifications', \App\Facades\Notifications::class);");
                $this->comment("\n \$this->app->singleton('NotificationService', function (\$app) {");
                $this->comment("\n\t return \App::make(\App\Services\NotificationService::class);");
                $this->comment("\n });");
                $this->info("\n Finished setting up notifications");
            } else {
                $this->info("\n You cancelled the laracogs notifications");
            }
        }
    }

    public function appendTheFactory()
    {
        $factory = file_get_contents(__DIR__.'/../Starter/Factory.txt');
        $factoryPrepared = '
/*
|--------------------------------------------------------------------------
| Notification Factory
|--------------------------------------------------------------------------
*/

$factory->define('.$this->getAppNamespace()."Repositories\Notification\Notification::class, function (Faker\Generator \$faker) {
    return [
        'id' => 1,
        'user_id' => 1,
        'flag' => 'info',
        'uuid' => 'lksjdflaskhdf',
        'title' => 'Testing',
        'details' => 'Your car has been impounded!',
        'is_read' => 0,
    ];
});
";

        $factoryMaster = base_path('database/factories/ModelFactory.php');

        return file_put_contents($factoryMaster, $factoryPrepared, FILE_APPEND);
    }
}
