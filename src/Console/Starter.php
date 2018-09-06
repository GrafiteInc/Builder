<?php

namespace Grafite\Builder\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Grafite\Builder\Traits\FileMakerTrait;

class Starter extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'grafite:starter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grafite Builder will build some common parts of your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileSystem = new Filesystem();

        $files = $fileSystem->allFiles(__DIR__.'/../Packages/Starter');

        $this->info('These files will be published');

        foreach ($files as $file) {
            $this->line(str_replace(__DIR__.'/../Packages/Starter/', '', $file));
        }

        $result = $this->confirm('Are you sure you want to overwrite any files of the same name?');

        if ($result) {
            $this->line('Copying routes...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/routes', base_path('routes'));

            $this->line('Copying config...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/config', base_path('config'));

            $this->line('Copying app/Http...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/app/Http', app_path('Http'));

            $this->line('Copying app/Events...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/app/Events', app_path('Events'));

            $this->line('Copying app/Listeners...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/app/Listeners', app_path('Listeners'));

            $this->line('Copying app/Notifications...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/app/Notifications', app_path('Notifications'));

            $this->line('Copying app/Models...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/app/Models', app_path('Models'));

            $this->line('Copying app/Services...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/app/Services', app_path('Services'));

            $this->line('Copying database...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/database', base_path('database'));

            $this->line('Copying resources/views...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/resources/views', base_path('resources/views'));

            $this->line('Copying tests...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/tests', base_path('tests'));

            $this->line('Adjusting Laravel default files...');
            $this->defaultFileChanger();

            $this->info("Build something worth sharing!\n");

            if ($this->confirm('Would you like to run the migration?')) {
                $this->comment('Running command: cd '.base_path().' && composer dump ...');

                exec(sprintf('cd %s && composer dump', escapeshellarg(base_path()));

                // execute migrate artisan command
                Artisan::call('migrate');
                Artisan::call('db:seed');
            } else {
                $this->info("Don't forget to run:");
                $this->comment('composer dump');
                $this->info('Then:');
                $this->comment('artisan migrate --seed');
            }
        } else {
            $this->info('You cancelled the grafite:starter');
        }
    }

    /**
     * Clean up files from the install of Laravel etc.
     */
    public function defaultFileChanger()
    {
        $files = [
            base_path('config/auth.php'),
            base_path('config/services.php'),
        ];

        foreach ($files as $file) {
            $contents = file_get_contents($file);
            $contents = str_replace('App\User::class', 'App\Models\User::class', $contents);
            file_put_contents($file, $contents);
        }
    }
}
