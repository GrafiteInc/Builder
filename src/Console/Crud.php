<?php

namespace Yab\Laracogs\Console;

use Config;
use Artisan;
use Illuminate\Console\Command;
use Yab\Laracogs\Generators\CrudGenerator;

class Crud extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:crud {table} {--migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a basic CRUD for a table';

    /**
     * Generate a CRUD stack
     *
     * @return mixed
     */
    public function handle()
    {
        $crudGenerator = new CrudGenerator();

        $table = ucfirst(str_singular($this->argument('table')));

        $config = [
            '_path_facade_'              => app_path('Facades'),
            '_path_service_'             => app_path('Services'),
            '_path_repository_'          => app_path('Repositories/'.ucfirst($table)),
            '_path_model_'               => app_path('Repositories/'.ucfirst($table)),
            '_path_controller_'          => app_path('Http/Controllers/'),
            '_path_views_'               => base_path('resources/views'),
            '_path_tests_'               => base_path('tests'),
            '_path_request_'             => app_path('Http/Requests/'),
            '_path_routes_'              => app_path('Http/routes.php'),
            'routes_prefix'              => '',
            'routes_suffix'              => '',
            '_namespace_services_'       => 'App\Services',
            '_namespace_facade_'         => 'App\Facades',
            '_namespace_repository_'     => 'App\Repositories\\'.ucfirst($table),
            '_namespace_model_'          => 'App\Repositories\\'.ucfirst($table),
            '_namespace_controller_'     => 'App\Http\Controllers',
            '_namespace_request_'        => 'App\Http\Requests',
            '_lower_case_'               => strtolower($table),
            '_lower_casePlural_'         => str_plural(strtolower($table)),
            '_camel_case_'               => ucfirst(camel_case($table)),
            '_camel_casePlural_'         => str_plural(camel_case($table)),
            'template_source'            => __DIR__.'/../Templates/',
        ];

        try {
            $this->line('Building controller...');
            $crudGenerator->createController($config);

            $this->line('Building repository...');
            $crudGenerator->createRepository($config);

            $this->line('Building request...');
            $crudGenerator->createRequest($config);

            $this->line('Building service...');
            $crudGenerator->createService($config);

            $this->line('Building views...');
            $crudGenerator->createViews($config);

            $this->line('Building routes...');
            $crudGenerator->createRoutes($config);

            $this->line('Building tests...');
            $crudGenerator->createTests($config);

            $this->line('Building factory...');
            $crudGenerator->createFactory($config);

            $this->line('Building facade...');
            $crudGenerator->createFacade($config);
        } catch (Exception $e) {
            throw new Exception("Unable to generate your CRUD", 1);
        }

        if ($this->option('migration')) {
            Artisan::call('make:migration', [
                'name' => 'create_'.str_plural(strtolower($table)).'_table',
                '--table' => str_plural(strtolower($table)),
                '--create' => true,
            ]);
        }

        $this->line('You may wish to add this as your testing database');
        $this->line("'testing' => [ 'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '' ],");
        $this->info('CRUD for '.$table.' is done.');
    }
}
