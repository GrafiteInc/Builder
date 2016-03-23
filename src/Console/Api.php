<?php

namespace Yab\Laracogs\Console;

use Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Schema;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Api extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laracogs:api';

    protected $files;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laracogs will add an API to your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if (! file_exists(base_path('resources/views/team/create.blade.php'))) {
            $this->line("\n\nPlease perform the starter command:\n");
            $this->info("\n\nphp artisan laracogs:starter\n");
            $this->line("\n\nThen one you're able to run the unit tests successfully re-run this command, to bootstrap your app :)\n");
        } else {
            $fileSystem = new Filesystem;

            $files = $fileSystem->allFiles(__DIR__.'/../Api');
            $this->line("\n");
            foreach ($files as $file) {
                $this->line(str_replace(__DIR__.'/../Api/', '', $file));
            }

            $this->info("\n\nThese files will be published\n");

            $result = $this->confirm("Are you sure you want to overwrite any files of the same name?");

            if ($result) {
                foreach ($files as $file) {
                    $newFileName = str_replace(__DIR__.'/../Api/', '', $file);
                    $this->line("Copying ".$newFileName."...");
                    if (is_dir($file)) {
                        $fileSystem->copyDirectory($file, base_path($newFileName));
                    } else {
                        @mkdir(base_path(str_replace(basename($newFileName), '', $newFileName)), 0755, true);
                        $fileSystem->copy($file, base_path($newFileName));
                    }
                }
            }

            $this->info("\n\n You will need to run: composer require tymon/jwt-auth");
            $this->info("\n\n Then follow the directions regarding installation on: https://github.com/tymondesigns/jwt-auth/wiki/Installation");
            $this->info("\n\n Please review the setup details for JWT.");

            $this->info("\n\n You will want to add things like:");
            $this->line("\n Add this line to (app/Providers/RouteServiceProvider.php):");
            $this->comment("\n require app_path('Http/api-routes.php');");

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

            $this->info("Finished setting up your basic JWT API");
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
