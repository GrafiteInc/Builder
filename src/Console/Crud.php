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
    protected $signature = 'laracogs:crud {table} {--api} {--migration} {--bootstrap}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a basic CRUD for a table with option for migration and api';

    /**
     * Generate a CRUD stack
     *
     * @return mixed
     */
    public function handle()
    {
        $section = false;
        $crudGenerator = new CrudGenerator();

        $table = ucfirst(str_singular($this->argument('table')));

        if (stristr($table, '_')) {
            $splitTable = explode('_', $table);
            $table = $splitTable[1];
            $section = $splitTable[0];
        }

        $config = [
            'bootstrap'                  => false,
            '_path_facade_'              => app_path('Facades'),
            '_path_service_'             => app_path('Services'),
            '_path_repository_'          => app_path('Repositories/_table_'),
            '_path_model_'               => app_path('Repositories/_table_'),
            '_path_controller_'          => app_path('Http/Controllers/'),
            '_path_api_controller_'      => app_path('Http/Controllers/Api'),
            '_path_views_'               => base_path('resources/views'),
            '_path_tests_'               => base_path('tests'),
            '_path_request_'             => app_path('Http/Requests/'),
            '_path_routes_'              => app_path('Http/routes.php'),
            '_path_api_routes_'          => app_path('Http/api-routes.php'),
            'routes_prefix'              => '',
            'routes_suffix'              => '',
            '_namespace_services_'       => 'App\Services',
            '_namespace_facade_'         => 'App\Facades',
            '_namespace_repository_'     => 'App\Repositories\_table_',
            '_namespace_model_'          => 'App\Repositories\_table_',
            '_namespace_controller_'     => 'App\Http\Controllers',
            '_namespace_api_controller_' => 'App\Http\Controllers\Api',
            '_namespace_request_'        => 'App\Http\Requests',
            '_table_name_'               => str_plural(strtolower($table)),
            '_lower_case_'               => strtolower($table),
            '_lower_casePlural_'         => str_plural(strtolower($table)),
            '_camel_case_'               => ucfirst(camel_case($table)),
            '_camel_casePlural_'         => str_plural(camel_case($table)),
            '_ucCamel_casePlural_'       => ucfirst(str_plural(camel_case($table))),
        ];

        $config = array_merge($config, Config::get('laracogs.crud.single'));
        $config['template_source'] = Config::get('laracogs.crud.template_source', ['template_source' => __DIR__.'/../Templates']);
        $config = $this->setConfig($config, $section, $table);

        if ($section) {
            $config = [];
            $config['template_source'] = Config::get('laracogs.crud.template_source', ['template_source' => __DIR__.'/../Templates']);
            $config = [
                'bootstrap'                  => false,
                '_path_facade_'              => app_path('Facades'),
                '_path_service_'             => app_path('Services'),
                '_path_repository_'          => app_path('Repositories/'.ucfirst($section).'/'.ucfirst($table)),
                '_path_model_'               => app_path('Repositories/'.ucfirst($section).'/'.ucfirst($table)),
                '_path_controller_'          => app_path('Http/Controllers/'.ucfirst($section).'/'),
                '_path_api_controller_'      => app_path('Http/Controllers/'.ucfirst($section).'/Api'),
                '_path_views_'               => base_path('resources/views/'.strtolower($section)),
                '_path_tests_'               => base_path('tests'),
                '_path_request_'             => app_path('Http/Requests/'.ucfirst($section)),
                '_path_routes_'              => app_path('Http/routes.php'),
                '_path_api_routes_'          => app_path('Http/api-routes.php'),
                'routes_prefix'              => "\n\nRoute::group(['namespace' => '".ucfirst($section)."', 'prefix' => '".strtolower($section)."', 'middleware' => ['web']], function () { \n",
                'routes_suffix'              => "\n});",
                '_namespace_services_'       => 'App\Services\\'.ucfirst($section),
                '_namespace_facade_'         => 'App\Facades',
                '_namespace_repository_'     => 'App\Repositories\\'.ucfirst($section).'\\'.ucfirst($table),
                '_namespace_model_'          => 'App\Repositories\\'.ucfirst($section).'\\'.ucfirst($table),
                '_namespace_controller_'     => 'App\Http\Controllers\\'.ucfirst($section),
                '_namespace_api_controller_' => 'App\Http\Controllers\\'.ucfirst($section).'\Api\\',
                '_namespace_request_'        => 'App\Http\Requests\\'.ucfirst($section),
                '_table_name_'               => str_plural(strtolower(implode('_', $splitTable))),
                '_lower_case_'               => strtolower($table),
                '_lower_casePlural_'         => str_plural(strtolower($table)),
                '_camel_case_'               => ucfirst(camel_case($table)),
                '_camel_casePlural_'         => str_plural(camel_case($table)),
                '_ucCamel_casePlural_'       => ucfirst(str_plural(camel_case($table))),
            ];

            foreach ($config as $key => $value) {
                if (in_array($key, ['_path_repository_', '_path_model_', '_path_controller_', '_path_views_', '_path_request_',])) {
                    @mkdir($value, 0777, true);
                }
            }

            $config = array_merge($config, Config::get('laracogs.crud.sectioned'));
            $config = $this->setConfig($config, $section, $table);
        }

        if ($this->option('bootstrap')) {
            $config['bootstrap'] = true;
        }

        if (! isset($config['template_source'])) {
            $config['template_source'] = __DIR__.'/../Templates';
        }

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
            $crudGenerator->createRoutes($config, false);

            $this->line('Building tests...');
            $crudGenerator->createTests($config);

            $this->line('Building factory...');
            $crudGenerator->createFactory($config);

            $this->line('Building facade...');
            $crudGenerator->createFacade($config);

            if ($this->option('api')) {
                $this->line('Building Api...');
                $crudGenerator->createApi($config);
            }

        } catch (Exception $e) {
            throw new Exception("Unable to generate your CRUD", 1);
        }

        if ($this->option('migration')) {
            $this->line('Building migration...');
            if ($section) {
                Artisan::call('make:migration', [
                    'name' => 'create_'.str_plural(strtolower(implode('_', $splitTable))).'_table',
                    '--table' => str_plural(strtolower(implode('_', $splitTable))),
                    '--create' => true,
                ]);
            } else {
                Artisan::call('make:migration', [
                    'name' => 'create_'.str_plural(strtolower($table)).'_table',
                    '--table' => str_plural(strtolower($table)),
                    '--create' => true,
                ]);
            }
        }

        $this->line('You may wish to add this as your testing database');
        $this->line("'testing' => [ 'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '' ],");
        $this->info('CRUD for '.$table.' is done.');
    }

    /**
     * Set the config
     *
     * @param array $config
     * @param string $section
     * @param string $table
     *
     * @return  array
     */
    public function setConfig($config, $section, $table)
    {
        if (! is_null($section)) {
            foreach ($config as $key => $value) {
                $config[$key] = str_replace('_table_', ucfirst($table), str_replace('_section_', ucfirst($section), str_replace('_sectionLowerCase_', ucfirst($section), $value)));
            }
        } else {
            foreach ($config as $key => $value) {
                $config[$key] = str_replace('_table_', ucfirst($table), $value);
            }
        }

        return $config;
    }
}
