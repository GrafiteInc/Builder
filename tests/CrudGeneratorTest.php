<?php

use org\bovigo\vfs\vfsStream;
use Yab\Laracogs\Generators\CrudGenerator;

class CrudGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected $encrypter;

    public function setUp()
    {
        $this->generator = new CrudGenerator();
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
            '_path_views_'               => vfsStream::url('resources/views'),
            '_path_tests_'               => vfsStream::url('tests'),
            '_path_request_'             => vfsStream::url('Http/Requests'),
            '_path_routes_'              => vfsStream::url('Http/routes.php'),
            'routes_prefix'              => '',
            'routes_suffix'              => '',
            '_namespace_services_'       => 'App\Services',
            '_namespace_facade_'         => 'App\Facades',
            '_namespace_repository_'     => 'App\Repositories\\'.ucfirst('testTable'),
            '_namespace_model_'          => 'App\Repositories\\'.ucfirst('testTable'),
            '_namespace_controller_'     => 'App\Http\Controllers',
            '_namespace_request_'        => 'App\Http\Requests',
            '_lower_case_'               => strtolower('testTable'),
            '_lower_casePlural_'         => str_plural(strtolower('testTable')),
            '_camel_case_'               => ucfirst(camel_case('testTable')),
            '_camel_casePlural_'         => str_plural(camel_case('testTable')),
            'template_source'            => __DIR__.'/../src/Templates',
            'tests_generated'            => 'acceptance,service,repository',
        ];
    }

    public function testControllerGenerator()
    {
        $this->crud = vfsStream::setup("Http/Controllers");
        $this->generator->createController($this->config);
        $this->assertTrue($this->crud->hasChild('Http/Controllers/TestTableController.php'));
        $contents = $this->crud->getChild('Http/Controllers/TestTableController.php');
        $this->assertTrue(strpos($contents->getContent(), 'class TestTableController extends Controller') !== false);
    }

    public function testRepositoryGenerator()
    {
        $this->crud = vfsStream::setup("Repositories/TestTable");
        $this->generator->createRepository($this->config);
        $this->assertTrue($this->crud->hasChild('Repositories/TestTable/TestTableRepository.php'));
        $contents = $this->crud->getChild('Repositories/TestTable/TestTableRepository.php');
        $this->assertTrue(strpos($contents->getContent(), 'class TestTableRepository') !== false);
    }

    public function testRequestGenerator()
    {
        $this->crud = vfsStream::setup("Http/Requests");
        $this->generator->createRequest($this->config);
        $this->assertTrue($this->crud->hasChild('Http/Requests/TestTableRequest.php'));
        $contents = $this->crud->getChild('Http/Requests/TestTableRequest.php');
        $this->assertTrue(strpos($contents->getContent(), 'class TestTableRequest') !== false);
    }

    public function testServiceGenerator()
    {
        $this->crud = vfsStream::setup("Services");
        $this->generator->createService($this->config);
        $this->assertTrue($this->crud->hasChild('Services/TestTableService.php'));
        $contents = $this->crud->getChild('Services/TestTableService.php');
        $this->assertTrue(strpos($contents->getContent(), 'class TestTableService') !== false);
    }

    public function testRoutesGenerator()
    {
        $this->crud = vfsStream::setup("Http");
        file_put_contents(vfsStream::url('Http/routes.php'), 'test');
        $this->generator->createRoutes($this->config, false);
        $contents = $this->crud->getChild('Http/routes.php');
        $this->assertTrue(strpos($contents->getContent(), 'TestTableController') !== false);
    }

    public function testViewsGenerator()
    {
        $this->crud = vfsStream::setup("resources/views");
        $this->generator->createViews($this->config);
        $this->assertTrue($this->crud->hasChild('resources/views/testtables/index.blade.php'));
        $contents = $this->crud->getChild('resources/views/testtables/index.blade.php');
        $this->assertTrue(strpos($contents->getContent(), '$testtable') !== false);
    }

    public function testTestGenerator()
    {
        $this->crud = vfsStream::setup("tests");
        $this->generator->createTests($this->config);
        $this->assertTrue($this->crud->hasChild('tests/TestTableAcceptanceTest.php'));
        $contents = $this->crud->getChild('tests/TestTableAcceptanceTest.php');
        $this->assertTrue(strpos($contents->getContent(), 'class TestTableAcceptanceTest') !== false);
        $this->assertTrue($this->crud->hasChild('tests/TestTableRepositoryTest.php'));
        $contents = $this->crud->getChild('tests/TestTableRepositoryTest.php');
        $this->assertTrue(strpos($contents->getContent(), 'class TestTableRepositoryTest') !== false);
        $this->assertTrue($this->crud->hasChild('tests/TestTableServiceTest.php'));
        $contents = $this->crud->getChild('tests/TestTableServiceTest.php');
        $this->assertTrue(strpos($contents->getContent(), 'class TestTableServiceTest') !== false);
    }

    /*
    |--------------------------------------------------------------------------
    | Other method tests
    |--------------------------------------------------------------------------
    */

    public function testPrepareTableDefinition()
    {
        $table = "id:increments,name:string,details:text";
        $result = $this->generator->prepareTableDefinition($table);

        $this->assertTrue((bool) strstr($result, 'id'));
        $this->assertTrue((bool) strstr($result, 'name'));
        $this->assertTrue((bool) strstr($result, 'details'));
    }

    public function testPrepareTableExample()
    {
        $table = "id:increments,name:string,details:text,created_on:dateTime";
        $result = $this->generator->prepareTableExample($table);

        $this->assertTrue((bool) strstr($result, 'laravel'));
        $this->assertTrue((bool) strstr($result, 'I am Batman'));
        $this->assertTrue((bool) strstr($result, '1'));
    }

}
