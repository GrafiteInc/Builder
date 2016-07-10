<?php

use org\bovigo\vfs\vfsStream;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Container\Container as Container;
use Illuminate\Support\Facades\Facade as Facade;
use Yab\Laracogs\Generators\DatabaseGenerator;

class DatabaseGeneratorTest extends AppTest
{
    protected $encrypter;

   public function setUp()
    {
        parent::setUp();
        $this->generator = new DatabaseGenerator();
        $this->config = [
            'bootstrap'                  => false,
            'semantic'                   => false,
            'relationships'              => null,
            'schema'                     => null,
            '_path_facade_'              => vfsStream::url('Facades'),
            '_path_service_'             => vfsStream::url('Services'),
            '_path_repository_'          => vfsStream::url('Repositories/'.ucfirst('testTable')),
            '_path_model_'               => vfsStream::url('Repositories/'.ucfirst('testTable')),
            '_path_controller_'          => vfsStream::url('Http/Controllers'),
            '_path_api_controller_'      => vfsStream::url('Http/Controllers/Api'),
            '_path_views_'               => vfsStream::url('resources/views'),
            '_path_tests_'               => vfsStream::url('tests'),
            '_path_request_'             => vfsStream::url('Http/Requests'),
            '_path_routes_'              => vfsStream::url('Http/routes.php'),
            '_path_api_routes_'          => vfsStream::url('Http/api-routes.php'),
            'routes_prefix'              => '',
            'routes_suffix'              => '',
            '_namespace_services_'       => 'App\Services',
            '_namespace_facade_'         => 'App\Facades',
            '_namespace_repository_'     => 'App\Repositories\\'.ucfirst('testTable'),
            '_namespace_model_'          => 'App\Repositories\\'.ucfirst('testTable'),
            '_namespace_controller_'     => 'App\Http\Controllers',
            '_namespace_api_controller_' => 'App\Http\Controllers\Api',
            '_namespace_request_'        => 'App\Http\Requests',
            '_lower_case_'               => strtolower('testTable'),
            '_lower_casePlural_'         => str_plural(strtolower('testTable')),
            '_camel_case_'               => ucfirst(camel_case('testTable')),
            '_camel_casePlural_'         => str_plural(camel_case('testTable')),
            'template_source'            => __DIR__.'/../src/Templates',
        ];
    }

    public function testCreateMigrationFail()
    {
        $this->setExpectedException('Exception');
        $this->generator->createMigration($this->config, 'alskfdjbajlksbdfl', 'TestTable', 'lkdblkabflabsd');
    }

    public function testCreateMigrationSuccess()
    {
        $this->generator->createMigration($this->config, '', 'TestTable', []);

        $this->assertEquals(count(glob(base_path('database/migrations').'/*')), 1);

        foreach (glob(base_path('database/migrations').'/*') as $migration) {
            unlink($migration);
        }

        $this->assertEquals(count(glob(base_path('database/migrations').'/*')), 0);
    }

    public function testPrepareSchema()
    {
        $this->generator->createMigration($this->config, '', 'TestTable', []);
        $migrations = glob(base_path('database/migrations').'/*');
        $this->assertEquals(count($migrations), 1);

        $this->generator->prepareSchema($this->config, '', 'TestTable', [], 'id:increments,name:string');

        $this->assertTrue(strpos(file_get_contents($migrations[0]), 'testtables') > 0);
        $this->assertTrue(strpos(file_get_contents($migrations[0]), "table->increments('id')") > 0);

        foreach (glob(base_path('database/migrations').'/*') as $migration) {
            unlink($migration);
        }
    }
}
