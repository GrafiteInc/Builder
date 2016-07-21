<?php

class AppTest extends Orchestra\Testbench\TestCase
{
    protected $app;

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app->make('Illuminate\Contracts\Http\Kernel');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Yab\Laracogs\LaracogsProvider::class,
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->withFactories(__DIR__.'/../src/Models/Factories');
        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/../src/Migrations'),
        ]);
        $this->withoutMiddleware();
        $this->withoutEvents();
    }

    public function testApiCommand()
    {
        $kernel = $this->app['Illuminate\Contracts\Console\Kernel'];
        $status = $kernel->handle(
            $input = new \Symfony\Component\Console\Input\ArrayInput([
                'command' => 'laracogs:api',
                '--no-interaction' => true
            ]),
            $output = new \Symfony\Component\Console\Output\BufferedOutput
        );

        $this->assertTrue(strpos($output->fetch(), 'php artisan laracogs:starter') > 0);
    }
}
