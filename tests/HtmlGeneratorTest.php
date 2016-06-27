<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Container\Container as Container;
use Illuminate\Support\Facades\Facade as Facade;
use Yab\Laracogs\Generators\HtmlGenerator;

class HtmlGeneratorTest extends PHPUnit_Framework_TestCase
{
    // use TestCase;
    protected $inputMaker;

    public function setUp()
    {
        $this->app = new Container();
        $this->app->singleton('app', Container::class);

        $config = Mockery::mock('config')->shouldReceive('get')->withAnyArgs()->andReturn(['string' => 'string'])->getMock();
        $session = Mockery::mock('session')->shouldReceive('isStarted')->withAnyArgs()->andReturn(true)->getMock();
        $request = Mockery::mock('request')->shouldReceive('old')->withAnyArgs()->andReturn([])->getMock();
        $this->app->instance('config', $config);
        $this->app->instance('session', $session);
        $this->app->instance('request', $request);

        Facade::setFacadeApplication($this->app);

        $this->html = new HtmlGenerator();
    }

    public function testMakeHidden()
    {
        $test = $this->html->makeHidden(['name' => 'test'], 'test', '');

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<input  id="Test" name="test" type="hidden" value="test">');
    }

    public function testMakeText()
    {
        $test = $this->html->makeText([
            'name' => 'test',
            'class' => 'form-control',
            'placeholder' => 'TestText'
        ], 'simple-test', 'data-thing');

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<textarea data-thing id="Test" class="form-control" name="test" placeholder="TestText">simple-test</textarea>');
    }

    public function testMakeSelected()
    {
        $test = $this->html->makeSelected([
            'name' => 'test',
            'class' => 'form-control',
            'config' => [
                'options' => [
                    'Admin' => 'admin',
                    'Member' => 'member'
                ]
            ]
        ], 'member', 'data-thing');

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<select data-thing id="Test" class="form-control" name="test"><option value="admin" >Admin</option><option value="member" selected>Member</option></select>');
    }

    public function testMakeCheckbox()
    {
        $test = $this->html->makeCheckbox([
            'name' => 'test',
            'class' => 'form-control',
        ], 'selected', '');

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<input  id="Test" selected type="checkbox" name="test">');
    }

    public function testMakeRadio()
    {
        $test = $this->html->makeRadio([
            'name' => 'test',
            'class' => 'form-control',
        ], 'selected', '');

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<input  id="Test" selected type="radio" name="test">');
    }

    public function testMakeInputString()
    {
        $test = $this->html->makeHTMLInputString([
            'config' => [
                'custom' => 'data-stuff'
            ],
            'placeholder' => 'wtf',
            'inputType' => 'text',
            'type' => 'text',
            'populated' => true,
            'name' => 'test',
            'class' => 'form-control',
            'objectValue' => 'sample Test'
        ]);

        $this->assertTrue(is_string($test));
        $this->assertEquals($test, '<input data-stuff id="Test" class="form-control" type="text" name="test"   value="sample Test" placeholder="wtf">');
    }
}
