<?php

use Yab\Laracogs\Utilities\InputMaker;
use \Illuminate\Container\Container as Container;
use \Illuminate\Support\Facades\Facade as Facade;
use Illuminate\Support\Facades\Config;

class InputMakerTest extends PHPUnit_Framework_TestCase
{
    // use TestCase;
    protected $inputMaker;

    public function setUp()
    {
        $this->app = new Container();
        $this->app->singleton('app', 'Illuminate\Container\Container');
        $config = Mockery::mock('config')->shouldReceive('get')->withAnyArgs()->andReturn(['string' => 'string'])->getMock();
        $session = Mockery::mock('session')->shouldReceive('isStarted')->withAnyArgs()->andReturn(true)->getMock();
        $request = Mockery::mock('request')->shouldReceive('old')->withAnyArgs()->andReturn([])->getMock();
        $this->app->instance('config', $config);
        $this->app->instance('session', $session);
        $this->app->instance('request', $request);

        Facade::setFacadeApplication($this->app);

        $this->inputMaker = new InputMaker;
    }

    public function testCreate()
    {
        $object = (object) ['name' => 'test'];
        $test = $this->inputMaker->create('name', [], $object, 'form-control', false, true);

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<input  id="Name" class="form-control" type="text" name="name"   value="test" placeholder="Name">');
    }
}
