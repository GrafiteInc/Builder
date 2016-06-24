<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Generators\FileMakerTrait;

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
            $this->line('Copying app/Http...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/app/Http', app_path('Http'));

            $this->line('Copying app/Repositories...');
            $this->copyPreparedFiles(__DIR__.'/../Packages/Starter/app/Repositories', app_path('Repositories'));

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

        return file_put_contents($factoryMaster, $factoryPrepared, FILE_APPEND);
    }
}
