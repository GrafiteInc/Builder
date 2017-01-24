<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Traits\FileMakerTrait;

class Billing extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:billing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laracogs will add billing to your app';

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

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Billing');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Billing/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Billing/', base_path());

                $this->info("\n\n You will need to run: composer require laravel/cashier");
                $this->info("\n\n Then follow the directions regarding billing on: https://laravel.com/docs/");
                $this->info("\n\n Please review the setup details for billing.");
                $this->info("\n\n You will want to add things like:");
                $this->line("\n This link: ");
                $this->comment("\n <li><a href='{!! url('user/billing/details') !!}'><span class='fa fa-dollar'></span> Billing</a></li>");
                $this->line("\n Add this line to (app/Providers/RouteServiceProvider.php) in the mapWebRoutes() method:");
                $this->line("\n It will look like: ->group(base_path('routes/web.php')); So you need to change it to resemble this:");
                $this->comment("\n ->group(function () {");
                $this->comment("\n require base_path('routes/web.php');");
                $this->comment("\n require base_path('routes/billing.php');");
                $this->comment("\n }");
                $this->line("\n Add this line to (.env):");
                $this->comment("\n SUBSCRIPTION=basic");
                $this->line("\n Add this to (app/Providers/AuthServiceProvider.php):");
                $this->comment("\n Gate::define('access-billing', function (\$user) {");
                $this->comment("\n\t return (\$user->meta->subscribed('main') && is_null(\$user->meta->subscription('main')->endDate));");
                $this->comment("\n });");
                $this->line("\n Your webpack mix file will want to resemble this: (webpack.mix.js):");
                $this->comment("\n\t .js([");
                $this->comment("\n\t\t 'resources/assets/js/app.js',");
                $this->comment("\n\t\t 'resources/assets/js/card.js',");
                $this->comment("\n\t\t 'resources/assets/js/subscription.js'");
                $this->comment("\n\t ], 'public/js');");
                $this->info("\n\n Please make sure you run the migration for cashier structure.");
                $this->comment("\n\n ** You will need to configure your app to handle cancelling subscriptions when deleting users. **");
                $this->info('Finished setting up billing');
            } else {
                $this->info('You cancelled the laracogs billing');
            }
        }
    }
}
