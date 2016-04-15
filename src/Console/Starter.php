<?php

namespace Yab\Laracogs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Starter extends Command
{
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
    protected $description = 'Use Laracogs to prebuild the standard parts of your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileSystem = new Filesystem;

        $files = $fileSystem->allFiles(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter');

        $this->info("These files will be published");

        foreach ($files as $file) {
            $this->line(str_replace(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR, '', $file));
        }

        $result = $this->confirm("Are you sure you want to overwrite any files of the same name?");

        if ($result) {
            $this->line("Copying app/Http...");
            $fileSystem->copyDirectory(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Http', app_path('Http'));

            $this->line("Copying app/Repositories...");
            $fileSystem->copyDirectory(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Repositories', app_path('Repositories'));

            $this->line("Copying app/Services...");
            $fileSystem->copyDirectory(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Services', app_path('Services'));

            $this->line("Copying database...");
            $fileSystem->copyDirectory(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'database', base_path('database'));

            $this->line("Copying resources/views...");
            $fileSystem->copyDirectory(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views', base_path('resources/views'));

            $this->line("Copying tests...");
            $fileSystem->copyDirectory(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'tests', base_path('tests'));

            $this->line("Appending database/factory...");
            $this->createFactory();

            $this->info("Update the model in: config/auth.php, database/factory/ModelFactory.php");
            $this->comment("\n");
            $this->comment("App\Repositories\User\User::class");
            $this->comment("\n");

            $this->info("Build something worth sharing!");
            $this->info("\n");
            $this->info("Don't forget to run: composer dump");
            $this->info("\n");
            $this->info("And then: artisan migration");
        } else {
            $this->info("You cancelled the laracogs starter");
        }

    }

    public function createFactory()
    {
        $factory = file_get_contents(__DIR__.'/../Starter/Factory.txt');
        $factoryMaster = base_path('database/factories/ModelFactory.php');
        return file_put_contents($factoryMaster, $factory, FILE_APPEND);
    }

}
