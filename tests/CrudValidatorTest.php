<?php

use org\bovigo\vfs\vfsStream;
use Yab\Laracogs\Services\CrudValidator;

class CrudValidatorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->mockCommand = Mockery::mock('Yab\Laracogs\Console\Crud')
            ->shouldReceive('option')
            ->with('ui')
            ->andReturn('bootstrap')
            ->shouldReceive('option')
            ->with('relationships')
            ->andReturn('hasOne|App\Parent|parent_id')
            ->shouldReceive('option')
            ->with('schema')
            ->andReturn('id:increments,name:string,parent_id:integer')
            ->shouldReceive('option')
            ->with('migration')
            ->andReturn(true)
            ->getMock();

        $this->validator = new CrudValidator();

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

    public function testApiGenerator()
    {

        // $this->crud = vfsStream::setup("Http/Controllers/Api");
        $this->validator->validateOptions($this->mockCommand);
        // $this->assertTrue($this->crud->hasChild('Http/Controllers/Api/TestTableController.php'));
        // $contents = $this->crud->getChild('Http/Controllers/Api/TestTableController.php');
        // $this->assertTrue(strpos($contents->getContent(), 'class TestTableController extends Controller') !== false);
    }

    // public function testControllerGenerator()
    // {
    //     $this->crud = vfsStream::setup("Http/Controllers");
    //     $this->generator->createController($this->config);
    //     $this->assertTrue($this->crud->hasChild('Http/Controllers/TestTableController.php'));
    //     $contents = $this->crud->getChild('Http/Controllers/TestTableController.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableController extends Controller') !== false);
    // }

    // public function testRepositoryGenerator()
    // {
    //     $this->crud = vfsStream::setup("Repositories/TestTable");
    //     $this->generator->createRepository($this->config);
    //     $this->assertTrue($this->crud->hasChild('Repositories/TestTable/TestTableRepository.php'));
    //     $contents = $this->crud->getChild('Repositories/TestTable/TestTableRepository.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableRepository') !== false);
    // }

    // public function testRequestGenerator()
    // {
    //     $this->crud = vfsStream::setup("Http/Requests");
    //     $this->generator->createRequest($this->config);
    //     $this->assertTrue($this->crud->hasChild('Http/Requests/TestTableRequest.php'));
    //     $contents = $this->crud->getChild('Http/Requests/TestTableRequest.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableRequest') !== false);
    // }

    // public function testServiceGenerator()
    // {
    //     $this->crud = vfsStream::setup("Services");
    //     $this->generator->createService($this->config);
    //     $this->assertTrue($this->crud->hasChild('Services/TestTableService.php'));
    //     $contents = $this->crud->getChild('Services/TestTableService.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableService') !== false);
    // }

    // public function testRoutesGenerator()
    // {
    //     $this->crud = vfsStream::setup("Http");
    //     file_put_contents(vfsStream::url('Http/routes.php'), 'test');
    //     $this->generator->createRoutes($this->config, false);
    //     $contents = $this->crud->getChild('Http/routes.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'TestTableController') !== false);
    // }

    // public function testViewsGenerator()
    // {
    //     $this->crud = vfsStream::setup("resources/views");
    //     $this->generator->createViews($this->config);
    //     $this->assertTrue($this->crud->hasChild('resources/views/testtables/index.blade.php'));
    //     $contents = $this->crud->getChild('resources/views/testtables/index.blade.php');
    //     $this->assertTrue(strpos($contents->getContent(), '$testtable') !== false);
    // }

    // public function testTestGenerator()
    // {
    //     $this->crud = vfsStream::setup("tests");
    //     $this->assertTrue($this->generator->createTests($this->config, false));

    //     $this->assertTrue($this->crud->hasChild('tests/acceptance/TestTableAcceptanceTest.php'));
    //     $contents = $this->crud->getChild('tests/acceptance/TestTableAcceptanceTest.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableAcceptanceTest') !== false);

    //     $this->assertTrue($this->crud->hasChild('tests/integration/TestTableRepositoryIntegrationTest.php'));
    //     $contents = $this->crud->getChild('tests/integration/TestTableRepositoryIntegrationTest.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableRepositoryIntegrationTest') !== false);

    //     $this->assertTrue($this->crud->hasChild('tests/integration/TestTableServiceIntegrationTest.php'));
    //     $contents = $this->crud->getChild('tests/integration/TestTableServiceIntegrationTest.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableServiceIntegrationTest') !== false);
    // }

    // public function testTestGeneratorServiceOnly()
    // {
    //     $this->crud = vfsStream::setup("tests");
    //     $this->assertTrue($this->generator->createTests($this->config, true));

    //     $this->assertFalse($this->crud->hasChild('tests/acceptance/TestTableAcceptanceTest.php'));

    //     $this->assertTrue($this->crud->hasChild('tests/integration/TestTableRepositoryIntegrationTest.php'));
    //     $contents = $this->crud->getChild('tests/integration/TestTableRepositoryIntegrationTest.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableRepositoryIntegrationTest') !== false);

    //     $this->assertTrue($this->crud->hasChild('tests/integration/TestTableServiceIntegrationTest.php'));
    //     $contents = $this->crud->getChild('tests/integration/TestTableServiceIntegrationTest.php');
    //     $this->assertTrue(strpos($contents->getContent(), 'class TestTableServiceIntegrationTest') !== false);
    // }

    // /*
    // |--------------------------------------------------------------------------
    // | Other method tests
    // |--------------------------------------------------------------------------
    // */

    // public function testPrepareTableDefinition()
    // {
    //     $table = "id:increments,name:string,details:text";
    //     $result = $this->generator->prepareTableDefinition($table);

    //     $this->assertTrue((bool) strstr($result, 'id'));
    //     $this->assertTrue((bool) strstr($result, 'name'));
    //     $this->assertTrue((bool) strstr($result, 'details'));
    // }

    // public function testPrepareTableExample()
    // {
    //     $table = "id:increments,name:string,details:text,created_on:dateTime";
    //     $result = $this->generator->prepareTableExample($table);

    //     $this->assertTrue((bool) strstr($result, 'laravel'));
    //     $this->assertTrue((bool) strstr($result, 'I am Batman'));
    //     $this->assertTrue((bool) strstr($result, '1'));
    // }

}
