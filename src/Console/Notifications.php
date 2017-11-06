<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Traits\FileMakerTrait;

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
        if (!file_exists(base_path('app/Services/UserService.php'))) {
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
                $this->info("\n\n Then follow the directions regarding notifications on: https://laravel.com/docs/");
                $this->info("\n\n Please review the setup details for notifications.");
                $this->info("\n\n You will want to add things like:");
                $this->line("\n These links: ");
                $this->comment("\n <li><a href='{!! url('user/notifications') !!}'><span class='fa fa-envelope-o'></span> Notifications</a></li>");
                $this->comment("\n <li><a href='{!! url('admin/notifications') !!}'><span class='fa fa-envelope-o'></span> Notifications</a></li>");
                $this->line("\n Now mofify the RouteServiceProvider by switching to a closure in the `group` method (app/Providers/RouteServiceProvider.php):");
                $this->line("\n It will look like: ->group(base_path('routes/web.php')); So you need to change it to resemble this:");
                $this->comment("\n ->group(function () {");
                $this->comment("\n require base_path('routes/web.php');");
                $this->comment("\n require base_path('routes/notification.php');");
                $this->comment("\n }");
                $this->line("\n Add this to (app/Providers/AppServiceProvider.php) in the register() method:");
                $this->comment("\n \$loader = \Illuminate\Foundation\AliasLoader::getInstance();");
                $this->comment("\n \$loader->alias('Notifications', \App\Facades\Notifications::class);");
                $this->comment("\n \$this->app->singleton('NotificationService', function (\$app) {");
                $this->comment("\n\t return app(\App\Services\NotificationService::class);");
                $this->comment("\n });");
                $this->info("\n Finished setting up notifications");
            } else {
                $this->info("\n You cancelled the laracogs notifications");
            }
        }
    }
}
