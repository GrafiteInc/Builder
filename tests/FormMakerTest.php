<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Container\Container as Container;
use Illuminate\Support\Facades\Facade as Facade;
use Yab\Laracogs\Utilities\FormMaker;

class FormMakerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->app = new Container();
        $this->app->singleton('app', 'Illuminate\Container\Container');

        $config = Mockery::mock('config')->shouldReceive('get')->withAnyArgs()->andReturn(include(__DIR__.'/../src/Packages/Starter/config/form-maker.php'))->getMock();
        $request = Mockery::mock('request')->shouldReceive('old')->withAnyArgs()->andReturn([])->getMock();
        $session = Mockery::mock('session');
        $session->shouldReceive('isStarted')->withAnyArgs()->andReturn(true);
        $session->shouldReceive('get')->withAnyArgs()->andReturn(collect([]));

        $this->app->instance('config', $config);
        $this->app->instance('session', $session);
        $this->app->instance('request', $request);

        Facade::setFacadeApplication($this->app);

        $this->formMaker = new FormMaker();
    }

    public function testFromArray()
    {
        $testArray = [
            'name' => 'string',
            'age' => 'number',
        ];

        $test = $this->formMaker->fromArray($testArray);

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<div class="form-group "><label class="control-label" for="Name">Name</label><input  id="Name" class="form-control" type="text" name="name"    placeholder="Name"></div><div class="form-group "><label class="control-label" for="Age">Number</label><input  id="Age" class="form-control" type="number" name="age"    placeholder="Number"></div>');
    }

    public function testFromArrayWithColumns()
    {
        $testArray = [
            'name' => 'string',
            'age' => 'number',
        ];

        $test = $this->formMaker->fromArray($testArray, ['name']);

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<div class="form-group "><label class="control-label" for="Name">Name</label><input  id="Name" class="form-control" type="text" name="name"    placeholder="Name"></div>');
    }

    public function testFromObject()
    {
        (object) $testObject = [
            'attributes' => [
                'name' => 'Joe',
                'age' => 18,
            ],
        ];

        $columns = [
            'name' => [
                'type' => 'string',
            ],
            'age' => [
                'type' => 'number',
            ]
        ];

        $test = $this->formMaker->fromObject($testObject, $columns);

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<div class="form-group "><label class="control-label" for="Name">Name</label><input  id="Name" class="form-control" type="text" name="name"    placeholder="Name"></div><div class="form-group "><label class="control-label" for="Age">Age</label><input  id="Age" class="form-control" type="number" name="age"    placeholder="Age"></div>');
    }
}
