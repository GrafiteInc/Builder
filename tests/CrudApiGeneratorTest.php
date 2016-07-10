<?php

use org\bovigo\vfs\vfsStream;
use Yab\Laracogs\Generators\CrudGenerator;

class CrudApiGeneratorTest extends PHPUnit_Framework_TestCase
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
        $this->crud = vfsStream::setup("Http/Controllers/Api");
        $this->generator->createApi($this->config, false);
        $this->assertTrue($this->crud->hasChild('Http/Controllers/Api/TestTableController.php'));
        $contents = $this->crud->getChild('Http/Controllers/Api/TestTableController.php');
        $this->assertTrue(strpos($contents->getContent(), 'class TestTableController extends Controller') !== false);
    }

}
