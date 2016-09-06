<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Traits\FileMakerTrait;

class Starter extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:starter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laracogs will prebuild some common parts of your app';

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

            $this->line('Appending database/factory...');
            $this->createFactory();

            $this->info('Update the model in: config/auth.php, database/factory/ModelFactory.php');
            $this->comment("\n");
            $this->comment($this->getAppNamespace()."Repositories\User\User::class");
            $this->comment("\n");

            $this->info("Build something worth sharing!\n");
            $this->info("Don't forget to run:");
            $this->comment('composer dump');
            $this->info('Then:');
            $this->comment('artisan migrate');
        } else {
            $this->info('You cancelled the laracogs starter');
        }
    }

    public function createFactory()
    {
        $factory = file_get_contents(__DIR__.'/../Packages/Starter/Factory.txt');
        $factoryPrepared = str_replace('{{App\}}', $this->getAppNamespace(), $factory);
        $factoryMaster = base_path('database/factories/ModelFactory.php');
        file_put_contents($factoryMaster, str_replace($factoryPrepared, '', file_get_contents($factoryMaster)));

        return file_put_contents($factoryMaster, $factoryPrepared, FILE_APPEND);
    }
}
