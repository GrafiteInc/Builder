<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Generators\FileMakerTrait;

class Api extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laracogs will add JWT API access to your app';

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

            $files = $fileSystem->allFiles(__DIR__.'/../Packages/API');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Packages/API/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

            if ($result) {
                $this->copyPreparedFiles(__DIR__.'/../Packages/API/', base_path());

                $this->info("\n\n You will need to run:");
                $this->comment("\n\n composer require tymon/jwt-auth");
                $this->info("\n\n Then follow the directions regarding installation on: https://github.com/tymondesigns/jwt-auth/wiki/Installation");
                $this->info("\n\n Please review the setup details for JWT.");

                $this->info("\n\n You will want to add things like:");
                $this->line("\n Add this line to (app/Providers/RouteServiceProvider.php) in the mapWebRoutes() method:");
                $this->comment("\n require app_path('Http/api-routes.php');");

                $this->line("\n Add this to the (config/app.php) providers:");
                $this->comment("\n Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class");
                $this->line("\n And then run:");
                $this->comment("\n artisan vendor:publish --provider=\"Tymon\JWTAuth\Providers\JWTAuthServiceProvider\"");

                $this->line("\n Add to the app/Http/Kernal.php under routeMiddleware :");
                $this->comment("\n 'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class");
                $this->comment("\n 'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class");

                $this->line("\n Add to except attribute the app/Http/Middleware/VerifyCsrfToken.php :");
                $this->comment("\n 'api/v1/login'");
                $this->comment("\n 'api/v1/user/profile'");

                $this->line("\n If you use Apache add this to the .htaccess file :");
                $this->comment("\n RewriteEngine On");
                $this->comment("\n RewriteCond %{HTTP:Authorization} ^(.*)");
                $this->comment("\n RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]");

                $this->line("\n Also update your jwt config file and set the user to:");
                $this->comment("\n \App\Repositories\User\User::class ");

                $this->info('Finished setting up your basic JWT API');
            } else {
                $this->info('You cancelled the laracogs api');
            }
        }
    }
}
