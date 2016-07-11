<?php

use org\bovigo\vfs\vfsStream;
use Yab\Laracogs\Services\CrudValidator;

class CrudValidatorTest extends AppTest
{
    protected $command;
    protected $validator;
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->command = Mockery::mock('Yab\Laracogs\Console\Crud');
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

    public function testInvalidSchemaValidateOptions()
    {
        $this->command->shouldReceive('option')
            ->with('ui')
            ->andReturn('bootstrap')
            ->shouldReceive('option')
            ->with('schema')
            ->andReturn('id:increments,name:string,parent_id:something')
            ->shouldReceive('option')
            ->with('migration')
            ->andReturn(true)
            ->getMock();

        $this->setExpectedException('Exception');
        $this->validator->validateSchema($this->command);
    }

    public function testValidateOptions()
    {
        $this->command->shouldReceive('option')
            ->with('ui')
            ->andReturn('bootstrap');
        $this->command->shouldReceive('option')
            ->with('schema')
            ->andReturn('id:increments,name:string,parent_id:integer');
        $this->command->shouldReceive('option')
            ->with('migration')
            ->andReturn(true);

        $test = $this->validator->validateSchema($this->command);
        $this->assertTrue($test);
    }

    public function testInvalidUIValidateOptions()
    {
        $this->command->shouldReceive('option')
            ->with('ui')
            ->andReturn('purecss');

        $this->setExpectedException('Exception');
        $this->validator->validateSchema($this->command);
    }

    public function testValidateSchema()
    {
        $this->command->shouldReceive('option')
            ->with('ui')
            ->andReturn('bootstrap');
        $this->command->shouldReceive('option')
            ->with('relationships')
            ->andReturn('hasOne|App\Parent|parent_id');
        $this->command->shouldReceive('option')
            ->with('schema')
            ->andReturn('id:increments,name:string,parent_id:integer');
        $this->command->shouldReceive('option')
            ->with('migration')
            ->andReturn(true);

        $test = $this->validator->validateOptions($this->command);
        $this->assertTrue($test);
    }

    public function testInvalidSchemaValidateSchema()
    {
        $this->command->shouldReceive('option')
            ->with('ui')
            ->andReturn('bootstrap');
        $this->command->shouldReceive('option')
            ->with('relationships')
            ->andReturn('hasOne|App\Parent|parent_id');
        $this->command->shouldReceive('option')
            ->with('schema')
            ->andReturn(null);
        $this->command->shouldReceive('option')
            ->with('migration')
            ->andReturn(false);

        $this->setExpectedException('Exception');
        $this->validator->validateOptions($this->command);
    }

    public function testInvalidMigrationValidateSchema()
    {
        $this->command->shouldReceive('option')
            ->with('ui')
            ->andReturn('bootstrap');
        $this->command->shouldReceive('option')
            ->with('schema')
            ->andReturn('id:increments,name:string,parent_id:integer');
        $this->command->shouldReceive('option')
            ->with('relationships')
            ->andReturn(null);
        $this->command->shouldReceive('option')
            ->with('migration')
            ->andReturn(false);

        $this->setExpectedException('Exception');
        $this->validator->validateOptions($this->command);
    }
}
