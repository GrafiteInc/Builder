<?php

namespace Yab\Laracogs\Generators;

use Illuminate\Filesystem\Filesystem;

/**
 * Generate the CRUD.
 */
class CrudGenerator
{
    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * Create the controller.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createController($config)
    {
        if (!is_dir($config['_path_controller_'])) {
            mkdir($config['_path_controller_'], 0777, true);
        }

        $request = file_get_contents($config['template_source'].'/Controller.txt');

        foreach ($config as $key => $value) {
            $request = str_replace($key, $value, $request);
        }

        $request = file_put_contents($config['_path_controller_'].'/'.$config['_camel_case_'].'Controller.php', $request);

        return $request;
    }

    /**
     * Create the repository.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createRepository($config)
    {
        $repoParts = [
            '_path_repository_',
            '_path_model_',
        ];

        foreach ($repoParts as $repoPart) {
            if (!is_dir($config[$repoPart])) {
                mkdir($config[$repoPart], 0777, true);
            }
        }

        $repo = file_get_contents($config['template_source'].'/Repository/Repository.txt');
        $model = file_get_contents($config['template_source'].'/Repository/Model.txt');
        $model = $this->configTheModel($config, $model);

        foreach ($config as $key => $value) {
            $repo = str_replace($key, $value, $repo);
            $model = str_replace($key, $value, $model);
        }

        $repository = file_put_contents($config['_path_repository_'].'/'.$config['_camel_case_'].'Repository.php', $repo);
        $model = file_put_contents($config['_path_model_'].'/'.$config['_camel_case_'].'.php', $model);

        return $repository && $model;
    }

    /**
     * Configure the model.
     *
     * @param array  $config
     * @param string $model
     *
     * @return string
     */
    public function configTheModel($config, $model)
    {
        if (!empty($config['schema'])) {
            $model = str_replace('// _camel_case_ table data', $this->prepareTableDefinition($config['schema']), $model);
        }

        if (!empty($config['relationships'])) {
            $relationships = [];

            foreach (explode(',', $config['relationships']) as $relationshipExpression) {
                $relationships[] = explode('|', $relationshipExpression);
            }

            $model = str_replace('// _camel_case_ relationships', $this->prepareModelRelationships($relationships), $model);
        }

        return $model;
    }

    /**
     * Create the request.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createRequest($config)
    {
        if (!is_dir($config['_path_request_'])) {
            mkdir($config['_path_request_'], 0777, true);
        }

        $request = file_get_contents($config['template_source'].'/Request.txt');

        foreach ($config as $key => $value) {
            $request = str_replace($key, $value, $request);
        }

        $request = file_put_contents($config['_path_request_'].'/'.$config['_camel_case_'].'Request.php', $request);

        return $request;
    }

    /**
     * Create the service.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createService($config)
    {
        if (!is_dir($config['_path_service_'])) {
            mkdir($config['_path_service_'], 0777, true);
        }

        $request = file_get_contents($config['template_source'].'/Service.txt');

        foreach ($config as $key => $value) {
            $request = str_replace($key, $value, $request);
        }

        $request = file_put_contents($config['_path_service_'].'/'.$config['_camel_case_'].'Service.php', $request);

        return $request;
    }

    /**
     * Create the routes.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createRoutes($config, $appendRoutes = true)
    {
        if ($appendRoutes) {
            $routesMaster = app_path('Http/routes.php');
        } else {
            $routesMaster = $config['_path_routes_'];
        }

        if (!empty($config['routes_prefix'])) {
            file_put_contents($routesMaster, $config['routes_prefix'], FILE_APPEND);
        }

        $routes = file_get_contents($config['template_source'].'/Routes.txt');

        foreach ($config as $key => $value) {
            $routes = str_replace($key, $value, $routes);
        }

        file_put_contents($routesMaster, $routes, FILE_APPEND);

        if (!empty($config['routes_prefix'])) {
            file_put_contents($routesMaster, $config['routes_suffix'], FILE_APPEND);
        }

        return true;
    }

    /**
     * Append to the factory.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createFactory($config)
    {
        $factory = file_get_contents($config['template_source'].'/Factory.txt');

        $factory = $this->tableSchema($config, $factory);

        $factoryMaster = base_path('database/factories/ModelFactory.php');

        foreach ($config as $key => $value) {
            $factory = str_replace($key, $value, $factory);
        }

        return file_put_contents($factoryMaster, $factory, FILE_APPEND);
    }

    /**
     * Create the facade.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createFacade($config)
    {
        if (!is_dir($config['_path_facade_'])) {
            mkdir($config['_path_facade_']);
        }

        $facade = file_get_contents($config['template_source'].'/Facade.txt');

        foreach ($config as $key => $value) {
            $facade = str_replace($key, $value, $facade);
        }

        $facade = file_put_contents($config['_path_facade_'].'/'.$config['_camel_case_'].'.php', $facade);

        return $facade;
    }

    /**
     * Create the tests.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createTests($config)
    {
        foreach (explode(',', $config['tests_generated']) as $testType) {
            $test = file_get_contents($config['template_source'].'/Tests/'.ucfirst($testType).'Test.txt');

            $test = $this->tableSchema($config, $test);

            foreach ($config as $key => $value) {
                $test = str_replace($key, $value, $test);
            }

            if (!file_put_contents($config['_path_tests_'].'/'.$config['_camel_case_'].''.ucfirst($testType).'Test.php', $test)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create the views.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createViews($config)
    {
        if (!is_dir($config['_path_views_'].'/'.$config['_lower_casePlural_'])) {
            mkdir($config['_path_views_'].'/'.$config['_lower_casePlural_']);
        }

        $viewTemplates = 'Views';

        if ($config['bootstrap']) {
            $viewTemplates = 'BootstrapViews';
        }

        if ($config['semantic']) {
            $viewTemplates = 'SemanticViews';
        }

        $createdView = false;

        foreach (glob($config['template_source'].'/'.$viewTemplates.'/*') as $file) {
            $viewContents = file_get_contents($file);
            $basename = str_replace('txt', 'php', basename($file));
            foreach ($config as $key => $value) {
                $viewContents = str_replace($key, $value, $viewContents);
            }
            $createdView = file_put_contents($config['_path_views_'].'/'.$config['_lower_casePlural_'].'/'.$basename, $viewContents);
        }

        return $createdView;
    }

    /**
     * Create the Api.
     *
     * @param array $config
     *
     * @return bool
     */
    public function createApi($config, $appendRoutes = true)
    {
        if ($appendRoutes) {
            $routesMaster = app_path('Http/api-routes.php');
        } else {
            $routesMaster = $config['_path_api_routes_'];
        }

        if (!file_exists($routesMaster)) {
            file_put_contents($routesMaster, "<?php\n\n");
        }

        if (!is_dir($config['_path_api_controller_'])) {
            mkdir($config['_path_api_controller_'], 0777, true);
        }

        $routes = file_get_contents($config['template_source'].'/ApiRoutes.txt');

        foreach ($config as $key => $value) {
            $routes = str_replace($key, $value, $routes);
        }

        file_put_contents($routesMaster, $routes, FILE_APPEND);

        $request = file_get_contents($config['template_source'].'/ApiController.txt');

        foreach ($config as $key => $value) {
            $request = str_replace($key, $value, $request);
        }

        $request = file_put_contents($config['_path_api_controller_'].'/'.$config['_camel_case_'].'Controller.php', $request);

        return $request;
    }

    /**
     * Prepare a string of the table.
     *
     * @param string $table
     *
     * @return string
     */
    public function prepareTableDefinition($table)
    {
        $tableDefintion = '';

        foreach (explode(',', $table) as $column) {
            $columnDefinition = explode(':', $column);
            $tableDefintion .= "\t\t'$columnDefinition[0]',\n";
        }

        return $tableDefintion;
    }

    /**
     * Prepare a table array example.
     *
     * @param string $table
     *
     * @return string
     */
    public function prepareTableExample($table)
    {
        $tableExample = '';

        foreach (explode(',', $table) as $key => $column) {
            $columnDefinition = explode(':', $column);
            $example = $this->createExampleByType($columnDefinition[1]);
            if ($key === 0) {
                $tableExample .= "'$columnDefinition[0]' => '$example',\n";
            } else {
                $tableExample .= "\t\t'$columnDefinition[0]' => '$example',\n";
            }
        }

        return $tableExample;
    }

    /**
     * Prepare a models relationships.
     *
     * @param array $relationships
     *
     * @return string
     */
    public function prepareModelRelationships($relationships)
    {
        $relationshipMethods = '';

        foreach ($relationships as $relation) {
            if (!isset($relation[2])) {
                $relationEnd = explode('\\', $relation[1]);
                $relation[2] = strtolower(end($relationEnd));
            }

            $method = str_singular($relation[2]);

            if (stristr($relation[0], 'many')) {
                $method = str_plural($relation[2]);
            }

            $relationshipMethods .= "\n\tpublic function ".$method.'() {';
            $relationshipMethods .= "\n\t\treturn \$this->$relation[0]($relation[1]::class);";
            $relationshipMethods .= "\n\t}";
        }

        return $relationshipMethods;
    }

    /**
     * Build a table schema.
     *
     * @param array  $config
     * @param string $string
     *
     * @return string
     */
    public function tableSchema($config, $string)
    {
        if (!empty($config['schema'])) {
            $string = str_replace('// _camel_case_ table data', $this->prepareTableExample($config['schema']), $string);
        }

        return $string;
    }

    /**
     * Create an example by type for table definitions.
     *
     * @param string $type
     *
     * @return mixed
     */
    public function createExampleByType($type)
    {
        $typeArray = [
            'bigIncrements' => 1,
            'increments'    => 1,
            'string'        => 'laravel',
            'boolean'       => 1,
            'binary'        => 'Its a bird, its a plane, no its Superman!',
            'char'          => 'a',
            'ipAddress'     => '192.168.1.1',
            'macAddress'    => 'X1:X2:X3:X4:X5:X6',
            'json'          => json_encode(['json' => 'test']),
            'text'          => 'I am Batman',
            'longText'      => 'I am Batman',
            'mediumText'    => 'I am Batman',
            'dateTime'      => date('Y-m-d h:i:s'),
            'date'          => date('Y-m-d'),
            'time'          => date('h:i:s'),
            'timestamp'     => time(),
            'float'         => 1.1,
            'decimal'       => 1.1,
            'double'        => 1.1,
            'integer'       => 1,
            'bigInteger'    => 1,
            'mediumInteger' => 1,
            'smallInteger'  => 1,
            'tinyInteger'   => 1,
        ];

        if (isset($typeArray[$type])) {
            return $typeArray[$type];
        }

        return 1;
    }
}
