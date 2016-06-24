<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Generators\FileMakerTrait;

class Socialite extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:socialite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laracogs will add a Socialite auth to your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! file_exists(base_path('resources/views/admin/users/invite.blade.php'))) {
            $this->line("\n\nPlease perform the starter command:\n");
            $this->info("\n\nphp artisan laracogs:starter\n");
            $this->line("\n\nThen one you're able to run the unit tests successfully re-run this command, to bootstrap your app :)\n");
        } else {
            $fileSystem = new Filesystem;

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/Socialite');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/Socialite/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm("Are you sure you want to overwrite any files of the same name?");

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/Socialite', base_path());

                $this->info("\n\n You will need to run:");
                $this->comment("\n\n composer require laravel/socialite");
                $this->info("\n\n Then follow the directions regarding socialite on: https://laravel.com/docs/");
                $this->info("\n\n Please review the setup details for socialite including your provider details.");
                $this->info("\n\n You will want to add things like:");
                $this->line("\n This to the provdiers in the app config: ");
                $this->comment("\n 'providers' => [");
                $this->comment("\n\t // Other service providers...");
                $this->comment("\n\t Laravel\Socialite\SocialiteServiceProvider::class,");
                $this->comment("\n ],");
                $this->line("\n Also you will need to add your providers to your services: (example) ");
                $this->comment("\n'github' => [");
                $this->comment("\n\t'client_id' => 'your-github-app-id',");
                $this->comment("\n\t'client_secret' => 'your-github-app-secret',");
                $this->comment("\n\t'redirect' => 'http://domain/auth/github/callback',");
                $this->comment("\n\t'scopes' => ['user:email'],");
                $this->comment("\n],");
                $this->line("\n This to the aliases in the app config: ");
                $this->comment("\n 'Socialite' => Laravel\Socialite\Facades\Socialite::class,");
                $this->line("\n Add this line to (app/Providers/RouteServiceProvider.php):");
                $this->comment("\n require app_path('Http/socialite-routes.php');");
                $this->info("Finished setting up a basic socialite structure");
            } else {
                $this->info("You cancelled the laracogs socialite");
            }
        }
    }
}
