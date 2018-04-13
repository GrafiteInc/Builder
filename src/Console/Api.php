<?php

namespace Grafite\Builder\Console;

use Grafite\Builder\Console\GrafiteCommand;
use Grafite\Builder\Traits\FileMakerTrait;
use Illuminate\Filesystem\Filesystem;

class Api extends GrafiteCommand
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'grafite:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grafite Builder will add JWT API access to your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->starterIsInstalled()) {
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
                $this->line("\n Add this line to (app/Providers/RouteServiceProvider.php) in the mapApiRoutes() method:");
                $this->comment("\n require base_path('routes/api.php');");

                $this->line("\n Add this to the (config/app.php) providers:");
                $this->comment("\n Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class");
                $this->line("\n And then run:");
                $this->comment("\n artisan vendor:publish --provider=\"Tymon\JWTAuth\Providers\JWTAuthServiceProvider\"");

                $this->line("\n Add to the app/Http/Kernel.php under routeMiddleware :");
                $this->comment("\n 'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class");
                $this->comment("\n 'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class");

                $this->line("\n Add to except attribute the app/Http/Middleware/VerifyCsrfToken.php :");
                $this->comment("\n 'api/v1/login',");
                $this->comment("\n 'api/v1/user/profile',");

                $this->line("\n If you use Apache add this to the .htaccess file :");
                $this->comment("\n RewriteEngine On");
                $this->comment("\n RewriteCond %{HTTP:Authorization} ^(.*)");
                $this->comment("\n RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]");

                $this->line("\n Also update your jwt config file and set the user to:");
                $this->comment("\n \App\Models\User::class ");

                $this->info('Finished setting up your basic JWT API');
            } else {
                $this->info('You cancelled the grafite:api');
            }
        }
    }
}
